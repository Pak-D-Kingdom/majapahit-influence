<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SetPasswordRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SetPasswordController extends Controller
{
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

                // Pastikan profil KOL aktif jika ada
                if ($user->kolProfile) {
                    $user->kolProfile->update(['status' => 'aktif']);
                }

                // Catat di Audit Trail
                AuditLog::log(
                    action: 'set_initial_password',
                    entityType: 'User',
                    entityId: $user->id,
                    oldValues: null,
                    newValues: ['status' => 'aktif', 'activated_at' => now()->toDateTimeString()],
                    user: $user
                );

                // Langsung login-kan user
                Auth::login($user);
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
}
