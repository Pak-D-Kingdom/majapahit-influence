@extends('layouts.app')

@section('title', 'Masuk — KERAJAAN')

@section('content')
<main class="min-h-screen flex flex-col justify-between bg-gradient-to-b from-blue-50/40 via-white to-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full mx-auto my-auto">

        {{-- Brand Logo Header --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 group transition-transform hover:scale-105">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3-nobg.png') }}" alt="KERAJAAN" class="h-12 w-auto object-contain">
                <span class="text-[11px] font-bold tracking-[0.22em] text-[#0c3685] uppercase">
                    KERAJAAN &bull; kerajaan INFLUENCE
                </span>
            </a>
        </div>

        {{-- Main Login Card --}}
        <div class="bg-white rounded-3xl border border-blue-100/80 shadow-xl shadow-blue-950/5 p-7 sm:p-9 backdrop-blur-sm">
            
            <div class="mb-6">
                <h1 class="text-2xl font-black text-[#0c3685] tracking-tight">Selamat Datang Kembali</h1>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Masuk untuk mengelola kolaborasi, katalog produk, dan analitik Anda.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 p-3.5 text-xs text-emerald-800 flex items-center gap-2.5">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-200/80 p-3.5 text-xs text-rose-800 flex items-center gap-2.5">
                    <i class="bi bi-exclamation-circle-fill text-rose-600 text-sm"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-[#0c3685] mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            placeholder="nama@domain.com"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white"
                        >
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-[#0c3685]">
                            Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-[#0b64d4] hover:text-[#0c3685] transition">
                            Lupa password?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white"
                        >
                    </div>
                </div>

                <div class="flex items-center pt-1">
                    <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-[#0b64d4] focus:ring-[#0b64d4] cursor-pointer">
                        <span>Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full mt-2 py-3 rounded-xl bg-gradient-to-r from-[#0b64d4] to-[#1698f6] hover:from-[#0c3685] hover:to-[#0b64d4] text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md shadow-blue-600/20 flex items-center justify-center gap-2">
                    <span>Masuk ke Akun</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 space-y-2.5 text-center">
                <p class="text-xs text-slate-500">
                    Belum punya akun di ekosistem KERAJAAN?
                </p>
                <div class="flex items-center justify-center gap-3 text-xs font-bold">
                    <a href="{{ route('registration.create') }}" class="text-[#0b64d4] hover:text-[#0c3685] hover:underline flex items-center gap-1">
                        <i class="bi bi-person-plus"></i> Gabung Creator
                    </a>
                    <span class="text-slate-300">&bull;</span>
                    <a href="{{ route('brand.register') }}" class="text-[#0b64d4] hover:text-[#0c3685] hover:underline flex items-center gap-1">
                        <i class="bi bi-building"></i> Gabung Brand
                    </a>
                </div>
            </div>

        </div>

        {{-- Footer simple back link --}}
        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#0b64d4] transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

    </div>
</main>
@endsection
