<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\AuditLog;
use App\Models\User;
=======
use Illuminate\Auth\Events\PasswordReset;
>>>>>>> origin/chanan
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
<<<<<<< HEAD
    /**
     * Display the password reset view for the given token.
     */
    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset the given user's password.
     */
    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // A password reset invalidates all previously issued sessions.
                $user->revokeSessions();

                AuditLog::log(
                    action: 'auth.password_reset',
                    entityType: 'User',
                    entityId: $user->id,
                    oldValues: null,
                    newValues: ['method' => 'self_service_reset'],
                    user: $user
                );
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password Anda berhasil diperbarui. Silakan login kembali.');
        }

        return back()->withErrors(['email' => __($status)]);
=======
    public function create(Request $request): View { return view('auth.reset-password', ['token' => $request->query('token'), 'email' => $request->query('email')]); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['token' => ['required'], 'email' => ['required', 'email'], 'password' => ['required', 'confirmed', 'min:8']]);
        $status = Password::reset($data, function ($user, string $password): void {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset. Silakan masuk.')
            : back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.'])->withInput($request->only('email'));
>>>>>>> origin/chanan
    }
}
