@extends('superadmin.layouts.app')

@section('title', 'Tambah Produk Baru | Superadmin')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('superadmin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-kerajaan-dark font-heading">Tambah Produk Baru</h2>
        <p class="mt-1 text-xs text-kerajaan-muted">Input produk ke katalog e-commerce KERAJAAN dan tetapkan persentase komisi terkunci untuk kreator.</p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-kerajaan-red/20 bg-kerajaan-red/10 p-4 text-xs text-kerajaan-red">
            <p class="font-bold font-heading">Periksa kembali data formulir berikut:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 sm:p-8 shadow-xs">
        <form action="{{ route('superadmin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Brand & Category --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="brand_id" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Pilih Brand Partner <span class="text-kerajaan-red">*</span>
                    </label>
                    <select id="brand_id" name="brand_id" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden" required>
                        <option value="">-- Pilih Brand Klien --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Kategori Produk <span class="text-kerajaan-red">*</span>
                    </label>
                    <select id="category_id" name="category_id" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden" required>
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
                    <label for="name" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Nama Produk <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: GlowUp Serum Niacinamide 10%" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="sku" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Kode SKU (Opsional)
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}" placeholder="GLOW-001" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>
            </div>

            {{-- Price, Commission & Stock --}}
            <div class="grid sm:grid-cols-3 gap-5 bg-kerajaan-cream p-5 rounded-2xl border border-kerajaan-dark/10">
                <div>
                    <label for="price" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Harga Retail (Rp) <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="number" id="price" name="price" value="{{ old('price', 100000) }}" min="0" step="1000" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs font-bold text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="locked_commission_percent" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Komisi Terkunci (%) <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="number" id="locked_commission_percent" name="locked_commission_percent" value="{{ old('locked_commission_percent', 40.00) }}" min="1" max="100" step="0.5" class="w-full rounded-xl border border-kerajaan-orange/40 bg-white px-3.5 py-2.5 text-xs font-extrabold text-kerajaan-orange focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Stok Tersedia (pcs) <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 100) }}" min="0" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs font-bold text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden" required>
                </div>
            </div>

            {{-- Image & Pathway --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="image" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Upload Foto Produk Utama <span class="font-normal text-kerajaan-muted">(Format JPG, PNG, WEBP maks. 5MB)</span>
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" class="w-full rounded-xl border border-dashed border-kerajaan-dark/25 bg-kerajaan-cream p-2.5 text-xs text-kerajaan-muted file:mr-3 file:rounded-lg file:border-0 file:bg-kerajaan-orange/10 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-kerajaan-orange hover:file:bg-kerajaan-orange/20 focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label for="promotion_pathway" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Jalur Promosi <span class="text-kerajaan-red">*</span>
                    </label>
                    <select id="promotion_pathway" name="promotion_pathway" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden" required>
                        <option value="both" {{ old('promotion_pathway') == 'both' ? 'selected' : '' }}>Keduanya (Direct & Marketplace)</option>
                        <option value="marketplace" {{ old('promotion_pathway') == 'marketplace' ? 'selected' : '' }}>Marketplace Otomatis (KERAJAAN Hub)</option>
                        <option value="direct" {{ old('promotion_pathway') == 'direct' ? 'selected' : '' }}>Direct Selection Only</option>
                    </select>
                </div>
            </div>

            {{-- Descriptions --}}
            <div>
                <label for="short_description" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                    Ringkasan Singkat (Untuk Kartu Katalog)
                </label>
                <input type="text" id="short_description" name="short_description" value="{{ old('short_description') }}" placeholder="Serum pencerah kulit dengan 10% Niacinamide dan formula lembut..." class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                    Deskripsi Lengkap & Keunggulan Produk (USP)
                </label>
                <textarea id="description" name="description" rows="4" placeholder="Detail formula, cara pemakaian, nomor BPOM, sertifikasi halal..." class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('description') }}</textarea>
            </div>

            {{-- Initial Bank Konten --}}
            <div class="pt-5 border-t border-kerajaan-dark/10 space-y-4">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-kerajaan-orange font-heading flex items-center gap-1.5">
                        <i class="bi bi-google"></i>
                        <span>Bank Konten Promosi (Google Drive)</span>
                    </h3>
                    <p class="text-[11px] text-kerajaan-muted mt-0.5">Seluruh materi video mentah, foto HD, banner, dan naskah copywriting disimpan dalam 1 folder Google Drive agar server tidak penuh.</p>
                </div>

                <div>
                    <label for="drive_folder_url" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Link Folder Google Drive (Opsional)
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-kerajaan-muted">
                            <i class="bi bi-google text-sm text-kerajaan-orange"></i>
                        </div>
                        <input type="url" id="drive_folder_url" name="drive_folder_url" value="{{ old('drive_folder_url') }}" placeholder="https://drive.google.com/drive/folders/..." class="w-full rounded-xl border border-kerajaan-dark/15 bg-white pl-9 pr-3.5 py-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                    </div>
                    <p class="text-[11px] text-kerajaan-muted mt-1">Pastikan hak akses link Google Drive disetel ke <strong>'Siapa saja yang memiliki link' (Viewer / Pelihat)</strong>.</p>
                </div>

                <div>
                    <label for="drive_description" class="block text-xs font-bold text-kerajaan-dark font-heading mb-1.5">
                        Keterangan Isi Folder Google Drive
                    </label>
                    <textarea id="drive_description" name="drive_description" rows="3" placeholder="Sebutkan isi di dalam folder Google Drive ini. Contoh:&#10;1. Video mentah B-Roll 4K unboxing & swatch&#10;2. Foto produk HD (PNG transparan & lifestyle)&#10;3. Naskah script copywriting & talking points&#10;4. Dokumen klaim manfaat & sertifikasi BPOM" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-xs font-mono text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('drive_description') }}</textarea>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-kerajaan-dark/5">
                <a href="{{ route('superadmin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-kerajaan-dark/15 bg-white hover:bg-kerajaan-sand text-kerajaan-dark font-bold text-xs transition font-heading">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-brown hover:from-kerajaan-brown hover:to-[#934510] text-white font-bold text-xs shadow-xs transition font-heading">
                    Simpan Produk ke Katalog
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
