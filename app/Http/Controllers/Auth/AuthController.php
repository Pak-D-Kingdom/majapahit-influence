<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form (API fallback).
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isKol()) {
                return redirect()->route('kol.dashboard');
            }

            return response()->json(['message' => 'Already authenticated', 'redirect' => '/'], 200);
        }

        return response()->json([
            'message' => 'Please authenticate. Send POST request to /login with email and password.',
            'login_url' => route('login.post')
        ], 401);
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            $request->hitRateLimiter();

            AuditLog::log(
                action: 'auth.login_failed',
                entityType: 'User',
                entityId: User::where('email', $request->string('email'))->value('id'),
                newValues: ['reason' => 'invalid_credentials'],
            );

            return response()->json([
                'message' => 'Email atau password yang Anda masukkan salah.',
            ], 401);
        }

        $user = Auth::user();

        // Cek apakah akun aktif
        if (! $user->is_active) {
            AuditLog::log(
                action: 'auth.login_failed',
                entityType: 'User',
                entityId: $user->id,
                newValues: ['reason' => 'inactive_account'],
                user: $user,
            );

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
            ], 403);
        }

        // Reset rate limiter setelah login sukses
        $request->clearRateLimiter();

        // Update waktu login terakhir
        $user->forceFill(['last_login_at' => now()])->save();

        // Regenerasi session ID untuk mencegah session fixation
        $request->session()->regenerate();

        // Catat jejak login ke audit_logs
        AuditLog::log(
            action: 'auth.login',
            entityType: 'User',
            entityId: $user->id,
            oldValues: null,
            newValues: [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
            user: $user
        );

        // Return JSON response for API testing
        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'role' => $user->isAdmin() ? 'admin' : ($user->isKol() ? 'kol' : 'user')
        ], 200);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            AuditLog::log(
                action: 'auth.logout',
                entityType: 'User',
                entityId: $user->id,
                oldValues: null,
                newValues: ['ip' => $request->ip()],
                user: $user
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Anda telah berhasil logout.'
        ], 200);
    }
}
