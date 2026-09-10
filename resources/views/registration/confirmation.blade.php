@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil — KERAJAAN')

@section('content')
<main class="min-h-screen bg-gradient-to-b from-blue-50/50 via-white to-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md mx-auto">

        {{-- Brand Logo Header --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 group transition-transform hover:scale-105">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3.png') }}" alt="KERAJAAN" class="h-12 w-auto object-contain">
                <span class="text-[11px] font-bold tracking-[0.22em] text-[#0c3685] uppercase">
                    KERAJAAN &bull; MAJAPAHIT INFLUENCE
                </span>
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100/80 p-8 text-center shadow-xl shadow-blue-950/5 space-y-6 backdrop-blur-sm">
            
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-blue-50 text-3xl text-[#0b64d4] border border-blue-100 shadow-inner">
                <i class="bi bi-check2"></i>
            </div>

            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-xs font-bold text-[#0b64d4] tracking-wider uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0b64d4]"></span>
                    PENDAFTARAN DITERIMA
                </div>
                <h1 class="text-2xl font-black text-[#0c3685]">Pendaftaran Berhasil Dikirim</h1>
                <p class="text-xs leading-relaxed text-slate-500">
                    Simpan nomor registrasi berikut untuk referensi pendaftaran Anda di ekosistem KERAJAAN:
                </p>
            </div>

            <div class="rounded-2xl bg-blue-50/70 border border-blue-100/80 px-4 py-4 text-lg font-black tracking-widest text-[#0b64d4]">
                {{ $registration }}
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Tim kami akan meninjau akun media sosial Anda dalam 1-3 hari kerja. Link aktivasi akan dikirimkan ke email terdaftar Anda.
            </p>

            <div class="pt-2">
                <a href="{{ url('/') }}" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#0b64d4] to-[#1698f6] hover:from-[#0c3685] hover:to-[#0b64d4] text-white font-bold text-xs uppercase tracking-wider block transition-all shadow-md shadow-blue-600/20">
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>
</main>
@endsection
