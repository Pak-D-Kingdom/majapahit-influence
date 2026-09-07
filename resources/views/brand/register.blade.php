@extends('layouts.app')

@section('title', 'Pendaftaran Brand & Kemitraan Maklon — Majapahit Influence')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-between py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto w-full">
        
        {{-- Header Navigation & Brand Mark --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 mb-4 group">
                <div class="w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center text-white font-black text-lg shadow-md group-hover:scale-105 transition-transform">
                    MI
                </div>
                <div class="text-left leading-tight">
                    <span class="block text-[11px] font-bold tracking-widest text-amber-700">MAJAPAHIT</span>
                    <strong class="block text-base font-extrabold text-gray-900 tracking-tight">INFLUENCE</strong>
                </div>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Form Pendaftaran Kemitraan Brand</h1>
            <p class="text-sm text-gray-600 mt-2 max-w-lg mx-auto">
                Promosikan produk Anda lewat ribuan kreator bertarget atau buat produk custom impian Anda dengan layanan Maklon Pak De Group.
            </p>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 sm:p-10">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <div class="font-bold mb-1 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i> Mohon lengkapi data dengan benar:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('brand.register.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Section 1: Kebutuhan Layanan --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-700">
                        1. Pilihan Kebutuhan Layanan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <label class="relative flex flex-col p-4 border rounded-2xl cursor-pointer hover:border-amber-500 transition-all {{ old('service_need', request('need')) === 'endorsement' ? 'border-amber-600 bg-amber-50/50' : 'border-gray-200' }}">
                            <input type="radio" name="service_need" value="endorsement" class="sr-only" {{ old('service_need', request('need')) === 'endorsement' ? 'checked' : '' }} required>
                            <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                <i class="bi bi-megaphone-fill text-amber-600"></i> Promosi Produk
                            </span>
                            <span class="text-[11px] text-gray-500 mt-1">Produk sudah siap & ingin dipromosikan KOL.</span>
                        </label>

                        <label class="relative flex flex-col p-4 border rounded-2xl cursor-pointer hover:border-amber-500 transition-all {{ old('service_need', request('need')) === 'maklon' ? 'border-amber-600 bg-amber-50/50' : 'border-gray-200' }}">
                            <input type="radio" name="service_need" value="maklon" class="sr-only" {{ old('service_need', request('need')) === 'maklon' ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                <i class="bi bi-gear-wide-connected text-amber-600"></i> Layanan Maklon
                            </span>
                            <span class="text-[11px] text-gray-500 mt-1">Ingin formulasi & produksi produk baru.</span>
                        </label>

                        <label class="relative flex flex-col p-4 border rounded-2xl cursor-pointer hover:border-amber-500 transition-all {{ old('service_need', request('need', 'both')) === 'both' ? 'border-amber-600 bg-amber-50/50' : 'border-gray-200' }}">
                            <input type="radio" name="service_need" value="both" class="sr-only" {{ old('service_need', request('need', 'both')) === 'both' ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                <i class="bi bi-stars text-amber-600"></i> Keduanya (All-in-One)
                            </span>
                            <span class="text-[11px] text-gray-500 mt-1">Maklon produk + langsung dipasarkan KOL.</span>
                        </label>
                    </div>
                </div>

                {{-- Section 2: Data Brand / Perusahaan --}}
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-700">2. Profil Brand & Usaha</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="brand_name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Brand <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name') }}" placeholder="Contoh: GlowUp Naturals" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>

                        <div>
                            <label for="company_name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Perusahaan / Badan Usaha (Opsional)
                            </label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT Glow Mandiri Sejahtera" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="industry_category" class="block text-xs font-bold text-gray-700 mb-1">
                            Kategori Industri Produk <span class="text-red-500">*</span>
                        </label>
                        <select id="industry_category" name="industry_category" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                            <option value="">-- Pilih Kategori Industri --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->name }}" {{ old('industry_category') == $cat->name ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                            <option value="Lainnya" {{ old('industry_category') == 'Lainnya' ? 'selected' : '' }}>Lainnya / Belum Ditentukan</option>
                        </select>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="social_media" class="block text-xs font-bold text-gray-700 mb-1">
                                Akun Instagram / TikTok Brand (Opsional)
                            </label>
                            <input type="text" id="social_media" name="social_media" value="{{ old('social_media') }}" placeholder="@brand.official" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>

                        <div>
                            <label for="website" class="block text-xs font-bold text-gray-700 mb-1">
                                Website / Link Toko Online (Opsional)
                            </label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Kontak PIC (Penanggung Jawab) --}}
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-700">3. Kontak Penanggung Jawab (PIC)</h3>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama PIC <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" placeholder="Contoh: Rina Novitasari" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>

                        <div>
                            <label for="pic_title" class="block text-xs font-bold text-gray-700 mb-1">
                                Jabatan PIC
                            </label>
                            <input type="text" id="pic_title" name="pic_title" value="{{ old('pic_title') }}" placeholder="Contoh: Owner / Marketing Manager" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_email" class="block text-xs font-bold text-gray-700 mb-1">
                                Email Bisnis <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="pic_email" name="pic_email" value="{{ old('pic_email') }}" placeholder="rina@brand.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>

                        <div>
                            <label for="pic_phone" class="block text-xs font-bold text-gray-700 mb-1">
                                No. WhatsApp PIC <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="pic_phone" name="pic_phone" value="{{ old('pic_phone') }}" placeholder="081234567890" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Catatan & Rencana --}}
                <div class="pt-4 border-t border-gray-100 space-y-2">
                    <label for="notes" class="block text-xs font-bold text-gray-700">
                        Deskripsi Singkat Rencana Promosi / Kebutuhan Produk (Opsional)
                    </label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Ceritakan target pasar Anda, rencana peluncuran produk, atau estimasi jumlah kuantitas maklon yang diinginkan..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm shadow-xl shadow-amber-600/20 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-send-check-fill"></i> Kirim Pengajuan Kemitraan Brand
                    </button>
                    <p class="text-center text-[11px] text-gray-500 mt-3">
                        Dengan mengirimkan form ini, data Anda aman dan tim kemitraan kami akan menghubungi Anda via WhatsApp dalam 1x24 jam kerja.
                    </p>
                </div>

            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-xs font-bold text-gray-600 hover:text-amber-600">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda Utama
            </a>
        </div>
    </div>
</div>
@endsection
