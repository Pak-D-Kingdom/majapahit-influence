<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SetPasswordController extends Controller
{
    public function create(Request $request): View { return view('auth.set-password', ['email' => $request->query('email'), 'token' => $request->query('token')]); }
    public function store(Request $request): RedirectResponse { $data = $request->validate(['token' => ['required'], 'email' => ['required', 'email'], 'password' => ['required', 'confirmed', 'min:8']]); $status = Password::reset($data, function ($user, $password): void { $user->forceFill(['password' => Hash::make($password), 'is_active' => true, 'email_verified_at' => now()])->setRememberToken(Str::random(60)); $user->save(); event(new PasswordReset($user)); }); return $status === Password::PASSWORD_RESET ? redirect()->route('login')->with('success', 'Password berhasil dibuat. Silakan masuk.') : back()->withErrors(['email' => __($status)])->withInput($request->only('email')); }
}
