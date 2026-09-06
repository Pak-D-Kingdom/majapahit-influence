<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
=======
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
>>>>>>> origin/chanan
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
<<<<<<< HEAD
    /**
     * Display the form to request a password reset link.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirimkan ke email Anda.');
        }

        return back()->withErrors(['email' => __($status)]);
=======
    public function create(): View { return view('auth.forgot-password'); }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Email tidak ditemukan atau belum dapat menerima reset password.']);
>>>>>>> origin/chanan
    }
}
