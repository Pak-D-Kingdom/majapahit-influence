@extends('layouts.auth')

@section('title', 'Pendaftaran Berhasil - Majapahit Influence')

@section('left_content')
    <div class="space-y-6">
        <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
            Langkah Pertama <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">
                Selesai!
            </span>
        </h1>
        <p class="text-gray-300 text-lg leading-relaxed max-w-md">
            Terima kasih telah mendaftar. Tim kami akan segera meninjau profil dan portfolio Anda.
        </p>
    </div>
@endsection

@section('content')
<div class="w-full text-center py-10">
    
    <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-8 border-[6px] border-green-100 shadow-xl shadow-green-500/10">
        <i class="bi bi-check-lg text-4xl text-green-500 font-bold"></i>
    </div>

    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Pendaftaran Berhasil!</h2>
    
    <p class="text-gray-500 mt-4 text-lg max-w-md mx-auto">
        Data pendaftaran Anda telah kami terima dan sedang dalam antrean review.
    </p>

    <div class="mt-10 bg-gray-50 border border-gray-200 rounded-2xl p-8 max-w-sm mx-auto">
        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Nomor Pendaftaran</p>
        <div class="text-2xl font-bold text-gray-900 tracking-wider">
            {{ session('registration_number') ?? 'REG-XXXX-XXXX' }}
        </div>
        <p class="text-xs text-gray-400 mt-4">Simpan nomor ini untuk keperluan pengecekan status.</p>
    </div>

    <div class="mt-12 space-y-4">
        <p class="text-sm text-gray-600">
            Kami akan mengirimkan notifikasi via Email/WhatsApp jika profil Anda lolos kurasi.
        </p>
        <a href="/" class="inline-flex items-center gap-2 font-bold text-orange-600 hover:text-orange-700 hover:bg-orange-50 px-6 py-3 rounded-xl transition-colors">
            Kembali ke Beranda <i class="bi bi-arrow-right"></i>
        </a>
    </div>

</div>
@endsection
