<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (auth()->check()) return $this->redirectByRole();
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']) + ['is_active' => true];
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        $request->user()->update(['last_login_at' => now()]);
        return $this->redirectByRole();
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }

    private function redirectByRole(): RedirectResponse
    {
        $user = auth()->user();
        if ($user->isSuperadmin()) return redirect()->route('superadmin.dashboard');
        if ($user->isKol()) return redirect()->route('kol.dashboard');
        Auth::logout();
        abort(403, 'Akun belum memiliki role yang valid.');
    }
}
