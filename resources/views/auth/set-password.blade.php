@extends('layouts.app')

@section('title', 'Aktivasi Akun Creator | KERAJAAN')

@section('content')
<main class="relative min-h-screen flex flex-col justify-between bg-[#f8fafc] text-[#0c3685] font-sans selection:bg-[#0b64d4] selection:text-white overflow-hidden py-10 px-4 sm:px-6 lg:px-8">
    
    {{-- Ambient Landing Page Decorative Lights --}}
    <div class="pointer-events-none absolute top-[-10%] left-[-5%] w-[480px] h-[480px] rounded-full bg-gradient-to-br from-[#1698f6]/15 via-[#0b64d4]/10 to-transparent blur-3xl -z-10"></div>
    <div class="pointer-events-none absolute bottom-[-15%] right-[-10%] w-[550px] h-[550px] rounded-full bg-gradient-to-tl from-[#0b64d4]/15 via-[#1698f6]/10 to-transparent blur-3xl -z-10"></div>
    <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full bg-radial from-blue-100/30 to-transparent blur-2xl -z-10"></div>

        {{-- Brand Logo Header --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 group transition-transform hover:scale-105">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3-nobg.png') }}" alt="KERAJAAN" class="h-12 w-auto object-contain">
                <span class="text-[11px] font-bold tracking-[0.22em] text-[#0c3685] uppercase">
                    KERAJAAN &bull; kerajaan INFLUENCE
                </span>
            </a>
        </div>

        {{-- Main Set Password Card --}}
        <div class="bg-white/95 backdrop-blur-xl rounded-[28px] border border-blue-100/90 shadow-[0_25px_60px_-15px_rgba(12,54,133,0.1)] p-7 sm:p-9 transition-all duration-300 relative overflow-hidden">
            
            {{-- Top Accent Line --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#0b64d4] via-[#1698f6] to-[#78a5d6]"></div>

            <div class="mb-6">
                <h1 class="font-heading text-2xl sm:text-[26px] font-extrabold text-[#0c3685] tracking-tight">
                    Aktivasi Akun KOL Anda
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                    Buat kata sandi baru untuk mulai mengakses kolaborasi brand, produk pilihan, dan analitik di ekosistem KERAJAAN.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-rose-50/90 border border-rose-200/90 p-3.5 text-xs text-rose-800 flex items-center gap-2.5 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill text-rose-600 text-base shrink-0"></i>
                    <span class="font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.set.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                {{-- Email (Read-only) --}}
                <div>
                    <label class="block text-xs font-bold text-[#0c3685] mb-1.5 tracking-wide">
                        Alamat Email Terdaftar
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="bi bi-envelope text-sm"></i>
                        </span>
                        <input 
                            type="email" 
                            value="{{ $email }}" 
                            readonly
                            class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200/90 text-xs sm:text-sm text-slate-600 bg-slate-100/70 cursor-not-allowed"
                        >
                    </div>
                </div>

                {{-- Password with Toggle --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-[#0c3685] mb-1.5 tracking-wide">
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 group-focus-within:text-[#0b64d4] transition-colors">
                            <i class="bi bi-shield-lock text-sm"></i>
                        </span>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            required 
                            autofocus
                            placeholder="Minimal 6 karakter"
                            class="w-full pl-10 pr-11 py-3 rounded-2xl border border-slate-200/90 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition duration-200 bg-slate-50/50 hover:bg-white focus:bg-white"
                        >
                        <button 
                            type="button" 
                            id="togglePassword" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-[#0c3685] transition-colors focus:outline-none"
                            aria-label="Tampilkan atau sembunyikan kata sandi"
                        >
                            <i class="bi bi-eye text-sm" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Password Confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-[#0c3685] mb-1.5 tracking-wide">
                        Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 group-focus-within:text-[#0b64d4] transition-colors">
                            <i class="bi bi-shield-check text-sm"></i>
                        </span>
                        <input 
                            type="password" 
                            id="password_confirmation"
                            name="password_confirmation" 
                            required 
                            placeholder="Ulangi kata sandi baru"
                            class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200/90 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition duration-200 bg-slate-50/50 hover:bg-white focus:bg-white"
                        >
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="w-full mt-2 py-3.5 px-6 rounded-full bg-gradient-to-r from-[#0b64d4] via-[#107ee8] to-[#1698f6] hover:shadow-[0_12px_28px_-6px_rgba(11,100,212,0.4)] hover:scale-[1.01] active:scale-[0.99] text-white font-heading font-bold text-xs sm:text-sm uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                    <span>Simpan Kata Sandi & Masuk</span>
                    <i class="bi bi-arrow-right text-sm"></i>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('bi-eye', !isPassword);
                toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    });
</script>
@endpush
