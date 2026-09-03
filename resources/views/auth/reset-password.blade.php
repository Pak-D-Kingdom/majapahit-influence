@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
    <div class="auth-header">
        <h2>Atur Ulang Kata Sandi</h2>
        <p>Silakan buat kata sandi baru yang aman untuk akun Anda.</p>
    </div>

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

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf

        {{-- Hidden Token --}}
        <input type="hidden" name="token" value="{{ $token }}">

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
                    value="{{ old('email', $email) }}"
                    required
                >
            </div>
            @error('email')
                <div class="invalid-feedback">
                    <i class="bi bi-info-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        {{-- New Password Field --}}
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi Baru</label>
            <div class="input-wrapper">
                <i class="bi bi-lock input-icon"></i>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimal 8 karakter"
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

        {{-- Confirm Password Field --}}
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
            <div class="input-wrapper">
                <i class="bi bi-shield-lock input-icon"></i>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi kata sandi baru"
                    required
                >
                <button
                    type="button"
                    class="password-toggle-btn"
                    onclick="togglePassword('password_confirmation', this)"
                    title="Tampilkan / Sembunyikan Password"
                >
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn-submit" style="margin-top: 10px;">
            <span>Simpan Kata Sandi Baru</span>
            <i class="bi bi-check-lg" style="font-size: 18px;"></i>
        </button>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('login') }}" class="auth-link">
                Kembali ke Halaman Masuk
            </a>
        </div>
    </form>
@endsection
