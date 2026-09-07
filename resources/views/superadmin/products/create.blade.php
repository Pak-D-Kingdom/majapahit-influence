@extends('superadmin.layouts.app')

@section('title', 'Tambah Produk Baru | Superadmin')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('superadmin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">Tambah Produk Baru</h2>
        <p class="mt-1 text-xs text-[#765f58]">Input produk ke katalog e-commerce Evermos dan tetapkan persentase komisi terkunci untuk kreator.</p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-[#d5282d]/20 bg-[#d5282d]/10 p-4 text-xs text-[#d5282d]">
            <p class="font-bold font-heading">Periksa kembali data formulir berikut:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 sm:p-8 shadow-xs">
        <form action="{{ route('superadmin.products.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Brand & Category --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="brand_id" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Pilih Brand Partner <span class="text-[#d5282d]">*</span>
                    </label>
                    <select id="brand_id" name="brand_id" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                        <option value="">-- Pilih Brand Klien --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Kategori Produk <span class="text-[#d5282d]">*</span>
                    </label>
                    <select id="category_id" name="category_id" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Product Name & SKU --}}
            <div class="grid sm:grid-cols-3 gap-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Nama Produk <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: GlowUp Serum Niacinamide 10%" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="sku" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Kode SKU (Opsional)
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}" placeholder="GLOW-001" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>
            </div>

            {{-- Price, Commission & Stock --}}
            <div class="grid sm:grid-cols-3 gap-5 bg-[#fbf7f4] p-5 rounded-2xl border border-[#421b13]/10">
                <div>
                    <label for="price" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Harga Retail (Rp) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" id="price" name="price" value="{{ old('price', 100000) }}" min="0" step="1000" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs font-bold text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="locked_commission_percent" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Komisi Terkunci (%) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" id="locked_commission_percent" name="locked_commission_percent" value="{{ old('locked_commission_percent', 40.00) }}" min="1" max="100" step="0.5" class="w-full rounded-xl border border-[#d57028]/40 bg-white px-3.5 py-2.5 text-xs font-extrabold text-[#d57028] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Stok Tersedia (pcs) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 100) }}" min="0" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs font-bold text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>
            </div>

            {{-- Image & Pathway --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="image_path" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        URL Gambar Produk (Foto Utama)
                    </label>
                    <input type="url" id="image_path" name="image_path" value="{{ old('image_path') }}" placeholder="https://images.unsplash.com/..." class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label for="promotion_pathway" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Jalur Promosi <span class="text-[#d5282d]">*</span>
                    </label>
                    <select id="promotion_pathway" name="promotion_pathway" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                        <option value="both" {{ old('promotion_pathway') == 'both' ? 'selected' : '' }}>Keduanya (Direct & Marketplace)</option>
                        <option value="marketplace" {{ old('promotion_pathway') == 'marketplace' ? 'selected' : '' }}>Marketplace Otomatis (Evermos Hub)</option>
                        <option value="direct" {{ old('promotion_pathway') == 'direct' ? 'selected' : '' }}>Direct Selection Only</option>
                    </select>
                </div>
            </div>

            {{-- Descriptions --}}
            <div>
                <label for="short_description" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Ringkasan Singkat (Untuk Kartu Katalog)
                </label>
                <input type="text" id="short_description" name="short_description" value="{{ old('short_description') }}" placeholder="Serum pencerah kulit dengan 10% Niacinamide dan formula lembut..." class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Deskripsi Lengkap & Keunggulan Produk (USP)
                </label>
                <textarea id="description" name="description" rows="4" placeholder="Detail formula, cara pemakaian, nomor BPOM, sertifikasi halal..." class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">{{ old('description') }}</textarea>
            </div>

            {{-- Initial Bank Konten --}}
            <div class="pt-5 border-t border-[#421b13]/10 space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-[#d57028] font-heading">Aset Awal Bank Konten (Opsional)</h3>

                <div>
                    <label for="copywriting_brief" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Script Copywriting / Talking Points Promosi
                    </label>
                    <textarea id="copywriting_brief" name="copywriting_brief" rows="3" placeholder="HOOK: Jangan lewatkan serum viral ini!&#10;USP: Mencerahkan dalam 14 hari...&#10;CTA: Klik keranjang sekarang!" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs font-mono text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">{{ old('copywriting_brief') }}</textarea>
                </div>

                <div>
                    <label for="drive_folder_url" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Link Folder Google Drive (Aset Foto HD / Video B-Roll)
                    </label>
                    <input type="url" id="drive_folder_url" name="drive_folder_url" value="{{ old('drive_folder_url') }}" placeholder="https://drive.google.com/drive/folders/..." class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#421b13]/5">
                <a href="{{ route('superadmin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-[#421b13]/15 bg-white hover:bg-[#f7eee8] text-[#421b13] font-bold text-xs transition font-heading">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] hover:from-[#b86021] hover:to-[#934510] text-white font-bold text-xs shadow-xs transition font-heading">
                    Simpan Produk ke Katalog
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
