@extends('superadmin.layouts.app')

@section('title', ($mode === 'create' ? 'Tambah Brand Baru' : 'Edit Brand') . ' | Superadmin')
@section('page-title', $mode === 'create' ? 'Tambah Brand Baru' : 'Edit Brand')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('superadmin.brands.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Brand & Klien</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">
            {{ $mode === 'create' ? 'Tambah Brand Partner Baru' : 'Edit Data Brand Partner' }}
        </h2>
        <p class="mt-1 text-xs text-[#765f58]">
            {{ $mode === 'create' ? 'Daftarkan profil brand klien yang akan menjalankan kampanye endorsement.' : 'Perbarui data legalitas, profil brand, dan kontak penanggung jawab.' }}
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
    <form method="POST" enctype="multipart/form-data" action="{{ $mode === 'create' ? route('superadmin.brands.store') : route('superadmin.brands.update', $brand) }}" class="space-y-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        {{-- Section 1: Informasi Brand --}}
        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-[#421b13]/5 pb-3 font-extrabold text-[#421b13] font-heading">Profil Brand & Perusahaan</h3>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Nama Brand <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $brand->name) }}" required placeholder="Contoh: Evermos Herbal" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Sektor Industri / Kategori</label>
                    <input type="text" name="industry" value="{{ old('industry', $brand->industry) }}" placeholder="Contoh: Skincare & Kecantikan" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Logo Brand <span class="text-xs font-normal text-[#765f58]">(JPG, PNG, WebP maks. 2MB)</span>
                    </label>
                    <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full rounded-xl border border-dashed border-[#421b13]/20 p-2.5 text-xs text-[#765f58] file:mr-3 file:rounded-lg file:border-0 file:bg-[#d57028]/10 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#d57028]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Status Kemitraan</label>
                    <select name="is_active" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                        <option value="1" @selected(old('is_active', $brand->is_active ?? 1) == 1)>Aktif</option>
                        <option value="0" @selected(old('is_active', $brand->is_active ?? 1) == 0)>Nonaktif</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Alamat Kantor</label>
                    <textarea name="address" rows="3" placeholder="Alamat lengkap operasional kantor brand..." class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">{{ old('address', $brand->address) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 2: Kontak PIC --}}
        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-[#421b13]/5 pb-3 font-extrabold text-[#421b13] font-heading">Penanggung Jawab (PIC) Brand</h3>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Nama Lengkap PIC</label>
                    <input type="text" name="pic_name" value="{{ old('pic_name', $brand->pic_name) }}" placeholder="Contoh: Budi Santoso" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Jabatan / Peran</label>
                    <input type="text" name="pic_title" value="{{ old('pic_title', $brand->pic_title) }}" placeholder="Contoh: Marketing Lead" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Email PIC</label>
                    <input type="email" name="pic_email" value="{{ old('pic_email', $brand->pic_email) }}" placeholder="budi@perusahaan.com" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Nomor WhatsApp / Telepon</label>
                    <input type="text" name="pic_phone" value="{{ old('pic_phone', $brand->pic_phone) }}" placeholder="081234567890" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-[#421b13] font-heading">Catatan Khusus Klien</label>
                    <textarea name="notes" rows="3" placeholder="Informasi preferensi, perjanjian khusus, atau catatan internal..." class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">{{ old('notes', $brand->notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('superadmin.brands.index') }}" class="rounded-xl border border-[#421b13]/15 bg-white px-5 py-2.5 text-xs font-bold text-[#421b13] transition hover:bg-[#f7eee8] font-heading">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-[#b86021] hover:to-[#934510] font-heading">
                {{ $mode === 'create' ? 'Simpan Brand' : 'Perbarui Brand' }}
            </button>
        </div>
    </form>
</div>
@endsection
