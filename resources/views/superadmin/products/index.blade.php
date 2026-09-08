@extends('superadmin.layouts.app')

@section('title', 'Katalog Produk & Bank Konten | Superadmin Majapahit Influence')
@section('page-title', 'Katalog Produk & Bank Konten')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#421b13] font-heading">Katalog Produk & Bank Konten</h1>
            <p class="text-xs text-[#765f58] mt-1">Kelola produk e-commerce mitra, penetapan komisi affiliate, dan materi Bank Konten promosi.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('catalog.index') }}" target="_blank" rel="noopener noreferrer" class="btn-majapahit-secondary text-xs">
                <i class="bi bi-eye"></i> Lihat Katalog Publik
            </a>
            <a href="{{ route('superadmin.products.create') }}" class="btn-majapahit-primary text-xs">
                <i class="bi bi-plus-lg"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    {{-- Product Table Card --}}
    <div class="bg-white rounded-2xl border border-[#421b13]/8 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#fbf7f4] border-b border-[#421b13]/8 text-[#765f58] font-heading font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Produk</th>
                        <th class="py-3.5 px-4">Brand & Kategori</th>
                        <th class="py-3.5 px-4">Harga Retail</th>
                        <th class="py-3.5 px-4">Komisi Terkunci</th>
                        <th class="py-3.5 px-4">Bank Konten</th>
                        <th class="py-3.5 px-4">Jalur Promosi</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#421b13]/6">
                    @forelse ($products as $prod)
                        <tr class="hover:bg-[#fff9f4]/60 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $prod->image_path ?: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=100&auto=format&fit=crop&q=80' }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-[#421b13]/8 shrink-0">
                                    <div>
                                        <strong class="text-sm font-bold text-[#421b13] font-heading block leading-snug">{{ $prod->name }}</strong>
                                        <span class="text-[11px] text-[#765f58]">SKU: {{ $prod->sku ?: '-' }} · Stok: {{ $prod->stock }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <strong class="text-[#421b13] font-heading block">{{ $prod->brand->name ?? 'Brand Partner' }}</strong>
                                <span class="px-2 py-0.5 rounded-md bg-[#f7eee8] text-[#421b13] text-[10px] font-bold mt-1 inline-block border border-[#421b13]/10">
                                    {{ $prod->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-[#421b13] font-heading">
                                {{ $prod->formatted_price }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-extrabold text-[11px] border border-emerald-200 block w-max">
                                    {{ number_format($prod->locked_commission_percent, 0) }}% ({{ $prod->formatted_commission }})
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-[#d57028]/10 text-[#d57028] font-bold text-[11px] border border-[#d57028]/20">
                                    <i class="bi bi-folder2-open mr-1"></i> {{ $prod->contentBanks->count() }} Aset
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if ($prod->promotion_pathway === 'both') bg-[#fec200]/20 text-[#b86021] border border-[#fec200]/50
                                    @elseif ($prod->promotion_pathway === 'marketplace') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @else bg-[#d57028]/10 text-[#d57028] border border-[#d57028]/25 @endif">
                                    {{ $prod->promotion_pathway }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('catalog.show', $prod->slug) }}" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-[#f7eee8] hover:bg-[#f7eee8]/80 text-[#421b13] transition" title="Lihat Halaman Katalog">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.products.edit', $prod->id) }}" class="p-2 rounded-lg bg-[#d57028]/10 hover:bg-[#d57028]/20 text-[#d57028] transition" title="Edit Produk">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('superadmin.products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-[#d5282d]/10 hover:bg-[#d5282d]/20 text-[#d5282d] transition" title="Hapus Produk">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#765f58]">
                                <div class="flex size-12 mx-auto items-center justify-center rounded-2xl bg-[#f7eee8] text-[#765f58] mb-3">
                                    <i class="bi bi-box-seam text-xl"></i>
                                </div>
                                <p class="font-medium text-sm">Belum ada produk di katalog.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="p-4 border-t border-[#421b13]/8">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
