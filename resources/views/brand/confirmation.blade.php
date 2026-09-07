@extends('layouts.app')

@section('title', 'Pendaftaran Brand Berhasil — Majapahit Influence')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-100 p-8 text-center space-y-6">
        
        <div class="w-20 h-20 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-4xl mx-auto shadow-inner">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <div class="space-y-2">
            <span class="text-xs font-bold text-amber-700 uppercase tracking-widest">Pengajuan Kemitraan Terkirim</span>
            <h1 class="text-2xl font-black text-gray-900">Terima Kasih, {{ $registration->pic_name }}!</h1>
            <p class="text-xs text-gray-600 leading-relaxed">
                Pendaftaran untuk brand <strong class="text-gray-900">{{ $registration->brand_name }}</strong> (Layanan: <span class="font-bold text-amber-800">{{ $registration->service_need_label }}</span>) telah berhasil kami terima.
            </p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 text-left space-y-2 text-xs">
            <div class="flex justify-between">
                <span class="text-gray-500">Nomor WhatsApp:</span>
                <span class="font-bold text-gray-800">{{ $registration->pic_phone }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Email:</span>
                <span class="font-bold text-gray-800">{{ $registration->pic_email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Kategori:</span>
                <span class="font-bold text-gray-800">{{ $registration->industry_category }}</span>
            </div>
        </div>

        <div class="space-y-3 pt-2">
            <a href="{{ route('catalog.index') }}" class="w-full py-3.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs uppercase tracking-wider block transition-colors shadow-md shadow-amber-600/20">
                <i class="bi bi-shop mr-1"></i> Jelajahi Katalog E-Commerce
            </a>
            <a href="{{ url('/') }}" class="w-full py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs block transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
