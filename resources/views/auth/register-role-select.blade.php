@extends('layouts.app')

@section('title', 'Pilih Peran Pendaftaran — KERAJAAN')

@section('content')
<main class="min-h-screen bg-gradient-to-b from-blue-50/60 via-white to-slate-50 flex flex-col justify-between py-10 sm:py-14 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto w-full my-auto">

        {{-- Brand Logo Header --}}
        <div class="text-center mb-10">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 group transition-transform hover:scale-105 mb-4">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3-nobg.png') }}" alt="KERAJAAN" class="h-12 sm:h-14 w-auto object-contain">
                <span class="text-[11px] font-bold tracking-[0.22em] text-[#0c3685] uppercase">
                    KERAJAAN &bull; CREATOR COMMERCE
                </span>
            </a>



            <h1 class="text-2xl sm:text-4xl font-black text-[#0c3685] tracking-tight">
                Mulai Kolaborasi di Ekosistem KERAJAAN
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2.5 max-w-xl mx-auto leading-relaxed">
                Pilih jenis akun yang sesuai dengan profil Anda untuk membuka akses eksklusif ke katalog produk, bank konten, dan kemitraan resmi.
            </p>
        </div>

        {{-- Role Cards Grid (2 Column) --}}
        <div class="grid md:grid-cols-2 gap-6 sm:gap-8">

            {{-- Card 1: Creator / KOL --}}
            <div class="group relative bg-white rounded-3xl border-2 border-blue-100/90 shadow-xl shadow-blue-950/5 hover:border-[#0b64d4] hover:shadow-2xl hover:shadow-blue-600/10 p-7 sm:p-9 flex flex-col justify-between transition-all duration-300 transform hover:-translate-y-1">
                
                {{-- Top Badge & Icon --}}
                <div>

                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#0b64d4] to-[#1698f6] text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-600/25 mb-5 group-hover:scale-105 transition-transform">
                        <i class="bi bi-stars"></i>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-[#0c3685] tracking-tight">
                        Creator / Influencer
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        Pilih produk unggulan, unduh materi promosi berkualitas tinggi di Bank Konten, dan dapatkan komisi penjualan yang terkunci.
                    </p>

                    {{-- Benefit Checklist --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0b64d4] text-sm shrink-0 mt-0.5"></i>
                            <span>Akses produk siap jual dari <strong>Brand Partner</strong></span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0b64d4] text-sm shrink-0 mt-0.5"></i>
                            <span>Materi promosi instan & video di <strong>Bank Konten</strong></span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0b64d4] text-sm shrink-0 mt-0.5"></i>
                            <span>Peluang produk sampel gratis untuk review</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0b64d4] text-sm shrink-0 mt-0.5"></i>
                            <span>Komisi transparan dengan penarikan mudah</span>
                        </div>
                    </div>
                </div>

                {{-- Action CTA --}}
                <div class="mt-8 pt-4">
                    <a href="{{ route('registration.create') }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl font-bold text-xs text-white bg-gradient-to-r from-[#0b64d4] to-[#1698f6] hover:from-[#0953b3] hover:to-[#0b64d4] shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30 transition-all duration-200">
                        <span>Daftar sebagai Creator</span>
                        <i class="bi bi-arrow-right-short text-lg"></i>
                    </a>
                </div>
            </div>

            {{-- Card 2: Brand Partner --}}
            <div class="group relative bg-white rounded-3xl border-2 border-blue-100/90 shadow-xl shadow-blue-950/5 hover:border-[#0c3685] hover:shadow-2xl hover:shadow-blue-950/10 p-7 sm:p-9 flex flex-col justify-between transition-all duration-300 transform hover:-translate-y-1">
                
                {{-- Top Badge & Icon --}}
                <div>

                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#0c3685] to-[#0b64d4] text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-950/25 mb-5 group-hover:scale-105 transition-transform">
                        <i class="bi bi-shop"></i>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-[#0c3685] tracking-tight">
                        Brand Partner & Bisnis
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        Pasarkan produk Anda ke ribuan kreator terverifikasi, buat materi promosi terpusat, dan kelola endorsement tanpa ribet.
                    </p>

                    {{-- Benefit Checklist --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0c3685] text-sm shrink-0 mt-0.5"></i>
                            <span>Listing produk di <strong>Katalog E-Commerce KERAJAAN</strong></span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0c3685] text-sm shrink-0 mt-0.5"></i>
                            <span>Jangkau ribuan kreator aktif untuk promosi</span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0c3685] text-sm shrink-0 mt-0.5"></i>
                            <span>Upload materi promosi ke <strong>Bank Konten</strong></span>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <i class="bi bi-check-circle-fill text-[#0c3685] text-sm shrink-0 mt-0.5"></i>
                            <span>Laporan performa penjualan & analitik kampanye</span>
                        </div>
                    </div>
                </div>

                {{-- Action CTA --}}
                <div class="mt-8 pt-4">
                    <a href="{{ route('brand.register') }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl font-bold text-xs text-white bg-gradient-to-r from-[#0c3685] to-[#0b64d4] hover:from-[#071d49] hover:to-[#0c3685] shadow-lg shadow-blue-950/20 hover:shadow-xl hover:shadow-blue-950/30 transition-all duration-200">
                        <span>Daftar sebagai Brand</span>
                        <i class="bi bi-arrow-right-short text-lg"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- Footer Links & Return --}}
        <div class="mt-10 text-center space-y-3">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun terdaftar? 
                <a href="{{ route('login') }}" class="font-bold text-[#0b64d4] hover:underline">
                    Masuk di sini
                </a>
            </p>
            <div>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

    </div>
</main>
@endsection
