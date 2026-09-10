@extends('layouts.app')

@section('title', 'Lupa Kata Sandi — KERAJAAN')

@section('content')
<main class="relative min-h-screen flex flex-col justify-between bg-[#f8fafc] text-[#0c3685] font-sans selection:bg-[#0b64d4] selection:text-white overflow-hidden py-10 px-4 sm:px-6 lg:px-8">
    
    {{-- Ambient Landing Page Decorative Lights --}}
    <div class="pointer-events-none absolute top-[-10%] left-[-5%] w-[480px] h-[480px] rounded-full bg-gradient-to-br from-[#1698f6]/15 via-[#0b64d4]/10 to-transparent blur-3xl -z-10"></div>
    <div class="pointer-events-none absolute bottom-[-15%] right-[-10%] w-[550px] h-[550px] rounded-full bg-gradient-to-tl from-[#0b64d4]/15 via-[#1698f6]/10 to-transparent blur-3xl -z-10"></div>
    <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full bg-radial from-blue-100/30 to-transparent blur-2xl -z-10"></div>

    <div class="max-w-md w-full mx-auto my-auto relative z-10">

        {{-- Brand Logo & Tagline Header --}}
        <div class="text-center mb-7">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2.5 group transition-transform duration-300 hover:scale-105">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3.png') }}" alt="KERAJAAN" class="h-12 sm:h-14 w-auto object-contain drop-shadow-sm">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 border border-blue-100/90 shadow-sm backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#1698f6] animate-pulse"></span>
                    <span class="text-[10px] font-extrabold tracking-[0.22em] text-[#0c3685] uppercase">
                        PEMULIHAN AKUN
                    </span>
                </div>
            </a>
        </div>

        {{-- Main Recovery Card --}}
        <div class="bg-white/95 backdrop-blur-xl rounded-[28px] border border-blue-100/90 shadow-[0_25px_60px_-15px_rgba(12,54,133,0.1)] p-7 sm:p-9 transition-all duration-300 relative overflow-hidden">
            
            {{-- Top Accent Line --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#0b64d4] via-[#1698f6] to-[#78a5d6]"></div>

            <div class="mb-6">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0b64d4] hover:text-[#0c3685] mb-3 transition group">
                    <i class="bi bi-arrow-left group-hover:-translate-x-0.5 transition-transform"></i>
                    <span>Kembali ke Halaman Masuk</span>
                </a>

                <h1 class="font-heading text-2xl sm:text-[26px] font-extrabold text-[#0c3685] tracking-tight">
                    Lupa Kata Sandi?
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                    Masukkan alamat email terdaftar Anda. Kami akan mengirimkan tautan verifikasi untuk menyetel ulang kata sandi dengan aman.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 p-3.5 text-xs text-emerald-800 flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0"></i>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-5 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 p-3.5 text-xs text-emerald-800 flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-rose-50/90 border border-rose-200/90 p-3.5 text-xs text-rose-800 flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill text-rose-600 text-base shrink-0"></i>
                    <span class="font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                {{-- Registered Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-[#0c3685] mb-1.5 tracking-wide">
                        Alamat Email Terdaftar <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 group-focus-within:text-[#0b64d4] transition-colors">
                            <i class="bi bi-envelope text-sm"></i>
                        </span>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            placeholder="nama@domain.com"
                            class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200/90 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition duration-200 bg-slate-50/50 hover:bg-white focus:bg-white"
                        >
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="w-full mt-2 py-3.5 px-6 rounded-full bg-gradient-to-r from-[#0b64d4] via-[#107ee8] to-[#1698f6] hover:shadow-[0_12px_28px_-6px_rgba(11,100,212,0.4)] hover:scale-[1.01] active:scale-[0.99] text-white font-heading font-bold text-xs sm:text-sm uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                    <span>Kirim Tautan Pemulihan</span>
                    <i class="bi bi-send text-sm"></i>
                </button>
            </form>

        </div>

        {{-- Return to Home Link --}}
        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#0b64d4] transition duration-200 py-1 px-3 rounded-full hover:bg-white/80">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Beranda KERAJAAN</span>
            </a>
        </div>

    </div>
</main>
@endsection
