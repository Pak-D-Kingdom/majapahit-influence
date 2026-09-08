@extends('superadmin.layouts.app')

@section('title', ($mode === 'create' ? 'Tambah KOL Baru' : 'Edit Profil KOL') . ' | Superadmin')
@section('page-title', $mode === 'create' ? 'Tambah KOL Baru' : 'Edit Profil KOL')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Title --}}
    <div>
        <a href="{{ route('superadmin.kol.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Data KOL</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">
            {{ $mode === 'create' ? 'Tambah KOL Baru' : 'Edit Profil KOL' }}
        </h2>
        <p class="mt-1 text-xs text-[#765f58]">
            {{ $mode === 'create' ? 'Daftarkan data kreator secara manual ke database agensi.' : 'Perbarui data profil, tier, dan platform media sosial kreator.' }}
        </p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-[#d5282d]/20 bg-[#d5282d]/10 p-4 text-xs text-[#d5282d]">
            <p class="font-bold font-heading">Periksa kembali data formulir berikut:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ $mode === 'create' ? route('superadmin.kol.store') : route('superadmin.kol.update', $kol) }}" class="space-y-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        {{-- Section: Informasi Dasar --}}
        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-[#421b13]/5 pb-3 font-extrabold text-[#421b13] font-heading">Informasi Dasar & Akun</h3>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Nama Lengkap <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $kol->user?->name) }}" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                    @error('name')
                        <span class="mt-1 block text-xs text-[#d5282d]">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Alamat Email <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $kol->user?->email) }}" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                    @error('email')
                        <span class="mt-1 block text-xs text-[#d5282d]">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Password {{ $mode === 'edit' ? '(kosongkan jika tidak diubah)' : '*' }}
                    </label>
                    <input type="password" name="password" {{ $mode === 'create' ? 'required' : '' }} placeholder="{{ $mode === 'edit' ? 'Biarkan kosong untuk password lama' : 'Password login KOL' }}" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Nama Panggilan (Nickname)</label>
                    <input type="text" name="nickname" value="{{ old('nickname', $kol->nickname) }}" placeholder="Contoh: Rina" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $kol->city) }}" placeholder="Contoh: Surabaya" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province', $kol->province) }}" placeholder="Contoh: Jawa Timur" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Tier Klasifikasi</label>
                    <select name="tier_id" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                        <option value="">Pilih Tier</option>
                        @foreach ($tiers as $tier)
                            <option value="{{ $tier->id }}" @selected(old('tier_id', $kol->tier_id) == $tier->id)>{{ $tier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Status Akun</label>
                    <select name="status" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                        @foreach (['pending' => 'Pending', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'blacklist' => 'Blacklist'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $kol->status ?: 'pending') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Bio Singkat</label>
                    <textarea name="bio" rows="3" placeholder="Tuliskan pengalaman atau highlight kreator..." class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">{{ old('bio', $kol->bio) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section: Platform Media Sosial & Niche --}}
        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-[#421b13]/5 pb-3 font-extrabold text-[#421b13] font-heading">Platform Utama & Spesialisasi</h3>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Platform Media Sosial <span class="text-[#d5282d]">*</span>
                    </label>
                    <select name="platform" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                        @foreach (['instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'twitter' => 'Twitter / X'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('platform', $kol->socialMedia?->first()?->platform ?: 'instagram') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Username <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username', $kol->socialMedia?->first()?->username) }}" required placeholder="@username" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">URL Profil</label>
                    <input type="url" name="profile_url" value="{{ old('profile_url', $kol->socialMedia?->first()?->profile_url) }}" placeholder="https://instagram.com/..." class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Jumlah Followers <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" min="0" name="followers_count" value="{{ old('followers_count', $kol->socialMedia?->first()?->followers_count ?? 0) }}" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Engagement Rate (%) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" max="100" name="engagement_rate" value="{{ old('engagement_rate', $kol->socialMedia?->first()?->engagement_rate ?? 0) }}" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <fieldset class="md:col-span-2">
                    <legend class="block text-xs font-bold text-[#421b13] font-heading">Pilih Niche / Industri</legend>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($niches as $niche)
                            <label class="flex items-center gap-2.5 rounded-xl border border-[#421b13]/10 bg-[#fbf7f4] p-3 text-xs font-bold text-[#421b13] transition hover:bg-[#f7eee8]">
                                <input type="checkbox" name="niches[]" value="{{ $niche->id }}" @checked(in_array($niche->id, old('niches', $kol->niches?->pluck('id')->all() ?: []))) class="rounded border-[#421b13]/20 text-[#d57028] focus:ring-[#d57028]">
                                <span>{{ $niche->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('superadmin.kol.index') }}" class="rounded-xl border border-[#421b13]/15 bg-white px-5 py-2.5 text-xs font-bold text-[#421b13] transition hover:bg-[#f7eee8] font-heading">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-[#b86021] hover:to-[#934510] font-heading">
                {{ $mode === 'create' ? 'Simpan KOL Baru' : 'Perbarui Profil' }}
            </button>
        </div>
    </form>
</div>
@endsection
