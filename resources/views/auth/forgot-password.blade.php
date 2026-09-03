@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
    <div class="auth-header">
        <h2>Lupa Kata Sandi?</h2>
        <p>Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
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

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

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

        {{-- Submit Button --}}
        <button type="submit" class="btn-submit" style="margin-top: 10px;">
            <span>Kirim Tautan Reset</span>
            <i class="bi bi-send" style="font-size: 16px;"></i>
        </button>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('login') }}" class="auth-link" style="display: inline-flex; align-items: center; gap: 6px;">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Halaman Masuk</span>
            </a>
        </div>
    </form>
@endsection
