@extends('superadmin.layouts.app')

@section('title', 'Edit Produk | ' . $product->name . ' | Superadmin')
@section('page-title', 'Edit Produk')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('superadmin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <div class="mt-3 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#421b13] font-heading">Edit Produk & Bank Konten</h2>
                <p class="mt-1 text-xs text-[#765f58]">Perbarui harga e-commerce, komisi terkunci, atau aset promosi produk.</p>
            </div>
            <a href="{{ route('catalog.show', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-[#421b13]/15 bg-white px-4 py-2 text-xs font-bold text-[#421b13] shadow-xs transition hover:bg-[#f7eee8] font-heading">
                <i class="bi bi-box-arrow-up-right text-[11px] text-[#d57028]"></i>
                <span>Lihat di Katalog</span>
            </a>
        </div>
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
        <form action="{{ route('superadmin.products.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Brand & Category --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="brand_id" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Brand Partner <span class="text-[#d5282d]">*</span>
                    </label>
                    <select id="brand_id" name="brand_id" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="sku" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Kode SKU
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>
            </div>

            {{-- Price, Commission & Stock --}}
            <div class="grid sm:grid-cols-3 gap-5 bg-[#fbf7f4] p-5 rounded-2xl border border-[#421b13]/10">
                <div>
                    <label for="price" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Harga Retail (Rp) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" id="price" name="price" value="{{ old('price', (int)$product->price) }}" min="0" step="1000" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs font-bold text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="locked_commission_percent" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Komisi Terkunci (%) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" id="locked_commission_percent" name="locked_commission_percent" value="{{ old('locked_commission_percent', (float)$product->locked_commission_percent) }}" min="1" max="100" step="0.5" class="w-full rounded-xl border border-[#d57028]/40 bg-white px-3.5 py-2.5 text-xs font-extrabold text-[#d57028] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Stok Tersedia (pcs) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs font-bold text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>
            </div>

            {{-- Image & Pathway --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="image_path" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        URL Gambar Produk
                    </label>
                    <input type="url" id="image_path" name="image_path" value="{{ old('image_path', $product->image_path) }}" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                </div>

                <div>
                    <label for="promotion_pathway" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Jalur Promosi <span class="text-[#d5282d]">*</span>
                    </label>
                    <select id="promotion_pathway" name="promotion_pathway" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                        <option value="both" {{ old('promotion_pathway', $product->promotion_pathway) == 'both' ? 'selected' : '' }}>Keduanya (Direct & Marketplace)</option>
                        <option value="marketplace" {{ old('promotion_pathway', $product->promotion_pathway) == 'marketplace' ? 'selected' : '' }}>Marketplace Otomatis (Evermos Hub)</option>
                        <option value="direct" {{ old('promotion_pathway', $product->promotion_pathway) == 'direct' ? 'selected' : '' }}>Direct Selection Only</option>
                    </select>
                </div>
            </div>

            {{-- Descriptions --}}
            <div>
                <label for="short_description" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Ringkasan Singkat
                </label>
                <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Deskripsi Lengkap & Keunggulan Produk (USP)
                </label>
                <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Submit Actions --}}
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#421b13]/5">
                <a href="{{ route('superadmin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-[#421b13]/15 bg-white hover:bg-[#f7eee8] text-[#421b13] font-bold text-xs transition font-heading">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] hover:from-[#b86021] hover:to-[#934510] text-white font-bold text-xs shadow-xs transition font-heading">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
