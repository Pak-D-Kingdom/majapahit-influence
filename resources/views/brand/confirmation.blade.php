@extends('layouts.app')

@section('title', 'Pendaftaran Brand Berhasil — KERAJAAN')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50/50 via-white to-slate-50 flex flex-col justify-between py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full mx-auto my-auto">
        
        {{-- Brand Logo Header --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 group transition-transform hover:scale-105">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3.png') }}" alt="KERAJAAN" class="h-12 w-auto object-contain">
                <span class="text-[11px] font-bold tracking-[0.22em] text-[#0c3685] uppercase">
                    KERAJAAN &bull; kerajaan INFLUENCE
                </span>
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-blue-950/5 border border-blue-100/80 p-8 text-center space-y-6 backdrop-blur-sm">
            
            <div class="w-16 h-16 rounded-full bg-blue-50 text-[#0b64d4] border border-blue-100/80 flex items-center justify-center text-3xl mx-auto shadow-inner">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-xs font-bold text-[#0b64d4] tracking-wider uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0b64d4]"></span>
                    PENGAJUAN TERKIRIM
                </div>
                <h1 class="text-2xl font-black text-[#0c3685]">Terima Kasih, {{ $registration->pic_name }}!</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pendaftaran untuk brand <strong class="text-slate-800">{{ $registration->brand_name }}</strong> (Layanan: <span class="font-bold text-[#0b64d4]">{{ $registration->service_need_label }}</span>) telah berhasil kami terima.
                </p>
            </div>

            <div class="bg-slate-50/70 rounded-2xl p-4 border border-slate-100 text-left space-y-2.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Nomor WhatsApp:</span>
                    <span class="font-bold text-slate-800">{{ $registration->pic_phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Email Bisnis:</span>
                    <span class="font-bold text-slate-800">{{ $registration->pic_email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kategori:</span>
                    <span class="font-bold text-slate-800">{{ $registration->industry_category }}</span>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <a href="{{ route('catalog.index') }}" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#0b64d4] to-[#1698f6] hover:from-[#0c3685] hover:to-[#0b64d4] text-white font-bold text-xs uppercase tracking-wider block transition-all shadow-md shadow-blue-600/20">
                    <i class="bi bi-shop mr-1"></i> Jelajahi Katalog E-Commerce
                </a>
                <a href="{{ url('/') }}" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs block transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
