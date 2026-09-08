@extends('layouts.app')
@section('title', 'Daftar sebagai KOL')
@section('content')
<main class="min-h-screen bg-gray-50/70 py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        
        {{-- Back Link --}}
        <div class="mb-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-amber-700 hover:text-amber-800 transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-amber-500/5 p-6 sm:p-10 space-y-8">
            
            {{-- Header --}}
            <div class="border-b border-gray-100 pb-6 space-y-2">
                <span class="px-3 py-1 rounded-lg bg-amber-50 text-amber-800 text-xs font-bold tracking-wider uppercase">
                    Pendaftaran Kreator
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                    Gabung sebagai KOL / Influencer
                </h1>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Jadilah bagian dari ekosistem Majapahit Influence. Dapatkan akses ke campaign endorsement eksklusif dan bank konten katalog produk dengan komisi transparan.
                </p>
            </div>

            {{-- Error Validation Alert --}}
            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                    <strong class="font-bold flex items-center gap-1.5">
                        <i class="bi bi-exclamation-triangle-fill text-sm"></i> Mohon periksa kesalahan pengisian berikut:
                    </strong>
                    <ul class="list-disc list-inside space-y-0.5 pt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('registration.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Section 1: Data Diri & Akun --}}
                <div class="space-y-5">
                    <h2 class="text-sm font-black uppercase tracking-wider text-gray-400">1. Data Diri & Akun Login</h2>
                    
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Nama lengkap Anda" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Email Aktif <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@domain.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Kata Sandi (Password) <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                            <span class="text-[11px] text-gray-400 mt-1 block">Kata sandi ini digunakan untuk login setelah akun disetujui.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required minlength="6" placeholder="Ulangi kata sandi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Nomor WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Kota / Domisili
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Contoh: Surabaya, Jakarta" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>
                    </div>
                </div>

                {{-- Section 2: Platform Sosial Media Utama --}}
                <div class="border-t border-gray-100 pt-6 space-y-5">
                    <h2 class="text-sm font-black uppercase tracking-wider text-gray-400">2. Akun Media Sosial Utama</h2>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Platform <span class="text-red-500">*</span>
                            </label>
                            <select name="platform" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                                @foreach (['instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'twitter' => 'Twitter / X'] as $val => $lbl)
                                    <option value="{{ $val }}" @selected(old('platform') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Username / Handle <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}" required placeholder="@username_anda" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                URL Profil Medsos
                            </label>
                            <input type="url" name="profile_url" value="{{ old('profile_url') }}" placeholder="https://instagram.com/username" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Jumlah Followers <span class="text-red-500">*</span>
                            </label>
                            <input type="number" min="0" name="followers_count" value="{{ old('followers_count', 0) }}" required placeholder="Contoh: 15000" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Niche & Portofolio --}}
                <div class="border-t border-gray-100 pt-6 space-y-5">
                    <h2 class="text-sm font-black uppercase tracking-wider text-gray-400">3. Niche & Informasi Tambahan</h2>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">
                            Pilih Kategori Niche Utama <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            @foreach ($niches as $niche)
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer hover:text-amber-700 transition">
                                    <input type="checkbox" name="niches[]" value="{{ $niche->name }}" @checked(in_array($niche->name, old('niches', []))) class="rounded text-amber-600 focus:ring-amber-500">
                                    <span>{{ $niche->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Ekspektasi Rate Card / Biaya Post (Opsional)
                            </label>
                            <textarea name="expected_rate" rows="3" placeholder="Contoh: Rp 500.000 / Reels, Rp 300.000 / Story" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">{{ old('expected_rate') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                Alasan Ingin Bergabung (Opsional)
                            </label>
                            <textarea name="join_reason" rows="3" placeholder="Ceritakan ketertarikan Anda berkolaborasi..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">{{ old('join_reason') }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Portofolio / Contoh Konten / Media Kit <span class="font-normal text-gray-400">(Maks. 5 file, format JPG/PNG/PDF)</span>
                        </label>
                        <input type="file" name="portfolio[]" multiple accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-dashed border-gray-200 rounded-2xl p-2 bg-gray-50/50">
                    </div>
                </div>

                {{-- Terms & Agreement --}}
                <div class="border-t border-gray-100 pt-6 space-y-4">
                    <label class="flex items-start gap-2.5 text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" name="terms" value="1" required class="mt-0.5 rounded text-amber-600 focus:ring-amber-500">
                        <span>Saya menyetujui syarat & ketentuan serta kebijakan privasi pendaftaran di platform Majapahit Influence.</span>
                    </label>

                    <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-extrabold text-sm shadow-lg shadow-amber-600/20 transition-all flex items-center justify-center gap-2">
                        <span>Kirim Pendaftaran Kreator</span>
                        <i class="bi bi-arrow-right text-base"></i>
                    </button>
                </div>

            </form>

        </div>

    </div>
</main>
@endsection
