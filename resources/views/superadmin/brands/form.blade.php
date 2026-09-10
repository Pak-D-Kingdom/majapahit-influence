@extends('superadmin.layouts.app')

@section('title', ($mode === 'create' ? 'Tambah Brand Baru' : 'Edit Brand') . ' | Superadmin')
@section('page-title', $mode === 'create' ? 'Tambah Brand Baru' : 'Edit Brand')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('superadmin.brands.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Brand & Klien</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-kerajaan-dark font-heading">
            {{ $mode === 'create' ? 'Tambah Brand Partner Baru' : 'Edit Data Brand Partner' }}
        </h2>
        <p class="mt-1 text-xs text-kerajaan-muted">
            {{ $mode === 'create' ? 'Daftarkan profil brand klien yang akan menjalankan kampanye endorsement.' : 'Perbarui data legalitas, profil brand, dan kontak penanggung jawab.' }}
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
    <form method="POST" enctype="multipart/form-data" action="{{ $mode === 'create' ? route('superadmin.brands.store') : route('superadmin.brands.update', $brand) }}" class="space-y-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        {{-- Section 1: Informasi Brand --}}
        <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-kerajaan-dark/5 pb-3 font-extrabold text-kerajaan-dark font-heading">Profil Brand & Perusahaan</h3>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Nama Brand <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $brand->name) }}" required placeholder="Contoh: Evermos Herbal" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Sektor Industri / Kategori</label>
                    <input type="text" name="industry" value="{{ old('industry', $brand->industry) }}" placeholder="Contoh: Skincare & Kecantikan" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Logo Brand <span class="text-xs font-normal text-kerajaan-muted">(JPG, PNG, WebP maks. 2MB)</span>
                    </label>
                    <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full rounded-xl border border-dashed border-kerajaan-dark/20 p-2.5 text-xs text-kerajaan-muted file:mr-3 file:rounded-lg file:border-0 file:bg-kerajaan-orange/10 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-kerajaan-orange">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Status Kemitraan</label>
                    <select name="is_active" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                        <option value="1" @selected(old('is_active', $brand->is_active ?? 1) == 1)>Aktif</option>
                        <option value="0" @selected(old('is_active', $brand->is_active ?? 1) == 0)>Nonaktif</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Alamat Kantor</label>
                    <textarea name="address" rows="3" placeholder="Alamat lengkap operasional kantor brand..." class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('address', $brand->address) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 2: Kontak PIC --}}
        <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-kerajaan-dark/5 pb-3 font-extrabold text-kerajaan-dark font-heading">Penanggung Jawab (PIC) Brand</h3>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Nama Lengkap PIC</label>
                    <input type="text" name="pic_name" value="{{ old('pic_name', $brand->pic_name) }}" placeholder="Contoh: Budi Santoso" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Jabatan / Peran</label>
                    <input type="text" name="pic_title" value="{{ old('pic_title', $brand->pic_title) }}" placeholder="Contoh: Marketing Lead" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Email PIC</label>
                    <input type="email" name="pic_email" value="{{ old('pic_email', $brand->pic_email) }}" placeholder="budi@perusahaan.com" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Nomor WhatsApp / Telepon</label>
                    <input type="text" name="pic_phone" value="{{ old('pic_phone', $brand->pic_phone) }}" placeholder="081234567890" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Catatan Khusus Klien</label>
                    <textarea name="notes" rows="3" placeholder="Informasi preferensi, perjanjian khusus, atau catatan internal..." class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('notes', $brand->notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('superadmin.brands.index') }}" class="rounded-xl border border-kerajaan-dark/15 bg-white px-5 py-2.5 text-xs font-bold text-kerajaan-dark transition hover:bg-kerajaan-sand font-heading">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-brown px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-kerajaan-brown hover:to-[#934510] font-heading">
                {{ $mode === 'create' ? 'Simpan Brand' : 'Perbarui Brand' }}
            </button>
        </div>
    </form>
</div>
@endsection
