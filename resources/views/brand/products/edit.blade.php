@extends('brand.layouts.app')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Data Produk')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Breadcrumb & Header --}}
    <div>
        <a href="{{ route('brand.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">
            Edit Data Produk
        </h2>
        <p class="mt-1 text-xs text-[#765f58]">
            Perbarui data produk, harga eceran, deskripsi, atau foto produk brand Anda.
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
    <form action="{{ route('brand.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs space-y-5">
            <h3 class="border-b border-[#421b13]/5 pb-3 font-extrabold text-[#421b13] font-heading">
                Informasi & Spesifikasi Produk
            </h3>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold text-[#421b13] font-heading">
                        Nama Produk <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-[#421b13] font-heading">
                        Kategori Produk <span class="text-[#d5282d]">*</span>
                    </label>
                    <select name="category_id" id="category_id" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->product_category_id ?? $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-xs font-bold text-[#421b13] font-heading">
                        Harga Jual Eceran (Rp) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                </div>

                <div class="sm:col-span-2">
                    <label for="locked_commission_percent" class="block text-xs font-bold text-[#421b13] font-heading">
                        Alokasi Komisi Afiliasi / Endorsement (%) <span class="text-[#d5282d]">*</span>
                    </label>
                    <input type="number" name="locked_commission_percent" id="locked_commission_percent" value="{{ old('locked_commission_percent', $product->locked_commission_percent ?? 40) }}" max="100" min="0" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>
                    <p class="mt-1.5 text-xs text-[#765f58]">Persentase komisi bagi kreator yang mempromosikan produk.</p>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-bold text-[#421b13] font-heading">
                        Deskripsi & Keunggulan Produk (USP) <span class="text-[#d5282d]">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label for="image" class="block text-xs font-bold text-[#421b13] font-heading">
                        Ganti Foto Produk <span class="font-normal text-[#765f58]">(Kosongkan jika tidak ingin mengubah)</span>
                    </label>
                    @if($product->image_path)
                        <div class="mt-2 mb-3 flex items-center gap-3">
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="size-16 rounded-xl object-cover border border-[#421b13]/10">
                            <span class="text-xs text-[#765f58]">Foto produk saat ini</span>
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*" class="mt-2 block w-full rounded-xl border border-dashed border-[#421b13]/20 bg-[#fff9f4] p-3 text-xs text-[#765f58] file:mr-3 file:rounded-lg file:border-0 file:bg-[#d57028]/10 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-[#d57028]">
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('brand.products.index') }}" class="btn-majapahit-secondary text-xs">
                Batal
            </a>
            <button type="submit" class="btn-majapahit-primary text-xs">
                <i class="bi bi-check-lg"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>
@endsection
