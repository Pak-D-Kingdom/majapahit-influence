@extends('layouts.app')

@section('title', 'Katalog E-Commerce Produk — Majapahit Influence')

@section('content')
<div class="min-h-screen bg-gray-50/70 pb-20">
    
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-gray-900 via-gray-900 to-amber-950 text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-amber-900/30">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30">
                        <i class="bi bi-shop"></i> EVERMOS-STYLE PRODUCT HUB
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Katalog Produk & Komisi Kreator</h1>
                    <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                        Pilih produk unggulan dari Brand Partner & Maklon Pak De Group. Dapatkan komisi tetap hingga <strong class="text-amber-400">40%</strong> dan ambil materi promosi instan di Bank Konten.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="px-4 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-bold transition-colors">
                        <i class="bi bi-house mr-1"></i> Beranda
                    </a>
                    <a href="{{ route('brand.register') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-gray-950 text-xs font-black transition-all shadow-md">
                        <i class="bi bi-plus-circle mr-1"></i> Daftarkan Produk Brand
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-8">
        
        {{-- Search & Filter Controls --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 mb-8 space-y-4">
            <form action="{{ route('catalog.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                
                {{-- Search Box --}}
                <div class="md:col-span-5 relative">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, brand, atau kata kunci..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                {{-- Category Dropdown (for mobile / fast switch) --}}
                <div class="md:col-span-3">
                    <select name="category" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->products_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sort Dropdown --}}
                <div class="md:col-span-2">
                    <select name="sort" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none bg-white">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="commission_high" {{ request('sort') == 'commission_high' ? 'selected' : '' }}>Komisi Tertinggi (Rp)</option>
                        <option value="commission_percent" {{ request('sort') == 'commission_percent' ? 'selected' : '' }}>Komisi % Tertinggi</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>

                {{-- Submit Button --}}
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs sm:text-sm transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'category', 'sort', 'pathway']))
                        <a href="{{ route('catalog.index') }}" class="py-2.5 px-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs flex items-center justify-center transition-colors" title="Reset filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            {{-- Horizontal Category Pills --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-2 border-t border-gray-100 no-scrollbar">
                <a href="{{ route('catalog.index', array_merge(request()->except('category'), [])) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all {{ !request('category') ? 'bg-amber-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua Kategori
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('catalog.index', array_merge(request()->except('category'), ['category' => $cat->slug])) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('category') == $cat->slug ? 'bg-amber-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') == $cat->slug ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-600' }}">
                            {{ $cat->products_count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Active Filters Notice --}}
        @if ($activeCategory)
            <div class="mb-6 flex items-center justify-between bg-amber-50 border border-amber-200/80 p-4 rounded-2xl">
                <div>
                    <strong class="text-sm font-bold text-amber-900">Kategori: {{ $activeCategory->name }}</strong>
                    <p class="text-xs text-amber-700 mt-0.5">{{ $activeCategory->description ?: 'Menampilkan seluruh produk dalam kategori ini.' }}</p>
                </div>
                <span class="text-xs font-extrabold text-amber-800">{{ $products->total() }} Produk Ditemukan</span>
            </div>
        @endif

        {{-- Products Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    
                    {{-- Product Image & Floating Badges --}}
                    <div class="relative h-52 bg-gray-100 overflow-hidden">
                        <img src="{{ $product->image_path ?: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=500&auto=format&fit=crop&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-white/95 backdrop-blur-sm text-[11px] font-extrabold text-gray-800 shadow-sm">
                            {{ $product->category->name ?? 'Kategori Umum' }}
                        </span>

                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-green-600 text-white text-[11px] font-black shadow-sm flex items-center gap-1">
                            <i class="bi bi-percent"></i> Komisi {{ number_format($product->locked_commission_percent, 0) }}%
                        </span>

                        @if ($product->promotion_pathway === 'marketplace')
                            <span class="absolute bottom-3 left-3 px-2 py-0.5 rounded bg-gray-900/80 text-white text-[10px] font-bold backdrop-blur-sm">
                                <i class="bi bi-globe2 mr-1"></i> Open Marketplace
                            </span>
                        @elseif ($product->promotion_pathway === 'direct')
                            <span class="absolute bottom-3 left-3 px-2 py-0.5 rounded bg-amber-600/90 text-white text-[10px] font-bold backdrop-blur-sm">
                                <i class="bi bi-star-fill mr-1"></i> Direct Request
                            </span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-1.5">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                {{ $product->brand->name ?? 'Brand Partner' }}
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-amber-600 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('catalog.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                {{ $product->short_description ?: Str::limit($product->description, 90) }}
                            </p>
                        </div>

                        {{-- Price & Locked Commission Highlight --}}
                        <div class="pt-3 border-t border-gray-100 space-y-2">
                            <div class="flex items-baseline justify-between">
                                <span class="text-xs text-gray-500">Harga Retail:</span>
                                <span class="text-sm font-black text-gray-900">{{ $product->formatted_price }}</span>
                            </div>

                            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/80 p-2.5 rounded-xl flex items-center justify-between">
                                <div class="text-[11px] font-bold text-amber-900">
                                    <span>Komisi Creator:</span>
                                </div>
                                <div class="text-xs font-black text-amber-700">
                                    +{{ $product->formatted_commission }}
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('catalog.show', $product->slug) }}" class="py-2.5 text-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs transition-colors">
                                Detail Produk
                            </a>
                            <a href="{{ route('catalog.content-bank', $product->slug) }}" class="py-2.5 text-center rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs shadow-sm transition-colors flex items-center justify-center gap-1.5">
                                <i class="bi bi-download"></i> Bank Konten
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-gray-200 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center text-3xl mx-auto">
                        <i class="bi bi-box2"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tidak ada produk yang cocok dengan pencarian</h3>
                        <p class="text-xs text-gray-500 mt-1">Coba ganti kata kunci atau pilih kategori produk yang lain.</p>
                    </div>
                    <a href="{{ route('catalog.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-amber-600 text-white font-bold text-xs">
                        Lihat Semua Produk
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $products->links() }}
        </div>

    </div>

</div>
@endsection
