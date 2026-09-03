@extends('layouts.auth')

@section('title', 'Aktivasi Akun KOL — Set Password')

@section('content')
    <div class="auth-header">
        <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(254, 194, 0, 0.15); border: 1px solid rgba(254, 194, 0, 0.4); color: var(--yellow); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="bi bi-patch-check-fill"></i> Pendaftaran Disetujui
        </div>
        <h2>Aktivasi Akun KOL Anda</h2>
        <p>Selamat bergabung di Majapahit Influence! Silakan buat kata sandi untuk mengamankan akun dan mengakses dashboard endorsement Anda.</p>
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

    <form method="POST" action="{{ route('password.set.post') }}" novalidate>
        @csrf

        {{-- Hidden Activation Token --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email Field --}}
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email Terdaftar</label>
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

        {{-- Password Field --}}
        <div class="form-group">
            <label for="password" class="form-label">Buat Kata Sandi Baru</label>
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

        {{-- Password Confirmation Field --}}
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
            <span>Aktifkan Akun & Masuk Dashboard</span>
            <i class="bi bi-stars" style="font-size: 18px;"></i>
        </button>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('login') }}" class="auth-link">
                Sudah punya akun? Masuk di sini
            </a>
        </div>
    </form>
@endsection
