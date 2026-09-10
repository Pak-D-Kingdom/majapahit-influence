@extends('layouts.app')

@section('title', 'Gabung sebagai Creator — KERAJAAN')

@section('content')
<main class="min-h-screen bg-gradient-to-b from-blue-50/50 via-white to-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        
        {{-- Header Navigation & Brand Mark --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 mb-4 group transition-transform hover:scale-105">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3.png') }}" alt="KERAJAAN" class="h-12 w-auto object-contain">
                <span class="text-[11px] font-bold tracking-[0.22em] text-[#0c3685] uppercase">
                    KERAJAAN &bull; MAJAPAHIT INFLUENCE
                </span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-[#0c3685] tracking-tight">
                Gabung sebagai Creator / Influencer
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-lg mx-auto leading-relaxed">
                Jadilah bagian dari ekosistem KERAJAAN. Dapatkan akses ke campaign endorsement eksklusif dan bank konten katalog produk dengan komisi transparan.
            </p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-3xl border border-blue-100/80 shadow-xl shadow-blue-950/5 p-6 sm:p-10 space-y-8 backdrop-blur-sm">

            {{-- Error Validation Alert --}}
            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                    <strong class="font-bold flex items-center gap-1.5">
                        <i class="bi bi-exclamation-triangle-fill text-sm text-rose-600"></i> Mohon periksa kesalahan pengisian berikut:
                    </strong>
                    <ul class="list-disc list-inside space-y-0.5 pt-1 pl-1">
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
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0b64d4] text-xs font-bold flex items-center justify-center">1</span>
                        <h2 class="text-xs font-black uppercase tracking-wider text-[#0c3685]">Data Diri & Akun Login</h2>
                    </div>
                    
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Nama lengkap Anda" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Email Aktif <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@domain.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kata Sandi (Password) <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                            <span class="text-[11px] text-slate-400 mt-1 block">Digunakan untuk login setelah pendaftaran disetujui.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required minlength="6" placeholder="Ulangi kata sandi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nomor WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kota / Domisili
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Contoh: Surabaya, Jakarta" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                    </div>
                </div>

                {{-- Section 2: Platform Sosial Media Utama --}}
                <div class="border-t border-slate-100 pt-6 space-y-5">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0b64d4] text-xs font-bold flex items-center justify-center">2</span>
                        <h2 class="text-xs font-black uppercase tracking-wider text-[#0c3685]">Akun Media Sosial Utama</h2>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Platform Utama <span class="text-rose-500">*</span>
                            </label>
                            <select name="platform" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white cursor-pointer">
                                @foreach (['instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'twitter' => 'Twitter / X'] as $val => $lbl)
                                    <option value="{{ $val }}" @selected(old('platform') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Username / Handle <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}" required placeholder="@username_anda" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                URL Profil Medsos
                            </label>
                            <input type="url" name="profile_url" value="{{ old('profile_url') }}" placeholder="https://instagram.com/username" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Jumlah Followers <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" min="0" name="followers_count" value="{{ old('followers_count', 0) }}" required placeholder="Contoh: 15000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Niche & Portofolio --}}
                <div class="border-t border-slate-100 pt-6 space-y-5">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0b64d4] text-xs font-bold flex items-center justify-center">3</span>
                        <h2 class="text-xs font-black uppercase tracking-wider text-[#0c3685]">Niche & Informasi Tambahan</h2>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Pilih Kategori Niche Utama <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                            @foreach ($niches as $niche)
                                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer hover:text-[#0b64d4] transition select-none">
                                    <input type="checkbox" name="niches[]" value="{{ $niche->name }}" @checked(in_array($niche->name, old('niches', []))) class="rounded border-slate-300 text-[#0b64d4] focus:ring-[#0b64d4] cursor-pointer">
                                    <span>{{ $niche->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Ekspektasi Rate Card / Biaya Post (Opsional)
                            </label>
                            <textarea name="expected_rate" rows="3" placeholder="Contoh: Rp 500.000 / Reels, Rp 300.000 / Story" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">{{ old('expected_rate') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alasan Ingin Bergabung (Opsional)
                            </label>
                            <textarea name="join_reason" rows="3" placeholder="Ceritakan ketertarikan Anda berkolaborasi bersama kami..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">{{ old('join_reason') }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Portofolio / Media Kit <span class="font-normal text-slate-400">(Maks. 5 file, JPG/PNG/PDF)</span>
                        </label>
                        <input type="file" name="portfolio[]" multiple accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#0b64d4] hover:file:bg-blue-100 border border-dashed border-slate-200 rounded-2xl p-2 bg-slate-50/50 cursor-pointer">
                    </div>
                </div>

                {{-- Terms & Agreement --}}
                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <label class="flex items-start gap-2.5 text-xs text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" name="terms" value="1" required class="mt-0.5 rounded border-slate-300 text-[#0b64d4] focus:ring-[#0b64d4] cursor-pointer">
                        <span>Saya menyetujui syarat & ketentuan serta kebijakan privasi pendaftaran di ekosistem KERAJAAN.</span>
                    </label>

                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#0b64d4] to-[#1698f6] hover:from-[#0c3685] hover:to-[#0b64d4] text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-blue-600/20 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Kirim Pendaftaran Kreator</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

            </form>

        </div>

        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#0b64d4] transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

    </div>
</main>
@endsection
