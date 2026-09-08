@extends('layouts.app')
@section('title', 'Pendaftaran berhasil')
@section('content')
<main class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10">
    <div class="w-full max-w-lg">
        <a href="{{ url('/') }}" class="mb-6 flex items-center justify-center gap-3 group">
            <div class="flex size-11 items-center justify-center rounded-xl bg-white p-1 shadow-md border border-[#d57028]/20 group-hover:scale-105 transition overflow-hidden">
                <img src="{{ asset('assets/Logo/majapahit.png') }}" alt="Majapahit Influence Logo" class="size-full object-contain">
            </div>
            <span class="text-sm font-bold tracking-[0.18em] text-slate-950 font-heading">MAJAPAHIT <span class="text-amber-600">INFLUENCE</span></span>
        </a>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-emerald-50 text-3xl text-emerald-600">
                <i class="bi bi-check2"></i>
            </div>
            <h1 class="mt-5 text-2xl font-bold text-slate-950">Pendaftaran berhasil dikirim</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">Simpan nomor registrasi berikut untuk referensi pendaftaranmu.</p>
            <div class="my-6 rounded-xl bg-slate-50 px-4 py-4 text-xl font-bold tracking-widest text-indigo-700">
                {{ $registration }}
            </div>
            <p class="text-sm text-slate-500">Tim kami akan memproses pendaftaran dalam beberapa hari kerja.</p>
            <a href="{{ url('/') }}" class="mt-6 inline-flex rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
                Kembali ke beranda
            </a>
        </div>
    </div>
</main>
@endsection
