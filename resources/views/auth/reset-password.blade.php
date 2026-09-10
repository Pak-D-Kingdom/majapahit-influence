@extends('layouts.app')

@section('title', 'Atur Ulang Kata Sandi — KERAJAAN')

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

        {{-- Main Card --}}
        <div class="bg-white rounded-3xl border border-blue-100/80 shadow-xl shadow-blue-950/5 p-7 sm:p-9 backdrop-blur-sm">
            
            <div class="mb-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-xs font-bold text-[#0b64d4] tracking-wider uppercase mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0b64d4]"></span>
                    KATA SANDI BARU
                </div>
                <h1 class="text-2xl font-black text-[#0c3685] tracking-tight">Buat Kata Sandi Baru</h1>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Gunakan kombinasi minimal 8 karakter yang aman untuk akun Anda.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-200/80 p-3.5 text-xs text-rose-800 flex items-center gap-2.5">
                    <i class="bi bi-exclamation-circle-fill text-rose-600 text-sm"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

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
                            value="{{ old('email', $email) }}" 
                            required 
                            readonly
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-600 bg-slate-100/80 cursor-not-allowed"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3685] mb-1.5">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            autofocus
                            placeholder="Minimal 8 karakter"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0c3685] mb-1.5">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            placeholder="Ulangi kata sandi baru"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white"
                        >
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 rounded-xl bg-gradient-to-r from-[#0b64d4] to-[#1698f6] hover:from-[#0c3685] hover:to-[#0b64d4] text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md shadow-blue-600/20 flex items-center justify-center gap-2">
                    <span>Simpan Kata Sandi Baru</span>
                    <i class="bi bi-check2"></i>
                </button>
            </form>

        </div>

    </div>
</main>
@endsection
