@extends('brand.layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('brand.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">
            Tambah Produk Baru
        </h2>
        <p class="mt-1 text-xs text-[#765f58]">
            Daftarkan produk unggulan brand Anda. Produk akan ditinjau oleh tim Superadmin sebelum diterbitkan ke katalog publik dan bank konten.
        </p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-[#d5282d]/20 bg-[#d5282d]/10 p-4 text-xs text-[#d5282d]">
            <p class="font-bold font-heading">Mohon periksa kembali formulir:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('brand.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs space-y-5">
            <h3 class="border-b border-[#421b13]/5 pb-3 font-extrabold text-[#421b13] font-heading">
                Informasi & Spesifikasi Produk
            </h3>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold text-[#421b13] font-heading">
                        Nama Produk <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Radiant Glow Serum Vitamin C 30ml" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-[#421b13] font-heading">
                        Kategori Produk <span class="text-[#d5282d]">*</span>
                    </label>
                    <select name="category_id" id="category_id" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-xs font-bold text-[#421b13] font-heading">
                        Harga Jual Eceran (Rp) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" placeholder="Contoh: 125000" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div class="sm:col-span-2">
                    <label for="locked_commission_percent" class="block text-xs font-bold text-[#421b13] font-heading">
                        Alokasi Komisi Afiliasi / Endorsement (%) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" name="locked_commission_percent" id="locked_commission_percent" value="{{ old('locked_commission_percent', 40) }}" max="100" min="0" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                    <p class="mt-1.5 text-xs text-[#765f58]">Persentase dari harga jual produk yang dialokasikan ke kreator/KOL (standar ekosistem: 40%).</p>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-bold text-[#421b13] font-heading">
                        Deskripsi & Keunggulan Produk (USP) <span class="text-[#d5282d]">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" placeholder="Jelaskan manfaat produk, bahan aktif utama, cara pemakaian, dan sertifikasi BPOM/Halal..." class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>{{ old('description') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label for="image" class="block text-xs font-bold text-[#421b13] font-heading">
                        Foto Produk Utama <span class="text-[#d5282d]">*</span> <span class="font-normal text-[#765f58]">(Format JPG, PNG, WebP maks. 2MB)</span>
                    </label>
                    <input type="file" name="image" id="image" accept="image/*" class="mt-2 block w-full rounded-xl border border-dashed border-[#421b13]/20 bg-[#fff9f4] p-3 text-xs text-[#765f58] file:mr-3 file:rounded-lg file:border-0 file:bg-[#d57028]/10 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#d57028]" required>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('brand.products.index') }}" class="btn-majapahit-secondary text-xs">
                Batal
            </a>
            <button type="submit" class="btn-majapahit-primary text-xs">
                <i class="bi bi-send-check"></i>
                <span>Simpan & Ajukan Verifikasi</span>
            </button>
        </div>
    </form>
</div>
@endsection
