@extends('superadmin.layouts.app')

@section('title', 'Edit Produk & Bank Konten | ' . $product->name . ' | Superadmin')
@section('page-title', 'Edit Produk & Bank Konten')

@section('content')
<div class="space-y-8 max-w-5xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('superadmin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <div class="mt-3 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#421b13] font-heading">Edit Produk & Bank Konten</h2>
                <p class="mt-1 text-xs text-[#765f58]">Perbarui harga e-commerce, komisi terkunci, dan kelola seluruh aset promosi Bank Konten.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('catalog.show', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2 text-xs font-bold text-[#421b13] shadow-xs transition hover:bg-[#f7eee8] font-heading">
                    <i class="bi bi-shop text-[11px] text-[#d57028]"></i>
                    <span>Katalog</span>
                </a>
                <a href="{{ route('catalog.content-bank', $product->slug) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-[#d57028]/30 bg-[#d57028]/10 px-3.5 py-2 text-xs font-bold text-[#d57028] shadow-xs transition hover:bg-[#d57028]/20 font-heading">
                    <i class="bi bi-folder-symlink-fill text-[11px]"></i>
                    <span>Bank Konten Publik</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Publication Status Banner --}}
    @php
        $hasDrive = $product->contentBanks->whereNotNull('external_url')->isNotEmpty();
    @endphp
    <div class="rounded-2xl border {{ $product->is_active ? 'border-emerald-200 bg-emerald-50/70' : 'border-amber-200 bg-amber-50/60' }} p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="size-10 rounded-xl {{ $product->is_active ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200' }} border flex items-center justify-center shrink-0">
                <i class="bi {{ $product->is_active ? 'bi-globe' : 'bi-eye-slash' }} text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold font-heading text-[#421b13]">Status E-Commerce:</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $product->is_active ? 'bg-emerald-600 text-white' : 'bg-amber-600 text-white' }}">
                        {{ $product->is_active ? 'Tayang di E-Commerce' : 'Draft / Belum Tayang' }}
                    </span>
                </div>
                <p class="text-xs text-[#765f58] mt-1">
                    @if($product->is_active)
                        Produk ini aktif dan dapat dilihat oleh publik serta kreator/KOL di katalog e-commerce.
                    @else
                        Produk ini belum tampil di katalog e-commerce publik. Superadmin berhak mempublikasikannya setelah Bank Konten Google Drive terisi lengkap.
                    @endif
                </p>
            </div>
        </div>

        <div class="shrink-0 flex items-center gap-2 self-end sm:self-center">
            @if($product->is_active)
                <form action="{{ route('superadmin.products.toggle-publish', $product->id) }}" method="POST" onsubmit="return confirm('Tarik produk ini dari katalog E-Commerce publik?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-white border border-[#421b13]/15 hover:bg-rose-50 text-rose-700 font-bold text-xs transition shadow-2xs inline-flex items-center gap-1.5 font-heading">
                        <i class="bi bi-arrow-down-circle"></i> Tarik dari E-Commerce
                    </button>
                </form>
            @else
                @if($hasDrive)
                    <form action="{{ route('superadmin.products.toggle-publish', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs inline-flex items-center gap-1.5 font-heading">
                            <i class="bi bi-cloud-upload"></i> Publish ke E-Commerce
                        </button>
                    </form>
                @else
                    <a href="#bank-konten" class="px-4 py-2 rounded-xl bg-[#d57028] hover:bg-[#b86021] text-white font-bold text-xs transition shadow-xs inline-flex items-center gap-1.5 font-heading">
                        <i class="bi bi-plus-circle"></i> Isi GDrive Dulu untuk Publish
                    </a>
                @endif
            @endif
        </div>
    </div>

    {{-- Product Edit Form Card --}}
    <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 sm:p-8 shadow-xs">
        <h3 class="text-sm font-black text-[#421b13] font-heading border-b border-[#421b13]/8 pb-3 mb-6 flex items-center gap-2">
            <i class="bi bi-box-seam text-[#d57028]"></i>
            <span>Informasi Produk & Pengaturan Komisi</span>
        </h3>

        <form action="{{ route('superadmin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

            {{-- Image Upload & Pathway --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="image" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                        Upload Foto Produk Utama <span class="font-normal text-[#765f58]">(Format JPG, PNG, WEBP maks. 5MB)</span>
                    </label>
                    @if ($product->image_path)
                        <div class="mb-3 flex items-center gap-3 p-3 bg-[#fbf7f4] rounded-xl border border-[#421b13]/10">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-14 h-14 rounded-lg object-cover border border-[#421b13]/10 shadow-xs">
                            <div class="text-xs text-[#765f58]">
                                <span class="font-bold text-[#421b13] block">Foto Saat Ini</span>
                                <span class="text-[11px]">Pilih file baru di bawah jika ingin mengganti.</span>
                            </div>
                        </div>
                    @endif
                    <input type="file" id="image" name="image" accept="image/*" class="w-full rounded-xl border border-dashed border-[#421b13]/25 bg-[#fff9f4] p-2.5 text-xs text-[#765f58] file:mr-3 file:rounded-lg file:border-0 file:bg-[#d57028]/10 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#d57028] hover:file:bg-[#d57028]/20 focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
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
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#421b13]/8">
                <a href="{{ route('superadmin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-[#421b13]/15 bg-white hover:bg-[#f7eee8] text-[#421b13] font-bold text-xs transition font-heading">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] hover:from-[#b86021] hover:to-[#934510] text-white font-bold text-xs shadow-xs transition font-heading">
                    Simpan Perubahan Produk
                </button>
            </div>
        </form>
    </div>

    {{-- Content Bank Management Card (Google Drive) --}}
    <div id="bank-konten" class="rounded-2xl border border-[#421b13]/8 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#421b13]/8 pb-4">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-black text-[#421b13] font-heading">Bank Konten Produk (Folder Google Drive)</h3>
                    <span class="px-2.5 py-0.5 rounded-full {{ $product->contentBanks->isNotEmpty() ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' }} text-xs font-bold border">
                        {{ $product->contentBanks->isNotEmpty() ? 'Tersambung ke Google Drive' : 'Belum Tersambung' }}
                    </span>
                </div>
                <p class="text-xs text-[#765f58] mt-1">Seluruh aset promosi (video mentah B-Roll, foto produk HD, banner grafis, dan script copywriting) disatukan dalam link Google Drive agar tidak membebani server.</p>
            </div>
            @if($product->contentBanks->isEmpty())
                <button type="button" onclick="document.getElementById('add-asset-modal').classList.remove('hidden')" class="btn-majapahit-primary text-xs shrink-0">
                    <i class="bi bi-google"></i> Sambungkan Google Drive
                </button>
            @else
                <button type="button" onclick="document.getElementById('add-asset-modal').classList.remove('hidden')" class="btn-majapahit-secondary text-xs shrink-0">
                    <i class="bi bi-plus-circle"></i> Tambah Folder Tambahan
                </button>
            @endif
        </div>

        {{-- Asset List --}}
        <div class="space-y-4">
            @forelse ($product->contentBanks as $asset)
                <div class="rounded-2xl border border-[#421b13]/10 bg-[#fbf7f4]/60 p-5 space-y-4 hover:border-[#d57028]/40 transition">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="size-11 rounded-xl bg-emerald-100/80 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                                <i class="bi bi-google text-xl"></i>
                            </div>
                            <div class="space-y-1">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <i class="bi bi-folder2-open"></i> Google Drive Bank Konten
                                </span>
                                <h4 class="text-sm font-bold text-[#421b13] font-heading">{{ $asset->title ?: 'Folder Master Google Drive' }}</h4>
                                @if(!empty($asset->external_url))
                                    <div class="flex items-center gap-2">
                                        <a href="{{ $asset->external_url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-[#d57028] hover:underline font-mono break-all line-clamp-1 inline-flex items-center gap-1">
                                            <span>{{ $asset->external_url }}</span>
                                            <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-start">
                            @if (!empty($asset->external_url))
                                <a href="{{ $asset->external_url }}" target="_blank" rel="noopener noreferrer" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 font-heading">
                                    <i class="bi bi-google"></i>
                                    <span>Buka di Google Drive</span>
                                </a>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $asset->external_url }}'); alert('Link Google Drive berhasil disalin ke clipboard!');" class="p-1.5 rounded-lg bg-white border border-[#421b13]/15 hover:bg-[#f7eee8] text-[#421b13] text-xs transition" title="Salin Link">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            @endif
                            <button type="button" onclick="openEditModal({{ json_encode($asset) }})" class="p-1.5 rounded-lg bg-[#d57028]/10 hover:bg-[#d57028]/20 text-[#d57028] text-xs transition" title="Edit Link & Keterangan">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('superadmin.content-banks.destroy', $asset->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi Bank Konten Google Drive ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-[#d5282d]/10 hover:bg-[#d5282d]/20 text-[#d5282d] text-xs transition" title="Hapus Aset">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Description of What's Inside the Drive Folder --}}
                    @if ($asset->content_text)
                        <div class="bg-white p-4 rounded-xl border border-[#421b13]/10 space-y-2">
                            <p class="text-[11px] font-bold text-[#421b13] font-heading flex items-center gap-1.5">
                                <i class="bi bi-info-circle text-[#d57028]"></i> Keterangan Isi di dalam Folder Google Drive:
                            </p>
                            <p class="text-xs text-[#765f58] whitespace-pre-line leading-relaxed font-sans">{{ $asset->content_text }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-[#421b13]/15 bg-[#fbf7f4] p-8 text-center text-[#765f58] space-y-3">
                    <div class="size-12 rounded-2xl bg-[#d57028]/10 text-[#d57028] mx-auto flex items-center justify-center">
                        <i class="bi bi-google text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-xs text-[#421b13]">Belum Ada Link Folder Google Drive</p>
                        <p class="text-[11px] text-[#765f58] mt-1 max-w-md mx-auto">Tambahkan link folder Google Drive yang berisi materi promosi (video mentah B-Roll, foto produk HD, dokumen klaim, dan script naskah) agar kreator/KOL dapat langsung mengunduh dan mempromosikannya.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('add-asset-modal').classList.remove('hidden')" class="btn-majapahit-primary text-xs inline-flex items-center gap-1.5">
                        <i class="bi bi-plus-circle"></i> Tambah Link Google Drive Sekarang
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL TAMBAH LINK GOOGLE DRIVE --}}
<div id="add-asset-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl border border-[#421b13]/10 bg-white p-6 shadow-xl space-y-5">
        <div class="flex items-center justify-between border-b border-[#421b13]/8 pb-3">
            <h3 class="text-sm font-black text-[#421b13] font-heading flex items-center gap-2">
                <i class="bi bi-google text-[#d57028]"></i>
                <span>Tambah Link Google Drive Bank Konten</span>
            </h3>
            <button type="button" onclick="document.getElementById('add-asset-modal').classList.add('hidden')" class="text-[#765f58] hover:text-[#421b13]">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('superadmin.products.content-banks.store', $product->id) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="title" value="Folder Master Google Drive - {{ $product->name }}">

            <div>
                <label for="create_external_url" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Link Folder Google Drive / Cloud <span class="text-[#d5282d]">*</span>
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#765f58]">
                        <i class="bi bi-google text-sm text-[#d57028]"></i>
                    </div>
                    <input type="url" id="create_external_url" name="external_url" placeholder="https://drive.google.com/drive/folders/..." class="w-full rounded-xl border border-[#421b13]/15 bg-white pl-9 pr-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>
                <p class="text-[11px] text-[#765f58] mt-1">Pastikan hak akses link Google Drive disetel ke <strong>'Siapa saja yang memiliki link' (Viewer / Pelihat)</strong>.</p>
            </div>

            <div>
                <label for="create_content_text" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Keterangan Isi Folder Google Drive
                </label>
                <textarea id="create_content_text" name="content_text" rows="4" placeholder="Sebutkan isi di dalam folder Google Drive ini. Contoh:&#10;1. Video mentah B-Roll 4K unboxing & swatch&#10;2. Foto produk HD (PNG transparan & lifestyle)&#10;3. Naskah script copywriting & talking points&#10;4. Dokumen klaim manfaat & sertifikasi BPOM" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs font-mono text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end gap-2 border-t border-[#421b13]/8">
                <button type="button" onclick="document.getElementById('add-asset-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-[#421b13]/15 bg-white hover:bg-[#f7eee8] text-[#421b13] font-bold text-xs transition font-heading">
                    Batal
                </button>
                <button type="submit" class="btn-majapahit-primary text-xs">
                    Simpan Link Google Drive
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT LINK GOOGLE DRIVE --}}
<div id="edit-asset-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl border border-[#421b13]/10 bg-white p-6 shadow-xl space-y-5">
        <div class="flex items-center justify-between border-b border-[#421b13]/8 pb-3">
            <h3 class="text-sm font-black text-[#421b13] font-heading flex items-center gap-2">
                <i class="bi bi-pencil-square text-[#d57028]"></i>
                <span>Edit Link & Keterangan Google Drive</span>
            </h3>
            <button type="button" onclick="document.getElementById('edit-asset-modal').classList.add('hidden')" class="text-[#765f58] hover:text-[#421b13]">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="edit-asset-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_title" name="title" value="">

            <div>
                <label for="edit_external_url" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Link Folder Google Drive / Cloud <span class="text-[#d5282d]">*</span>
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-[#765f58]">
                        <i class="bi bi-google text-sm text-[#d57028]"></i>
                    </div>
                    <input type="url" id="edit_external_url" name="external_url" class="w-full rounded-xl border border-[#421b13]/15 bg-white pl-9 pr-3.5 py-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>
                <p class="text-[11px] text-[#765f58] mt-1">Pastikan hak akses link Google Drive disetel ke <strong>'Siapa saja yang memiliki link' (Viewer / Pelihat)</strong>.</p>
            </div>

            <div>
                <label for="edit_content_text" class="block text-xs font-bold text-[#421b13] font-heading mb-1.5">
                    Keterangan Isi Folder Google Drive
                </label>
                <textarea id="edit_content_text" name="content_text" rows="4" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-xs font-mono text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end gap-2 border-t border-[#421b13]/8">
                <button type="button" onclick="document.getElementById('edit-asset-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-[#421b13]/15 bg-white hover:bg-[#f7eee8] text-[#421b13] font-bold text-xs transition font-heading">
                    Batal
                </button>
                <button type="submit" class="btn-majapahit-primary text-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(asset) {
        const modal = document.getElementById('edit-asset-modal');
        const form = document.getElementById('edit-asset-form');
        
        form.action = `/superadmin/content-banks/${asset.id}`;
        document.getElementById('edit_title').value = asset.title || '';
        document.getElementById('edit_external_url').value = asset.external_url || '';
        document.getElementById('edit_content_text').value = asset.content_text || '';
        
        modal.classList.remove('hidden');
    }
</script>
@endpush
@endsection

