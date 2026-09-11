@extends('layouts.app')

@section('title', 'Katalog Produk & Komisi Kreator — KERAJAAN')

@section('content')

{{-- Shared Top Navbar --}}
@include('partials.landing-header', ['active' => 'catalog'])

<div class="min-h-screen bg-[#f8fafc] text-[#071d49] flex flex-col justify-between font-sans">

    <div>
        {{-- =========================================
            HERO BANNER - TEMA KERAJAAN (BLUE & NAVY)
        ========================================== --}}
        <section class="relative bg-gradient-to-br from-[#071d49] via-[#0c3685] to-[#0b64d4] text-white pt-32 pb-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
            {{-- Decorative Royal Ambient Glows --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#1698f6]/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="container mx-auto max-w-4xl relative z-10 text-center space-y-4">
                <h1 class="text-3xl sm:text-5xl font-heading font-extrabold tracking-tight text-white leading-tight">
                    Katalog Produk & Komisi Kreator
                </h1>
                <p class="text-sm sm:text-base text-slate-200 leading-relaxed font-normal max-w-2xl mx-auto">
                    Pilih produk unggulan dari Brand Partner resmi KERAJAAN. Nikmati kepastian komisi transparan dan ambil materi promosi instan berkualitas tinggi langsung dari Bank Konten.
                </p>
            </div>
        </section>

        {{-- =========================================
            MAIN CONTENT (SEARCH, FILTERS, PRODUCTS)
        ========================================== --}}
        <main class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 -mt-6 relative z-20 pb-20">
            
            {{-- Search & Filter Controls --}}
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-xl shadow-slate-900/5 border border-slate-100 mb-8 space-y-5">
                <form action="{{ route('catalog.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3.5">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    
                    {{-- Search Box --}}
                    <div class="md:col-span-7 relative">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, brand, atau kata kunci..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-[#071d49] placeholder-slate-400 focus:ring-2 focus:ring-[#0b64d4] focus:border-[#0b64d4] focus:outline-none transition-all">
                    </div>

                    {{-- Sort Dropdown --}}
                    <div class="md:col-span-3">
                        <select name="sort" onchange="this.form.submit()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-[#071d49] focus:ring-2 focus:ring-[#0b64d4] focus:border-[#0b64d4] focus:outline-none bg-white transition-all">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                            <option value="commission_high" {{ request('sort') == 'commission_high' ? 'selected' : '' }}>Komisi Tertinggi (Rp)</option>
                            <option value="commission_percent" {{ request('sort') == 'commission_percent' ? 'selected' : '' }}>Komisi % Tertinggi</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </div>

                    {{-- Submit Button --}}
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0b64d4] hover:bg-[#071d49] text-white font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5 shadow-md shadow-blue-600/20">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        @if(request()->hasAny(['search', 'category', 'sort', 'pathway']))
                            <a href="{{ route('catalog.index') }}" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center transition-colors" title="Reset filter">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Category Pills (No horizontal scroll, clean wrap) --}}
                <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100">
                    @if (!request('category'))
                        <a href="{{ route('catalog.index', array_merge(request()->except('category'), [])) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-[#0b64d4] text-white shadow-md shadow-blue-600/20">
                            Semua Kategori
                        </a>
                    @else
                        <a href="{{ route('catalog.index', array_merge(request()->except('category'), [])) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-[#071d49]">
                            Semua Kategori
                        </a>
                    @endif

                    @foreach ($categories as $cat)
                        @if (request('category') == $cat->slug)
                            <a href="{{ route('catalog.index', array_merge(request()->except('category'), ['category' => $cat->slug])) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 bg-[#0b64d4] text-white shadow-md shadow-blue-600/20">
                                <span>{{ $cat->name }}</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-white font-extrabold">
                                    {{ $cat->products_count }}
                                </span>
                            </a>
                        @else
                            <a href="{{ route('catalog.index', array_merge(request()->except('category'), ['category' => $cat->slug])) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-[#071d49]">
                                <span>{{ $cat->name }}</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-200 text-slate-600 font-extrabold">
                                    {{ $cat->products_count }}
                                </span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Active Filters Notice --}}
            @if ($activeCategory)
                <div class="mb-6 flex items-center justify-between bg-blue-50/80 border border-blue-200/80 p-4 rounded-2xl">
                    <div>
                        <strong class="text-sm font-bold text-[#071d49]">Kategori: {{ $activeCategory->name }}</strong>
                        <p class="text-xs text-slate-600 mt-0.5">{{ $activeCategory->description ?: 'Menampilkan seluruh produk dalam kategori ini.' }}</p>
                    </div>
                    <span class="text-xs font-extrabold text-[#0b64d4] bg-white px-3 py-1 rounded-full border border-blue-200 shadow-sm">{{ $products->total() }} Produk Ditemukan</span>
                </div>
            @endif

            {{-- Products Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                        
                        {{-- Product Image & Floating Badges --}}
                        <div class="relative h-56 bg-slate-100 overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-white/95 backdrop-blur-sm text-[11px] font-extrabold text-[#071d49] shadow-sm">
                                {{ $product->category->name ?? 'Kategori Umum' }}
                            </span>

                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[11px] font-black shadow-sm flex items-center gap-1">
                                <i class="bi bi-percent"></i> Komisi {{ number_format($product->locked_commission_percent, 0) }}%
                            </span>

                            @if ($product->promotion_pathway === 'marketplace')
                                <span class="absolute bottom-3 left-3 px-2 py-0.5 rounded bg-[#071d49]/90 text-white text-[10px] font-bold backdrop-blur-sm">
                                    <i class="bi bi-globe2 mr-1 text-[#1698f6]"></i> Open Catalog
                                </span>
                            @elseif ($product->promotion_pathway === 'direct')
                                <span class="absolute bottom-3 left-3 px-2 py-0.5 rounded bg-[#0b64d4]/90 text-white text-[10px] font-bold backdrop-blur-sm">
                                    <i class="bi bi-star-fill mr-1 text-sky-200"></i> Direct Request
                                </span>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-1.5">
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    {{ $product->brand->name ?? 'Brand Partner' }}
                                </div>
                                <h3 class="text-sm font-bold text-[#071d49] group-hover:text-[#0b64d4] transition-colors line-clamp-2 leading-snug">
                                    <a href="{{ route('catalog.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $product->short_description ?: Str::limit($product->description, 90) }}
                                </p>
                            </div>

                            {{-- Price & Locked Commission Highlight (Clean Blue Box) --}}
                            <div class="pt-3 border-t border-slate-100 space-y-2">
                                <div class="flex items-baseline justify-between">
                                    <span class="text-xs text-slate-500">Harga Retail:</span>
                                    <span class="text-sm font-black text-[#071d49]">{{ $product->formatted_price }}</span>
                                </div>

                                <div class="bg-gradient-to-r from-blue-50/90 to-indigo-50/50 border border-blue-100 p-2.5 rounded-xl flex items-center justify-between">
                                    <div class="text-[11px] font-bold text-[#0c3685]">
                                        <span>Komisi Creator:</span>
                                    </div>
                                    <div class="text-xs font-black text-[#0b64d4]">
                                        +{{ $product->formatted_commission }}
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <a href="{{ route('catalog.show', $product->slug) }}" class="py-2.5 text-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-colors">
                                    Detail Produk
                                </a>
                                <a href="{{ route('catalog.content-bank', $product->slug) }}" class="py-2.5 text-center rounded-xl bg-[#0b64d4] hover:bg-[#071d49] text-white font-extrabold text-xs shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-1.5">
                                    <i class="bi bi-download"></i> Bank Konten
                                </a>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200 space-y-4">
                        <div class="w-16 h-16 rounded-full bg-blue-50 text-[#0b64d4] flex items-center justify-center text-3xl mx-auto">
                            <i class="bi bi-box2"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[#071d49]">Tidak ada produk yang cocok dengan pencarian</h3>
                            <p class="text-xs text-slate-500 mt-1">Coba ganti kata kunci atau pilih kategori produk yang lain.</p>
                        </div>
                        <a href="{{ route('catalog.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-[#0b64d4] hover:bg-[#071d49] text-white font-bold text-xs transition-colors shadow-md shadow-blue-600/20">
                            Lihat Semua Produk
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $products->links() }}
            </div>

        </main>
    </div>

    {{-- Shared Site Footer --}}
    @include('partials.landing-footer')

</div>
@endsection
