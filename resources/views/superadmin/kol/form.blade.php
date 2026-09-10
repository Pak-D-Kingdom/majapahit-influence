@extends('superadmin.layouts.app')

@section('title', ($mode === 'create' ? 'Tambah KOL Baru' : 'Edit Profil KOL') . ' | Superadmin')
@section('page-title', $mode === 'create' ? 'Tambah KOL Baru' : 'Edit Profil KOL')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Title --}}
    <div>
        <a href="{{ route('superadmin.kol.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Data KOL</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-kerajaan-dark font-heading">
            {{ $mode === 'create' ? 'Tambah KOL Baru' : 'Edit Profil KOL' }}
        </h2>
        <p class="mt-1 text-xs text-kerajaan-muted">
            {{ $mode === 'create' ? 'Daftarkan data kreator secara manual ke database agensi.' : 'Perbarui data profil, tier, dan platform media sosial kreator.' }}
        </p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-kerajaan-red/20 bg-kerajaan-red/10 p-4 text-xs text-kerajaan-red">
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
        <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-kerajaan-dark/5 pb-3 font-extrabold text-kerajaan-dark font-heading">Informasi Dasar & Akun</h3>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Nama Lengkap <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $kol->user?->name) }}" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                    @error('name')
                        <span class="mt-1 block text-xs text-kerajaan-red">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Alamat Email <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $kol->user?->email) }}" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                    @error('email')
                        <span class="mt-1 block text-xs text-kerajaan-red">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Password {{ $mode === 'edit' ? '(kosongkan jika tidak diubah)' : '*' }}
                    </label>
                    <input type="password" name="password" {{ $mode === 'create' ? 'required' : '' }} placeholder="{{ $mode === 'edit' ? 'Biarkan kosong untuk password lama' : 'Password login KOL' }}" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Nama Panggilan (Nickname)</label>
                    <input type="text" name="nickname" value="{{ old('nickname', $kol->nickname) }}" placeholder="Contoh: Rina" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $kol->city) }}" placeholder="Contoh: Surabaya" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province', $kol->province) }}" placeholder="Contoh: Jawa Timur" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Tier Klasifikasi</label>
                    <select name="tier_id" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                        <option value="">Pilih Tier</option>
                        @foreach ($tiers as $tier)
                            <option value="{{ $tier->id }}" @selected(old('tier_id', $kol->tier_id) == $tier->id)>{{ $tier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Status Akun</label>
                    <select name="status" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                        @foreach (['pending' => 'Pending', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif', 'blacklist' => 'Blacklist'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $kol->status ?: 'pending') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Bio Singkat</label>
                    <textarea name="bio" rows="3" placeholder="Tuliskan pengalaman atau highlight kreator..." class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('bio', $kol->bio) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section: Platform Media Sosial & Niche --}}
        <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-kerajaan-dark/5 pb-3 font-extrabold text-kerajaan-dark font-heading">Platform Utama & Spesialisasi</h3>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Platform Media Sosial <span class="text-kerajaan-red">*</span>
                    </label>
                    <select name="platform" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                        @foreach (['instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube', 'twitter' => 'Twitter / X'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('platform', $kol->socialMedia?->first()?->platform ?: 'instagram') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Username <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username', $kol->socialMedia?->first()?->username) }}" required placeholder="@username" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">URL Profil</label>
                    <input type="url" name="profile_url" value="{{ old('profile_url', $kol->socialMedia?->first()?->profile_url) }}" placeholder="https://instagram.com/..." class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Jumlah Followers <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="number" min="0" name="followers_count" value="{{ old('followers_count', $kol->socialMedia?->first()?->followers_count ?? 0) }}" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Engagement Rate (%) <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" max="100" name="engagement_rate" value="{{ old('engagement_rate', $kol->socialMedia?->first()?->engagement_rate ?? 0) }}" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <fieldset class="md:col-span-2">
                    <legend class="block text-xs font-bold text-kerajaan-dark font-heading">Pilih Niche / Industri</legend>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($niches as $niche)
                            <label class="flex items-center gap-2.5 rounded-xl border border-kerajaan-dark/10 bg-kerajaan-cream p-3 text-xs font-bold text-kerajaan-dark transition hover:bg-kerajaan-sand">
                                <input type="checkbox" name="niches[]" value="{{ $niche->id }}" @checked(in_array($niche->id, old('niches', $kol->niches?->pluck('id')->all() ?: []))) class="rounded border-kerajaan-dark/20 text-kerajaan-orange focus:ring-kerajaan-orange">
                                <span>{{ $niche->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('superadmin.kol.index') }}" class="rounded-xl border border-kerajaan-dark/15 bg-white px-5 py-2.5 text-xs font-bold text-kerajaan-dark transition hover:bg-kerajaan-sand font-heading">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-brown px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-kerajaan-brown hover:to-[#934510] font-heading">
                {{ $mode === 'create' ? 'Simpan KOL Baru' : 'Perbarui Profil' }}
            </button>
        </div>
    </form>
</div>
@endsection
