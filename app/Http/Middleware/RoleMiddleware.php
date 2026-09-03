<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // 1. Pastikan user sudah login
        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('login'));
        }

        // 2. Pastikan akun user aktif
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.'], 403);
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
            ]);
        }

        // 3. Validasi role jika ada parameter role yang ditentukan
        if (! empty($roles)) {
            $allowedRoles = [];
            foreach ($roles as $role) {
                foreach (explode(',', $role) as $r) {
                    $cleaned = trim($r);
                    if ($cleaned !== '') {
                        $allowedRoles[] = $cleaned;
                    }
                }
            }

            $hasAccess = false;
            foreach ($allowedRoles as $role) {
                if ($user->hasRole($role)) {
                    $hasAccess = true;
                    break;
                }
            }

            if (! $hasAccess) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.'], 403);
                }

                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
            }
        }

        return $next($request);
    }
}
