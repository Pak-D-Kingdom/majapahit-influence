@extends('layouts.auth')

@section('title', 'Masuk ke Akun')

@section('content')
    <div class="auth-header">
        <h2>Selamat Datang Kembali</h2>
        <p>Masuk untuk mengelola campaign, endorsement, atau komisi Anda.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" novalidate>
        @csrf

        {{-- Email Field --}}
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-wrapper">
                <i class="bi bi-envelope input-icon"></i>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="nama@email.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>
            @error('email')
                <div class="invalid-feedback">
                    <i class="bi bi-info-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password Field --}}
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-wrapper">
                <i class="bi bi-lock input-icon"></i>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••"
                    required
                >
                <button
                    type="button"
                    class="password-toggle-btn"
                    onclick="togglePassword('password', this)"
                    title="Tampilkan / Sembunyikan Password"
                >
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">
                    <i class="bi bi-info-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="form-options">
            <label class="custom-checkbox">
                <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <span>Ingat saya di perangkat ini</span>
            </label>

            <a href="{{ route('password.request') }}" class="auth-link">
                Lupa password?
            </a>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn-submit">
            <span>Masuk Sekarang</span>
            <i class="bi bi-arrow-right-short" style="font-size: 20px;"></i>
        </button>
    </form>
@endsection
