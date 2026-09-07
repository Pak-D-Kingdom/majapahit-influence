@extends('superadmin.layouts.app')

@section('title', 'Edit Produk — ' . $product->name)

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-500">
        <a href="{{ route('superadmin.products.index') }}" class="hover:text-amber-600">Katalog Produk</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-bold">Edit: {{ $product->name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Edit Produk & Bank Konten</h1>
            <p class="text-xs text-gray-500 mt-0.5">Perbarui harga, persentase komisi, atau data produk.</p>
        </div>
        <a href="{{ route('catalog.show', $product->slug) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs inline-flex items-center gap-1.5 transition-colors">
            <i class="bi bi-eye"></i> Lihat di Katalog
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
        <form action="{{ route('superadmin.products.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Brand & Category --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="brand_id" class="block text-xs font-bold text-gray-700 mb-1">
                        Brand Partner <span class="text-red-500">*</span>
                    </label>
                    <select id="brand_id" name="brand_id" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-gray-700 mb-1">
                        Kategori Produk <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Product Name & SKU --}}
            <div class="grid sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-bold text-gray-700 mb-1">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none" required>
                </div>

                <div>
                    <label for="sku" class="block text-xs font-bold text-gray-700 mb-1">
                        Kode SKU
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
            </div>

            {{-- Price & Locked Commission --}}
            <div class="grid sm:grid-cols-3 gap-4 bg-amber-50/60 p-4 rounded-2xl border border-amber-200">
                <div>
                    <label for="price" class="block text-xs font-bold text-amber-900 mb-1">
                        Harga Retail (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="price" name="price" value="{{ old('price', (int)$product->price) }}" min="0" step="1000" class="w-full px-3.5 py-2.5 rounded-xl border border-amber-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                </div>

                <div>
                    <label for="locked_commission_percent" class="block text-xs font-bold text-amber-900 mb-1">
                        Komisi Terkunci (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="locked_commission_percent" name="locked_commission_percent" value="{{ old('locked_commission_percent', (float)$product->locked_commission_percent) }}" min="1" max="100" step="0.5" class="w-full px-3.5 py-2.5 rounded-xl border border-amber-300 text-xs font-bold text-green-700 focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-amber-900 mb-1">
                        Stok Tersedia (pcs) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-amber-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                </div>
            </div>

            {{-- Image & Pathway --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="image_path" class="block text-xs font-bold text-gray-700 mb-1">
                        URL Gambar Produk
                    </label>
                    <input type="url" id="image_path" name="image_path" value="{{ old('image_path', $product->image_path) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <div>
                    <label for="promotion_pathway" class="block text-xs font-bold text-gray-700 mb-1">
                        Jalur Promosi <span class="text-red-500">*</span>
                    </label>
                    <select id="promotion_pathway" name="promotion_pathway" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white" required>
                        <option value="both" {{ old('promotion_pathway', $product->promotion_pathway) == 'both' ? 'selected' : '' }}>Keduanya (Direct & Marketplace)</option>
                        <option value="marketplace" {{ old('promotion_pathway', $product->promotion_pathway) == 'marketplace' ? 'selected' : '' }}>Marketplace Otomatis (Evermos Hub)</option>
                        <option value="direct" {{ old('promotion_pathway', $product->promotion_pathway) == 'direct' ? 'selected' : '' }}>Direct Selection Only</option>
                    </select>
                </div>
            </div>

            {{-- Descriptions --}}
            <div>
                <label for="short_description" class="block text-xs font-bold text-gray-700 mb-1">
                    Ringkasan Singkat
                </label>
                <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-gray-700 mb-1">
                    Deskripsi Lengkap (USP)
                </label>
                <textarea id="description" name="description" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('superadmin.products.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition-colors">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
