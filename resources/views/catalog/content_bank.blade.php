@extends('layouts.app')

@section('title', 'Bank Konten — ' . $product->name . ' — KERAJAAN')

@section('content')

{{-- Shared Top Navbar --}}
@include('partials.landing-header', ['active' => 'catalog'])

<div class="min-h-screen bg-[#f8fafc] text-[#071d49] flex flex-col justify-between font-sans">

    <div>
        {{-- Top Header Banner --}}
        <section class="relative bg-gradient-to-br from-[#071d49] via-[#0c3685] to-[#0b64d4] text-white pt-32 pb-14 px-4 sm:px-6 lg:px-8 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#1698f6]/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="container mx-auto max-w-6xl relative z-10">
                <div class="max-w-2xl space-y-3">
                    <h1 class="text-2xl sm:text-4xl font-heading font-extrabold tracking-tight text-white leading-tight">
                        Bank Konten Promosi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                        Kumpulan materi promosi resmi, foto HD, video mentah, dan teks copywriting yang bebas diunduh untuk kebutuhan promosi konten Anda.
                    </p>
                </div>
            </div>
        </section>

        <main class="container mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 -mt-6 relative z-20 pb-20 space-y-8">
            
            {{-- Quick Product Card Summary --}}
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover shadow-sm border border-slate-100 shrink-0">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $product->category->name ?? 'Kategori' }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-[11px] font-bold text-slate-500">Brand: {{ $product->brand->name ?? 'Partner' }}</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-heading font-extrabold text-[#071d49] leading-tight">{{ $product->name }}</h2>
                        <span class="text-xs text-[#0b64d4] font-bold block">Harga: {{ $product->formatted_price }} &bull; Komisi: {{ $product->formatted_commission }} ({{ number_format($product->locked_commission_percent, 0) }}%)</span>
                    </div>
                </div>

                <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Link Bank Konten berhasil disalin!');" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1.5 transition-colors shrink-0">
                    <i class="bi bi-link-45deg text-base"></i> Salin Link
                </button>
            </div>

            {{-- Content Assets Grid --}}
            <div class="space-y-6">
                <h2 class="text-lg font-heading font-extrabold text-[#071d49] flex items-center gap-2">
                    <i class="bi bi-collection-fill text-[#0b64d4]"></i> Daftar Aset & Materi Promosi
                </h2>

                <div class="grid md:grid-cols-2 gap-6">
                    @forelse ($product->contentBanks as $asset)
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between space-y-4 hover:shadow-md transition-shadow">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                                        @if ($asset->asset_type === 'image') bg-blue-50 text-[#0b64d4] border border-blue-200
                                        @elseif ($asset->asset_type === 'video') bg-purple-50 text-purple-700 border border-purple-200
                                        @elseif ($asset->asset_type === 'copywriting') bg-slate-100 text-slate-700 border border-slate-200
                                        @elseif ($asset->asset_type === 'drive_link') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                        <i class="bi
                                            @if ($asset->asset_type === 'image') bi-images
                                            @elseif ($asset->asset_type === 'video') bi-camera-video
                                            @elseif ($asset->asset_type === 'copywriting') bi-body-text
                                            @elseif ($asset->asset_type === 'drive_link') bi-google
                                            @else bi-file-earmark-pdf @endif mr-1"></i>
                                        {{ strtoupper($asset->asset_type === 'drive_link' ? 'Google Drive Master' : ($asset->asset_type === 'video' ? 'Video B-Roll (GDrive)' : ($asset->asset_type === 'image' ? 'Foto HD (GDrive)' : ($asset->asset_type === 'document' ? 'Dokumen (GDrive)' : 'Copywriting')))) }}
                                    </span>
                                </div>

                                <h3 class="text-base font-bold text-[#071d49]">{{ $asset->title }}</h3>

                                @if ($asset->asset_type === 'copywriting')
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs font-mono text-slate-800 whitespace-pre-line max-h-48 overflow-y-auto">
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
                                    <div class="p-4 bg-blue-50/70 rounded-2xl border border-blue-200 text-xs text-[#071d49] flex items-center gap-3">
                                        <i class="bi bi-images text-2xl text-[#0b64d4] shrink-0"></i>
                                        <div class="truncate">
                                            <strong class="block font-bold">Foto HD & Banner Grafis (Google Drive)</strong>
                                            <span class="text-[11px] text-slate-600">Aset foto produk PNG cutout, lifestyle, dan banner promosi.</span>
                                        </div>
                                    </div>
                                @elseif ($asset->asset_type === 'document')
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-[#071d49] flex items-center gap-3">
                                        <i class="bi bi-file-earmark-pdf text-2xl text-[#0b64d4] shrink-0"></i>
                                        <div class="truncate">
                                            <strong class="block font-bold">Dokumen Panduan & Fact Sheet (Google Drive)</strong>
                                            <span class="text-[11px] text-slate-600">PDF panduan produk, aturan brand, dan do's & don'ts.</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($asset->content_text && $asset->asset_type !== 'copywriting')
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $asset->content_text }}</p>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                @if ($asset->asset_type === 'copywriting')
                                    <button type="button" onclick="navigator.clipboard.writeText(`{{ addslashes($asset->content_text) }}`); alert('Teks copywriting berhasil disalin!');" class="w-full py-2.5 rounded-xl bg-[#071d49] hover:bg-black text-white font-bold text-xs transition-colors flex items-center justify-center gap-2">
                                        <i class="bi bi-clipboard-check"></i> Salin Semua Teks
                                    </button>
                                @elseif (!empty($asset->external_url))
                                    <a href="{{ $asset->external_url }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 rounded-xl bg-[#0b64d4] hover:bg-[#071d49] text-white font-bold text-xs text-center transition-colors flex items-center justify-center gap-2 shadow-md shadow-blue-600/20">
                                        <i class="bi bi-google"></i> Buka di Google Drive
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 bg-white rounded-3xl p-12 text-center border border-slate-200">
                            <p class="text-xs text-slate-500">Belum ada aset promosi yang diunggah untuk produk ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>

    {{-- Shared Site Footer --}}
    @include('partials.landing-footer')

</div>
@endsection
