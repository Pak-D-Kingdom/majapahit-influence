<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Http\Requests\Auth\SetPasswordRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
=======
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
>>>>>>> origin/chanan
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SetPasswordController extends Controller
{
<<<<<<< HEAD
    /**
     * Show the set password form for newly approved KOL.
     */
    public function showSetPasswordForm(Request $request, string $token): View
    {
        return view('auth.set-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Set the initial password and activate the KOL account.
     */
    public function updatePassword(SetPasswordRequest $request): RedirectResponse
    {
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'is_active' => true,
                ])->save();

                // Password activation invalidates any pre-existing sessions.
                $user->revokeSessions();

                // Pastikan profil KOL aktif jika ada
                if ($user->kolProfile) {
                    $user->kolProfile->update(['status' => 'aktif']);
                }

                // Catat di Audit Trail
                AuditLog::log(
                    action: 'auth.password_changed',
                    entityType: 'User',
                    entityId: $user->id,
                    oldValues: null,
                    newValues: ['status' => 'aktif', 'activated_at' => now()->toDateTimeString()],
                    user: $user
                );

                // Langsung login-kan user
                Auth::login($user);
                request()->session()->regenerate();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('kol.dashboard')->with(
                'status',
                'Selamat datang di platform Majapahit! Akun Anda telah aktif dan password berhasil disimpan.'
            );
        }

        return back()->withErrors([
            'email' => 'Token aktivasi akun tidak valid atau sudah kedaluwarsa.',
        ]);
    }
=======
    public function create(Request $request): View { return view('auth.set-password', ['email' => $request->query('email'), 'token' => $request->query('token')]); }
    public function store(Request $request): RedirectResponse { $data = $request->validate(['token' => ['required'], 'email' => ['required', 'email'], 'password' => ['required', 'confirmed', 'min:8']]); $status = Password::reset($data, function ($user, $password): void { $user->forceFill(['password' => Hash::make($password), 'is_active' => true, 'email_verified_at' => now()])->setRememberToken(Str::random(60)); $user->save(); event(new PasswordReset($user)); }); return $status === Password::PASSWORD_RESET ? redirect()->route('login')->with('success', 'Password berhasil dibuat. Silakan masuk.') : back()->withErrors(['email' => __($status)])->withInput($request->only('email')); }
>>>>>>> origin/chanan
}
