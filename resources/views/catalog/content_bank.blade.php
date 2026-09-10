@extends('layouts.app')

@section('title', 'Bank Konten — ' . $product->name)

@section('content')
<div class="min-h-screen bg-gray-50/70 pb-20">
    
    {{-- Top Header --}}
    <div class="bg-gradient-to-r from-gray-900 via-gray-900 to-amber-950 text-white py-10 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-6xl flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold">
                    <i class="bi bi-folder-symlink-fill"></i> BANK KONTEN BRAND RESMI
                </div>
                <h1 class="text-2xl sm:text-3xl font-black">{{ $product->name }}</h1>
                <p class="text-xs sm:text-sm text-gray-300">
                    Materi promosi resmi dari Brand <strong class="text-amber-400">{{ $product->brand->name ?? 'Partner' }}</strong>. Bebas diunduh dan digunakan untuk konten promosi Anda.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('catalog.show', $product->slug) }}" class="px-4 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-bold transition-colors">
                    <i class="bi bi-arrow-left mr-1"></i> Kembali ke Produk
                </a>
                <a href="{{ route('catalog.index') }}" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition-colors">
                    <i class="bi bi-shop mr-1"></i> Katalog
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 mt-8 space-y-8">
        
        {{-- Quick Product Card Summary --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                <div>
                    <span class="text-xs font-bold text-gray-400 block">{{ $product->category->name ?? 'Kategori' }}</span>
                    <h3 class="text-base font-black text-gray-900">{{ $product->name }}</h3>
                    <span class="text-xs text-amber-700 font-extrabold">Harga: {{ $product->formatted_price }} | Komisi Anda: {{ $product->formatted_commission }} ({{ number_format($product->locked_commission_percent, 0) }}%)</span>
                </div>
            </div>

            <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Link Bank Konten berhasil disalin!');" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs flex items-center gap-1.5 transition-colors">
                <i class="bi bi-link-45deg text-base"></i> Salin Link Bank Konten
            </button>
        </div>

        {{-- Content Assets Grid --}}
        <div class="space-y-6">
            <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                <i class="bi bi-collection-fill text-amber-600"></i> Daftar Aset & Materi Promosi
            </h2>

            <div class="grid md:grid-cols-2 gap-6">
                @forelse ($product->contentBanks as $asset)
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                                    @if ($asset->asset_type === 'image') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif ($asset->asset_type === 'video') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif ($asset->asset_type === 'copywriting') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif ($asset->asset_type === 'drive_link') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @else bg-gray-100 text-gray-700 border border-gray-200 @endif">
                                    <i class="bi
                                        @if ($asset->asset_type === 'image') bi-images
                                        @elseif ($asset->asset_type === 'video') bi-camera-video
                                        @elseif ($asset->asset_type === 'copywriting') bi-body-text
                                        @elseif ($asset->asset_type === 'drive_link') bi-google
                                        @else bi-file-earmark-pdf @endif mr-1"></i>
                                    {{ strtoupper($asset->asset_type === 'drive_link' ? 'Google Drive Master' : ($asset->asset_type === 'video' ? 'Video B-Roll (GDrive)' : ($asset->asset_type === 'image' ? 'Foto HD (GDrive)' : ($asset->asset_type === 'document' ? 'Dokumen (GDrive)' : 'Copywriting')))) }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-gray-900">{{ $asset->title }}</h3>

                            @if ($asset->asset_type === 'copywriting')
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 text-xs font-mono text-gray-800 whitespace-pre-line max-h-48 overflow-y-auto">
                                    {{ $asset->content_text }}
                                </div>
                            @elseif ($asset->asset_type === 'drive_link')
                                <div class="p-4 bg-emerald-50/70 rounded-2xl border border-emerald-200 text-xs text-emerald-900 flex items-center gap-3">
                                    <i class="bi bi-google text-2xl text-emerald-700 shrink-0"></i>
                                    <div class="truncate">
                                        <strong class="block font-bold">Folder Master Google Drive</strong>
                                        <span class="text-[11px] text-emerald-700">Akses seluruh materi master foto HD, video mentah, dan grafis resolusi tinggi.</span>
                                    </div>
                                </div>
                            @elseif ($asset->asset_type === 'video')
                                <div class="p-4 bg-purple-50/70 rounded-2xl border border-purple-200 text-xs text-purple-900 flex items-center gap-3">
                                    <i class="bi bi-camera-video text-2xl text-purple-700 shrink-0"></i>
                                    <div class="truncate">
                                        <strong class="block font-bold">Video B-Roll & Footage (Google Drive)</strong>
                                        <span class="text-[11px] text-purple-700">Download video mentah unboxing, swatch, dan pemakaian.</span>
                                    </div>
                                </div>
                            @elseif ($asset->asset_type === 'image')
                                <div class="p-4 bg-blue-50/70 rounded-2xl border border-blue-200 text-xs text-blue-900 flex items-center gap-3">
                                    <i class="bi bi-images text-2xl text-blue-700 shrink-0"></i>
                                    <div class="truncate">
                                        <strong class="block font-bold">Foto HD & Banner Grafis (Google Drive)</strong>
                                        <span class="text-[11px] text-blue-700">Aset foto produk PNG cutout, lifestyle, dan banner promosi.</span>
                                    </div>
                                </div>
                            @elseif ($asset->asset_type === 'document')
                                <div class="p-4 bg-amber-50/70 rounded-2xl border border-amber-200 text-xs text-amber-900 flex items-center gap-3">
                                    <i class="bi bi-file-earmark-pdf text-2xl text-amber-700 shrink-0"></i>
                                    <div class="truncate">
                                        <strong class="block font-bold">Dokumen Panduan & Fact Sheet (Google Drive)</strong>
                                        <span class="text-[11px] text-amber-700">PDF panduan produk, aturan brand, dan do's & don'ts.</span>
                                    </div>
                                </div>
                            @endif

                            @if ($asset->content_text && $asset->asset_type !== 'copywriting')
                                <p class="text-xs text-gray-600 leading-relaxed">{{ $asset->content_text }}</p>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            @if ($asset->asset_type === 'copywriting')
                                <button type="button" onclick="navigator.clipboard.writeText(`{{ addslashes($asset->content_text) }}`); alert('Teks copywriting berhasil disalin!');" class="w-full py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white font-bold text-xs transition-colors flex items-center justify-center gap-2">
                                    <i class="bi bi-clipboard-check"></i> Salin Semua Teks
                                </button>
                            @elseif (!empty($asset->external_url))
                                <a href="{{ $asset->external_url }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs text-center transition-colors flex items-center justify-center gap-2">
                                    <i class="bi bi-google"></i> Buka di Google Drive
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white rounded-3xl p-12 text-center border border-gray-200">
                        <p class="text-xs text-gray-500">Belum ada aset promosi yang diunggah untuk produk ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
