@extends('layouts.app')

@section('title', $product->name . ' | Detail & Bank Konten')

@section('content')
<div class="min-h-screen bg-slate-50/70 pb-20">
    
    {{-- Breadcrumb Navigation --}}
    <div class="bg-white border-b border-slate-100 py-4 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-7xl flex items-center justify-between text-xs text-slate-500">
            <div class="flex items-center gap-2 overflow-x-auto">
                <a href="{{ url('/') }}" class="hover:text-[#0b64d4]">Beranda</a>
                <i class="bi bi-chevron-right text-[10px]"></i>
                <a href="{{ route('catalog.index') }}" class="hover:text-[#0b64d4]">Katalog E-Commerce</a>
                <i class="bi bi-chevron-right text-[10px]"></i>
                <span class="text-slate-800 font-bold truncate max-w-xs">{{ $product->name }}</span>
            </div>

            <a href="{{ route('catalog.content-bank', $product->slug) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-[#0b64d4] font-bold hover:bg-blue-100 transition-colors">
                <i class="bi bi-folder2-open"></i> Buka Bank Konten Lengkap
            </a>
        </div>
    </div>

    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-8">
        
        <div class="grid lg:grid-cols-12 gap-8">
            
            {{-- Left Column: Product Image & Highlights --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-80 sm:h-96 object-cover rounded-2xl">
                </div>

                {{-- Promotion Pathway Badge Box --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 space-y-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Skema Distribusi Promosi:</span>
                    @if ($product->promotion_pathway === 'both')
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-200">
                            <i class="bi bi-stars text-[#0b64d4] text-2xl"></i>
                            <div>
                                <strong class="block text-xs font-bold text-[#0c3685]">Jalur Terbuka (Direct & Marketplace)</strong>
                                <span class="text-[11px] text-slate-600">Dapat dipromosikan oleh semua kreator bebas atau melalui penugasan eksklusif dari Brand.</span>
                            </div>
                        </div>
                    @elseif ($product->promotion_pathway === 'marketplace')
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl border border-green-200">
                            <i class="bi bi-globe2 text-green-600 text-2xl"></i>
                            <div>
                                <strong class="block text-xs font-bold text-green-900">Jalur Marketplace Otomatis</strong>
                                <span class="text-[11px] text-green-700">Semua affiliate dan KOL dapat langsung mengambil bahan konten dan mempromosikannya.</span>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-200">
                            <i class="bi bi-person-check-fill text-blue-600 text-2xl"></i>
                            <div>
                                <strong class="block text-xs font-bold text-blue-900">Jalur Direct Selection</strong>
                                <span class="text-[11px] text-blue-700">Penugasan khusus dari Brand partner.</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Details, Locked Commission Box & Bank Konten --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- Product Title & Price Card --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-5">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-800 text-xs font-bold">
                                {{ $product->category->name ?? 'Kategori Umum' }}
                            </span>
                            <span class="text-xs font-bold text-gray-500">
                                Brand: <strong class="text-gray-900">{{ $product->brand->name ?? 'Partner' }}</strong>
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 leading-tight">
                            {{ $product->name }}
                        </h1>
                    </div>

                    {{-- Transparent Locked Commission Box --}}
                    <div class="bg-gradient-to-br from-[#0c3685] to-[#0b64d4] rounded-2xl p-5 text-white shadow-lg shadow-[#0b64d4]/20 space-y-3 font-heading">
                        <div class="flex items-center justify-between pb-3 border-b border-blue-400/30">
                            <div>
                                <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider block">Harga Retail Produk</span>
                                <strong class="text-2xl font-black">{{ $product->formatted_price }}</strong>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider block">Komisi Tetap Anda</span>
                                <strong class="text-2xl font-black text-[#78a5d6]">+{{ $product->formatted_commission }}</strong>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-blue-100 font-sans">
                            <span class="flex items-center gap-1.5 font-bold">
                                <i class="bi bi-shield-lock-fill text-[#78a5d6]"></i> Terkunci {{ number_format($product->locked_commission_percent, 0) }}% Transparan
                            </span>
                            <span>Stok Siap: {{ $product->stock }} pcs</span>
                        </div>
                    </div>

                    {{-- Short Description & Specs --}}
                    <div class="space-y-3 pt-2">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 font-heading">Deskripsi & Keunggulan Produk (USP)</h3>
                        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line font-sans">
                            {{ $product->description ?: $product->short_description }}
                        </p>
                    </div>

                    {{-- Action CTA --}}
                    <div class="pt-4 flex flex-wrap gap-3 font-heading">
                        <a href="{{ route('catalog.content-bank', $product->slug) }}" class="flex-1 py-3.5 px-6 rounded-2xl bg-[#0b64d4] hover:bg-[#0c3685] text-white font-extrabold text-sm shadow-md shadow-[#0b64d4]/25 flex items-center justify-center gap-2 transition-all">
                            <i class="bi bi-download text-lg"></i> Buka & Unduh Bank Konten
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Link produk berhasil disalin!');" class="py-3.5 px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-colors flex items-center gap-1.5">
                            <i class="bi bi-share-fill"></i> Bagikan Link
                        </button>
                    </div>
                </div>

                {{-- Bank Konten Preview Cards on Detail Page --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-black text-[#0c3685] uppercase tracking-wider block font-heading">Repository Materi Promosi</span>
                            <h2 class="text-xl font-bold text-slate-900 font-heading">Bank Konten Siap Pakai</h2>
                        </div>
                        <a href="{{ route('catalog.content-bank', $product->slug) }}" class="text-xs font-bold text-[#0b64d4] hover:underline font-heading">
                            Lihat Semua Aset <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($product->contentBanks as $asset)
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0b64d4] flex items-center justify-center text-lg font-bold flex-shrink-0">
                                        @if ($asset->asset_type === 'image')
                                            <i class="bi bi-image"></i>
                                        @elseif ($asset->asset_type === 'video')
                                            <i class="bi bi-camera-video"></i>
                                        @elseif ($asset->asset_type === 'copywriting')
                                            <i class="bi bi-file-text"></i>
                                        @elseif ($asset->asset_type === 'drive_link')
                                            <i class="bi bi-google"></i>
                                        @else
                                            <i class="bi bi-file-earmark"></i>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="text-sm font-bold text-slate-900 font-heading">{{ $asset->title }}</h3>
                                        <p class="text-xs text-slate-500 line-clamp-2">{{ $asset->content_text }}</p>
                                    </div>
                                </div>

                                <div class="flex-shrink-0">
                                    @if ($asset->asset_type === 'copywriting')
                                        <button type="button" onclick="navigator.clipboard.writeText(`{{ addslashes($asset->content_text) }}`); alert('Script copywriting berhasil disalin!');" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-black transition-colors flex items-center gap-1.5 font-heading">
                                            <i class="bi bi-clipboard"></i> Salin Script
                                        </button>
                                    @elseif ($asset->asset_type === 'drive_link' || !empty($asset->external_url))
                                        <a href="{{ $asset->external_url }}" target="_blank" class="px-4 py-2 rounded-xl bg-[#0b64d4] text-white font-bold text-xs hover:bg-[#0c3685] transition-colors inline-flex items-center gap-1.5 shadow-xs shadow-[#0b64d4]/20 font-heading">
                                            <i class="bi bi-box-arrow-up-right"></i> Buka Folder
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 italic">Materi Bank Konten sedang disiapkan oleh Brand.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
