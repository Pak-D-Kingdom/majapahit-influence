@extends('kol.layouts.app')

@section('title', 'Edit Profil & Rate Card')
@section('page-title', 'Edit Profil & Rate Card')

@section('content')
    {{-- Top Action / Breadcrumbs Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('kol.profile.show') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b64d4] hover:text-[#0c3685] transition font-heading mb-2">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Profil Saya</span>
            </a>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#071d49] font-heading">
                Edit Profil & Rate Card
            </h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi identitas, foto profil, akun media sosial, tarif konten, dan rekening bank Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('kol.profile.show') }}" class="btn-kerajaan-secondary">
                <i class="bi bi-x-lg"></i>
                <span>Batal</span>
            </a>
            <button type="button" onclick="submitProfileForm()" class="btn-kerajaan-primary shadow-lg shadow-[#0b64d4]/20">
                <i class="bi bi-check2-circle text-lg"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/80 p-5 text-sm text-rose-800 shadow-xs">
            <div class="flex items-center gap-2 font-bold font-heading">
                <i class="bi bi-exclamation-octagon-fill text-rose-600"></i>
                <span>Terdapat kesalahan pada formulir:</span>
            </div>
            <ul class="mt-2 list-inside list-disc text-xs space-y-1 text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form id="editProfileForm" method="POST" action="{{ route('kol.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- SECTION 1: DATA PRIBADI & FOTO --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Bagian 1</span>
                <h3 class="text-xl font-extrabold text-[#071d49] font-heading">Data Pribadi & Foto Profil</h3>
                <p class="mt-0.5 text-xs text-slate-500">Informasi identitas publik dan foto yang tampil pada agensi serta brand mitra.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                {{-- Foto Profil Preview & Upload --}}
                <div class="flex flex-col items-center text-center p-4 rounded-2xl bg-slate-50/70 border border-slate-200">
                    <div class="relative size-32 sm:size-36 overflow-hidden rounded-2xl border-2 border-white bg-gradient-to-br from-[#0b64d4] to-[#1698f6] shadow-md shadow-[#0b64d4]/20">
                        <img id="photoPreview"
                             src="{{ $profile->photo_path ? asset('storage/' . $profile->photo_path) : '' }}"
                             alt="{{ $profile->nickname ?: $profile->user->name }}"
                             class="size-full object-cover {{ $profile->photo_path ? '' : 'hidden' }}">

                        <div id="photoPlaceholder" class="flex size-full items-center justify-center font-heading text-4xl font-extrabold text-white {{ $profile->photo_path ? 'hidden' : '' }}">
                            {{ str($profile->nickname ?: $profile->user->name)->substr(0, 2)->upper() }}
                        </div>
                    </div>

                    <input type="file"
                           name="photo"
                           id="photoInput"
                           accept="image/png,image/jpeg,image/jpg"
                           class="hidden"
                           onchange="handlePhotoSelect(this)">

                    <button type="button"
                            onclick="document.getElementById('photoInput').click()"
                            class="mt-4 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-[#0b64d4] shadow-xs hover:bg-slate-50 hover:border-[#0b64d4] transition font-heading">
                        <i class="bi bi-camera"></i>
                        <span>Ganti Foto</span>
                    </button>
                    <p class="mt-2 text-[11px] text-slate-400">Format JPG, JPEG, PNG. Maks 2MB.</p>
                </div>

                {{-- Fields Data Pribadi --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    {{-- Nama Lengkap (Read-only) --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                            Nama Lengkap Akun
                        </label>
                        <input type="text"
                               value="{{ $profile->user->name }}"
                               disabled
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500 cursor-not-allowed">
                        <p class="mt-1 text-[11px] text-slate-400">Nama terdaftar di akun pengguna.</p>
                    </div>

                    {{-- Nama Panggilan --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                            Nama Panggilan / Alias <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="nickname"
                               value="{{ old('nickname', $profile->nickname) }}"
                               required
                               placeholder="Nama panggilan Anda"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                    </div>

                    {{-- Kota Domisili --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                            Kota Domisili
                        </label>
                        <input type="text"
                               name="city"
                               value="{{ old('city', $profile->city) }}"
                               placeholder="Contoh: Surabaya"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                    </div>

                    {{-- Provinsi --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                            Provinsi
                        </label>
                        <input type="text"
                               name="province"
                               value="{{ old('province', $profile->province) }}"
                               placeholder="Contoh: Jawa Timur"
                               class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                    </div>

                    {{-- Bio Singkat --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                            Bio Singkat
                        </label>
                        <textarea name="bio"
                                  rows="3"
                                  maxlength="1000"
                                  placeholder="Ceritakan persona konten, spesialisasi, atau gaya komunikasi Anda secara singkat..."
                                  class="mt-2 w-full rounded-xl border border-slate-200 bg-white p-3.5 text-sm text-slate-800 placeholder:text-slate-400 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">{{ old('bio', $profile->bio) }}</textarea>
                    </div>

                    {{-- Info Tier & Niche (Read-only Badge Bar) --}}
                    <div class="sm:col-span-2 rounded-2xl bg-slate-50 p-4 border border-slate-200 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex size-10 items-center justify-center rounded-xl bg-[#0b64d4]/10 text-[#0b64d4]">
                                <i class="bi bi-award text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#071d49] font-heading">Status Tier: <span class="text-[#0b64d4]">{{ $profile->tier ? 'Tier ' . $profile->tier->name : 'Reguler' }}</span></p>
                                <p class="text-[11px] text-slate-500">Bagi hasil komisi standar: <strong class="text-[#071d49]">{{ $profile->effective_commission_pct }}%</strong> (Dikelola oleh Admin)</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-[11px] font-bold text-slate-400 uppercase mr-1">Niche:</span>
                            @forelse ($profile->niches as $niche)
                                <span class="rounded-full bg-white px-2.5 py-0.5 text-xs font-semibold text-slate-700 border border-slate-200 shadow-2xs">
                                    {{ $niche->name }}
                                </span>
                            @empty
                                <span class="text-xs italic text-slate-400">Belum ada kategori niche</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: AKUN SOSIAL MEDIA (REPEATER) --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center mb-6 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Bagian 2</span>
                    <h3 class="text-xl font-extrabold text-[#071d49] font-heading">Akun Media Sosial</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Daftar kanal media sosial Anda. Minimal 1 platform aktif wajib diisi.</p>
                </div>
                <button type="button"
                        onclick="addSocialMediaRow()"
                        class="inline-flex items-center gap-1.5 self-start rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-[#0b64d4] hover:bg-[#0b64d4] hover:text-white transition font-heading">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tambah Platform</span>
                </button>
            </div>

            @php
                $existingSocials = old('social_media', $profile->socialMedia->toArray());
                if (empty($existingSocials)) {
                    $existingSocials = [['id' => null, 'platform' => 'instagram', 'username' => '', 'profile_url' => '', 'followers_count' => 0, 'engagement_rate' => 0.0]];
                }
            @endphp

            <div id="socialMediaContainer" class="space-y-4">
                @foreach ($existingSocials as $idx => $social)
                    <div class="social-row relative rounded-2xl border border-slate-200 bg-slate-50/50 p-4 sm:p-5 transition hover:border-[#0b64d4]/30 hover:bg-white" data-index="{{ $idx }}">
                        <input type="hidden" name="social_media[{{ $idx }}][id]" value="{{ $social['id'] ?? '' }}">

                        <div class="grid gap-4 sm:grid-cols-12 items-end">
                            {{-- Platform --}}
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                                    Platform <span class="text-rose-500">*</span>
                                </label>
                                <select name="social_media[{{ $idx }}][platform]" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                                    @foreach (['instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'x' => 'X (Twitter)', 'facebook' => 'Facebook', 'threads' => 'Threads', 'linkedin' => 'LinkedIn'] as $key => $label)
                                        <option value="{{ $key }}" @selected(($social['platform'] ?? '') === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Username --}}
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                                    Username / Handle <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative mt-2">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-semibold text-slate-400">@</span>
                                    <input type="text"
                                           name="social_media[{{ $idx }}][username]"
                                           value="{{ $social['username'] ?? '' }}"
                                           required
                                           placeholder="username"
                                           class="w-full rounded-xl border border-slate-200 bg-white pl-8 pr-3 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                                </div>
                            </div>

                            {{-- Profile URL --}}
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                                    URL Profil
                                </label>
                                <input type="url"
                                       name="social_media[{{ $idx }}][profile_url]"
                                       value="{{ $social['profile_url'] ?? '' }}"
                                       placeholder="https://..."
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                            </div>

                            {{-- Followers Count --}}
                            <div class="sm:col-span-1">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading" title="Jumlah Followers">
                                    Followers <span class="text-rose-500">*</span>
                                </label>
                                <input type="number"
                                       name="social_media[{{ $idx }}][followers_count]"
                                       value="{{ $social['followers_count'] ?? 0 }}"
                                       min="0"
                                       required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                            </div>

                            {{-- Engagement Rate --}}
                            <div class="sm:col-span-1">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading" title="Engagement Rate (%)">
                                    ER (%) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number"
                                       step="0.01"
                                       name="social_media[{{ $idx }}][engagement_rate]"
                                       value="{{ $social['engagement_rate'] ?? 0 }}"
                                       min="0"
                                       max="100"
                                       required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                            </div>

                            {{-- Action Delete Button --}}
                            <div class="sm:col-span-1 flex justify-end">
                                <button type="button"
                                        onclick="removeSocialMediaRow(this)"
                                        class="social-remove-btn inline-flex size-9 items-center justify-center rounded-xl border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 transition"
                                        title="Hapus platform ini">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SECTION 3: RATE CARD (REPEATER) --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center mb-6 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Bagian 3</span>
                    <h3 class="text-xl font-extrabold text-[#071d49] font-heading">Rate Card (Tarif Konten)</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Tarif indikatif per platform dan tipe konten untuk penawaran endorsement.</p>
                </div>
                <button type="button"
                        onclick="addRateCardRow()"
                        class="inline-flex items-center gap-1.5 self-start rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-[#0b64d4] hover:bg-[#0b64d4] hover:text-white transition font-heading">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tambah Tarif</span>
                </button>
            </div>

            @php
                $existingRates = old('rate_cards', $profile->rateCards->toArray());
            @endphp

            <div id="rateCardsContainer" class="space-y-4">
                @forelse ($existingRates as $idx => $rateCard)
                    <div class="rate-row relative rounded-2xl border border-slate-200 bg-slate-50/50 p-4 sm:p-5 transition hover:border-[#0b64d4]/30 hover:bg-white" data-index="{{ $idx }}">
                        <input type="hidden" name="rate_cards[{{ $idx }}][id]" value="{{ $rateCard['id'] ?? '' }}">

                        <div class="grid gap-4 sm:grid-cols-12 items-end">
                            {{-- Platform --}}
                            <div class="sm:col-span-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                                    Platform <span class="text-rose-500">*</span>
                                </label>
                                <select name="rate_cards[{{ $idx }}][platform]" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                                    @foreach (['instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'x' => 'X (Twitter)', 'facebook' => 'Facebook', 'threads' => 'Threads'] as $key => $label)
                                        <option value="{{ $key }}" @selected(($rateCard['platform'] ?? '') === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tipe Konten --}}
                            <div class="sm:col-span-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                                    Tipe Konten <span class="text-rose-500">*</span>
                                </label>
                                <select name="rate_cards[{{ $idx }}][content_type]" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                                    @foreach (['reels' => 'Reels / Short Video', 'video' => 'Dedicated Video', 'feed_post' => 'Feed Post / Foto', 'story' => 'Story', 'tweet' => 'Thread / Post X', 'live_stream' => 'Live Streaming'] as $typeKey => $typeLabel)
                                        <option value="{{ $typeKey }}" @selected(($rateCard['content_type'] ?? '') === $typeKey)>{{ $typeLabel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tarif (Rp) --}}
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                                    Tarif Konten (Rp) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative mt-2">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                                    <input type="number"
                                           name="rate_cards[{{ $idx }}][rate]"
                                           value="{{ (int) ($rateCard['rate'] ?? 0) }}"
                                           min="0"
                                           step="1000"
                                           required
                                           placeholder="1500000"
                                           class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 py-2 text-sm font-semibold text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                                </div>
                            </div>

                            {{-- Action Delete Button --}}
                            <div class="sm:col-span-1 flex justify-end">
                                <button type="button"
                                        onclick="removeRateCardRow(this)"
                                        class="inline-flex size-9 items-center justify-center rounded-xl border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 transition"
                                        title="Hapus tarif ini">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div id="rateCardsEmptyState" class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 p-8 text-center">
                        <i class="bi bi-tag text-3xl text-slate-400"></i>
                        <p class="mt-2 text-sm font-bold text-[#071d49] font-heading">Belum ada tarif rate card</p>
                        <p class="mt-1 text-xs text-slate-500">Klik tombol di atas untuk menambahkan tarif endorsement Anda.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 flex items-start gap-2 rounded-xl bg-slate-50 p-3 text-xs text-slate-600 border border-slate-200">
                <i class="bi bi-info-circle-fill text-[#0b64d4] shrink-0 mt-0.5"></i>
                <span>Setiap perubahan rate card akan otomatis tercatat pada riwayat audit sistem. Tarif bersifat indikatif dan dapat dinegosiasikan per campaign.</span>
            </div>
        </div>

        {{-- SECTION 4: INFORMASI BANK & PAJAK --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Bagian 4</span>
                <h3 class="text-xl font-extrabold text-[#071d49] font-heading">Informasi Rekening Bank & NPWP</h3>
                <p class="mt-0.5 text-xs text-slate-500">Digunakan secara eksklusif untuk penyaluran pencairan komisi hasil endorsement Anda.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                {{-- Nama Bank --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Nama Bank
                    </label>
                    <input type="text"
                           name="bank_name"
                           value="{{ old('bank_name', $profile->bank_name) }}"
                           placeholder="Contoh: BCA, Mandiri, BNI, BRI, dll."
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                {{-- Nomor Rekening --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Nomor Rekening
                    </label>
                    <input type="text"
                           name="bank_account_number"
                           value="{{ old('bank_account_number', $profile->bank_account_number) }}"
                           placeholder="Contoh: 1234567890"
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                {{-- Atas Nama Rekening --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Atas Nama Rekening
                    </label>
                    <input type="text"
                           name="bank_account_name"
                           value="{{ old('bank_account_name', $profile->bank_account_name) }}"
                           placeholder="Sesuai buku tabungan / e-banking"
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                {{-- NPWP --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Nomor Pokok Wajib Pajak (NPWP)
                    </label>
                    <input type="text"
                           name="npwp"
                           value="{{ old('npwp', $profile->npwp) }}"
                           placeholder="Contoh: 01.234.567.8-901.000"
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>
            </div>

            <div class="mt-6 flex items-start gap-3 rounded-2xl bg-amber-50/80 p-4 border border-amber-200 text-amber-900 text-xs">
                <i class="bi bi-shield-exclamation text-base text-amber-600 shrink-0 mt-0.5"></i>
                <div>
                    <strong class="font-bold font-heading">Penting untuk Kelancaran Pencairan:</strong>
                    <p class="mt-0.5 text-amber-800">Pastikan nama pemilik rekening perbankan sama dengan identitas resmi Anda agar proses pencairan saldo komisi disetujui oleh tim finance.</p>
                </div>
            </div>
        </div>

        {{-- BOTTOM ACTION BUTTONS --}}
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-200">
            <a href="{{ route('kol.profile.show') }}" class="btn-kerajaan-secondary">
                <span>Batal</span>
            </a>
            <button type="button" onclick="submitProfileForm()" class="btn-kerajaan-primary shadow-lg shadow-[#0b64d4]/20">
                <i class="bi bi-check2-circle text-lg"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>

    {{-- DYNAMIC ROW TEMPLATES (FOR JAVASCRIPT) --}}
    <template id="socialMediaTemplate">
        <div class="social-row relative rounded-2xl border border-slate-200 bg-slate-50/50 p-4 sm:p-5 transition hover:border-[#0b64d4]/30 hover:bg-white" data-index="__INDEX__">
            <input type="hidden" name="social_media[__INDEX__][id]" value="">

            <div class="grid gap-4 sm:grid-cols-12 items-end">
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Platform <span class="text-rose-500">*</span>
                    </label>
                    <select name="social_media[__INDEX__][platform]" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                        <option value="youtube">YouTube</option>
                        <option value="x">X (Twitter)</option>
                        <option value="facebook">Facebook</option>
                        <option value="threads">Threads</option>
                        <option value="linkedin">LinkedIn</option>
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Username / Handle <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-semibold text-slate-400">@</span>
                        <input type="text"
                               name="social_media[__INDEX__][username]"
                               value=""
                               required
                               placeholder="username"
                               class="w-full rounded-xl border border-slate-200 bg-white pl-8 pr-3 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        URL Profil
                    </label>
                    <input type="url"
                           name="social_media[__INDEX__][profile_url]"
                           value=""
                           placeholder="https://..."
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading" title="Jumlah Followers">
                        Followers <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="social_media[__INDEX__][followers_count]"
                           value="0"
                           min="0"
                           required
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading" title="Engagement Rate (%)">
                        ER (%) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           step="0.01"
                           name="social_media[__INDEX__][engagement_rate]"
                           value="0"
                           min="0"
                           max="100"
                           required
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                <div class="sm:col-span-1 flex justify-end">
                    <button type="button"
                            onclick="removeSocialMediaRow(this)"
                            class="social-remove-btn inline-flex size-9 items-center justify-center rounded-xl border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 transition"
                            title="Hapus platform ini">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <template id="rateCardTemplate">
        <div class="rate-row relative rounded-2xl border border-slate-200 bg-slate-50/50 p-4 sm:p-5 transition hover:border-[#0b64d4]/30 hover:bg-white" data-index="__INDEX__">
            <input type="hidden" name="rate_cards[__INDEX__][id]" value="">

            <div class="grid gap-4 sm:grid-cols-12 items-end">
                <div class="sm:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Platform <span class="text-rose-500">*</span>
                    </label>
                    <select name="rate_cards[__INDEX__][platform]" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                        <option value="instagram">Instagram</option>
                        <option value="tiktok">TikTok</option>
                        <option value="youtube">YouTube</option>
                        <option value="x">X (Twitter)</option>
                        <option value="facebook">Facebook</option>
                        <option value="threads">Threads</option>
                    </select>
                </div>

                <div class="sm:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Tipe Konten <span class="text-rose-500">*</span>
                    </label>
                    <select name="rate_cards[__INDEX__][content_type]" required class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                        <option value="reels">Reels / Short Video</option>
                        <option value="video">Dedicated Video</option>
                        <option value="feed_post">Feed Post / Foto</option>
                        <option value="story">Story</option>
                        <option value="tweet">Thread / Post X</option>
                        <option value="live_stream">Live Streaming</option>
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Tarif Konten (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number"
                               name="rate_cards[__INDEX__][rate]"
                               value="1000000"
                               min="0"
                               step="1000"
                               required
                               placeholder="1000000"
                               class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 py-2 text-sm font-semibold text-slate-800 focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                    </div>
                </div>

                <div class="sm:col-span-1 flex justify-end">
                    <button type="button"
                            onclick="removeRateCardRow(this)"
                            class="inline-flex size-9 items-center justify-center rounded-xl border border-rose-200 bg-white text-rose-600 hover:bg-rose-50 transition"
                            title="Hapus tarif ini">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
<script>
    let socialMediaCounter = {{ count($existingSocials) }};
    let rateCardsCounter = {{ count($existingRates) }};

    function handlePhotoSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto profil maksimal adalah 2MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                const placeholder = document.getElementById('photoPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function addSocialMediaRow() {
        const template = document.getElementById('socialMediaTemplate').innerHTML;
        const newHtml = template.replace(/__INDEX__/g, socialMediaCounter);
        document.getElementById('socialMediaContainer').insertAdjacentHTML('beforeend', newHtml);
        socialMediaCounter++;
        updateSocialMediaRemoveButtons();
    }

    function removeSocialMediaRow(button) {
        const rows = document.querySelectorAll('.social-row');
        if (rows.length <= 1) {
            alert('Minimal 1 akun sosial media harus tersedia.');
            return;
        }
        const row = button.closest('.social-row');
        row.remove();
        updateSocialMediaRemoveButtons();
    }

    function updateSocialMediaRemoveButtons() {
        const rows = document.querySelectorAll('.social-row');
        const buttons = document.querySelectorAll('.social-remove-btn');
        if (rows.length <= 1) {
            buttons.forEach(btn => btn.classList.add('opacity-40', 'pointer-events-none'));
        } else {
            buttons.forEach(btn => btn.classList.remove('opacity-40', 'pointer-events-none'));
        }
    }

    function addRateCardRow() {
        const emptyState = document.getElementById('rateCardsEmptyState');
        if (emptyState) {
            emptyState.remove();
        }

        const template = document.getElementById('rateCardTemplate').innerHTML;
        const newHtml = template.replace(/__INDEX__/g, rateCardsCounter);
        document.getElementById('rateCardsContainer').insertAdjacentHTML('beforeend', newHtml);
        rateCardsCounter++;
    }

    function removeRateCardRow(button) {
        const row = button.closest('.rate-row');
        row.remove();

        const remainingRows = document.querySelectorAll('.rate-row');
        if (remainingRows.length === 0) {
            const container = document.getElementById('rateCardsContainer');
            container.innerHTML = `
                <div id="rateCardsEmptyState" class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 p-8 text-center">
                    <i class="bi bi-tag text-3xl text-slate-400"></i>
                    <p class="mt-2 text-sm font-bold text-[#071d49] font-heading">Belum ada tarif rate card</p>
                    <p class="mt-1 text-xs text-slate-500">Klik tombol di atas untuk menambahkan tarif endorsement Anda.</p>
                </div>
            `;
        }
    }

    function submitProfileForm() {
        const rows = document.querySelectorAll('.social-row');
        if (rows.length === 0) {
            alert('Minimal 1 akun media sosial wajib diisi.');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menyimpan perubahan profil dan rate card ini?')) {
            document.getElementById('editProfileForm').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSocialMediaRemoveButtons();
    });
</script>
@endpush
