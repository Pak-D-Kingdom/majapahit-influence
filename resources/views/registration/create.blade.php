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
                    KERAJAAN &bull; CREATOR COMMERCE
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

                {{-- Section 2: Platform Sosial Media --}}
                <div class="border-t border-slate-100 pt-6 space-y-5">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-50 text-[#0b64d4] text-xs font-bold flex items-center justify-center">2</span>
                        <div>
                            <h2 class="text-xs font-black uppercase tracking-wider text-[#0c3685]">Akun Media Sosial</h2>
                            <p class="text-[11px] text-slate-500 mt-0.5">Pilih platform media sosial aktif yang Anda kelola (dapat memilih lebih dari satu).</p>
                        </div>
                    </div>

                    @php
                        $availablePlatforms = [
                            'instagram' => ['name' => 'Instagram', 'icon' => 'bi-instagram', 'color' => 'text-pink-600', 'bg' => 'bg-pink-50', 'border' => 'border-pink-200', 'placeholder' => '@username_instagram', 'url_example' => 'https://instagram.com/username'],
                            'tiktok' => ['name' => 'TikTok', 'icon' => 'bi-tiktok', 'color' => 'text-slate-900', 'bg' => 'bg-slate-100', 'border' => 'border-slate-300', 'placeholder' => '@username_tiktok', 'url_example' => 'https://tiktok.com/@username'],
                            'youtube' => ['name' => 'YouTube', 'icon' => 'bi-youtube', 'color' => 'text-red-600', 'bg' => 'bg-red-50', 'border' => 'border-red-200', 'placeholder' => '@channel_youtube', 'url_example' => 'https://youtube.com/@channel'],
                            'facebook' => ['name' => 'Facebook', 'icon' => 'bi-facebook', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'placeholder' => 'Nama Halaman / Akun Facebook', 'url_example' => 'https://facebook.com/username'],
                            'threads' => ['name' => 'Threads', 'icon' => 'bi-threads', 'color' => 'text-neutral-900', 'bg' => 'bg-neutral-100', 'border' => 'border-neutral-300', 'placeholder' => '@username_threads', 'url_example' => 'https://threads.net/@username'],
                            'twitter' => ['name' => 'Twitter / X', 'icon' => 'bi-twitter-x', 'color' => 'text-slate-800', 'bg' => 'bg-slate-100', 'border' => 'border-slate-300', 'placeholder' => '@username_x', 'url_example' => 'https://x.com/username'],
                        ];

                        $oldPlatforms = old('platforms');
                        if ($oldPlatforms === null) {
                            $oldPlatforms = old('social_media') ? array_keys(old('social_media')) : ['instagram'];
                        }
                    @endphp

                    {{-- Platform Selector Checkboxes --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Pilih Platform <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                            @foreach ($availablePlatforms as $pKey => $pMeta)
                                @php $isChecked = in_array($pKey, (array) $oldPlatforms); @endphp
                                <label class="platform-choice-card relative flex items-center gap-2 p-2.5 sm:p-3 rounded-2xl border transition-all cursor-pointer select-none {{ $isChecked ? 'border-[#0b64d4] bg-blue-50/40 shadow-xs' : 'border-slate-200 bg-slate-50/50 hover:bg-white hover:border-slate-300' }}">
                                    <input type="checkbox" name="platforms[]" value="{{ $pKey }}" @checked($isChecked) class="platform-checkbox rounded border-slate-300 text-[#0b64d4] focus:ring-[#0b64d4] cursor-pointer size-4" onchange="togglePlatformSection('{{ $pKey }}', this.checked)">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <i class="bi {{ $pMeta['icon'] }} {{ $pMeta['color'] }} text-base"></i>
                                        <span class="text-xs font-bold text-[#071d49] truncate">{{ $pMeta['name'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Platform Detail Input Cards --}}
                    <div class="space-y-4 pt-1">
                        @foreach ($availablePlatforms as $pKey => $pMeta)
                            @php
                                $isActive = in_array($pKey, (array) $oldPlatforms);
                                $oldUser = old("social_media.{$pKey}.username", $pKey === 'instagram' ? old('username') : '');
                                $oldFollowers = old("social_media.{$pKey}.followers_count", $pKey === 'instagram' ? old('followers_count') : '');
                                $oldUrl = old("social_media.{$pKey}.profile_url", $pKey === 'instagram' ? old('profile_url') : '');
                            @endphp
                            <div id="platform-card-{{ $pKey }}" class="platform-card rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-xs transition-all {{ $isActive ? '' : 'hidden' }}">
                                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <span class="flex size-7 items-center justify-center rounded-lg {{ $pMeta['bg'] }} {{ $pMeta['color'] }}">
                                            <i class="bi {{ $pMeta['icon'] }} text-sm"></i>
                                        </span>
                                        <h3 class="text-xs font-extrabold text-[#071d49] font-heading">{{ $pMeta['name'] }}</h3>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-[#0b64d4]">
                                        Aktif
                                    </span>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                            Username / Handle <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="social_media[{{ $pKey }}][username]" value="{{ $oldUser }}" placeholder="{{ $pMeta['placeholder'] }}" class="platform-input w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                            Jumlah Followers <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="number" min="0" name="social_media[{{ $pKey }}][followers_count]" value="{{ $oldFollowers }}" placeholder="Contoh: 15000" class="platform-input w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                            URL Profil Medsos <span class="text-slate-400 font-normal">(Opsional)</span>
                                        </label>
                                        <input type="url" name="social_media[{{ $pKey }}][profile_url]" value="{{ $oldUrl }}" placeholder="{{ $pMeta['url_example'] }}" class="platform-input w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0b64d4] focus:border-transparent transition bg-slate-50/50 hover:bg-white focus:bg-white">
                                    </div>
                                </div>
                            </div>
                        @endforeach
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

@push('scripts')
<script>
    function togglePlatformSection(platformKey, isChecked) {
        const card = document.getElementById('platform-card-' + platformKey);
        const checkbox = document.querySelector(`input[name="platforms[]"][value="${platformKey}"]`);
        const labelCard = checkbox?.closest('.platform-choice-card');

        if (card) {
            if (isChecked) {
                card.classList.remove('hidden');
                const firstInput = card.querySelector('input[type="text"]');
                firstInput?.focus();
            } else {
                card.classList.add('hidden');
            }
        }

        if (labelCard) {
            if (isChecked) {
                labelCard.classList.add('border-[#0b64d4]', 'bg-blue-50/40', 'shadow-xs');
                labelCard.classList.remove('border-slate-200', 'bg-slate-50/50');
            } else {
                labelCard.classList.remove('border-[#0b64d4]', 'bg-blue-50/40', 'shadow-xs');
                labelCard.classList.add('border-slate-200', 'bg-slate-50/50');
            }
        }
    }
</script>
@endpush
@endsection
