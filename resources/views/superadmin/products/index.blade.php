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
                        <th class="py-3.5 px-4">Bank Konten (GDrive)</th>
                        <th class="py-3.5 px-4 text-center">Status E-Commerce</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#421b13]/6">
                    @forelse ($products as $prod)
                        @php
                            $hasDrive = $prod->contentBanks->whereNotNull('external_url')->isNotEmpty();
                        @endphp
                        <tr class="hover:bg-[#fff9f4]/60 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-[#421b13]/8 shrink-0">
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
                                @if($hasDrive)
                                    <a href="{{ route('superadmin.products.edit', $prod->id) }}#bank-konten" class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-[11px] border border-emerald-200 inline-flex items-center gap-1 transition" title="Bank Konten Google Drive Tersambung">
                                        <i class="bi bi-google text-emerald-600"></i> Ada GDrive ({{ $prod->contentBanks->count() }})
                                    </a>
                                @else
                                    <a href="{{ route('superadmin.products.edit', $prod->id) }}#bank-konten" class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-[11px] border border-amber-300 inline-flex items-center gap-1 transition" title="Belum Ada Bank Konten GDrive">
                                        <i class="bi bi-exclamation-circle text-amber-600"></i> Belum Ada GDrive
                                    </a>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    @if ($prod->is_active)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                            <i class="bi bi-check-circle-fill text-emerald-600"></i> Tayang di E-Commerce
                                        </span>
                                        <form action="{{ route('superadmin.products.toggle-publish', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Tarik produk ini dari katalog E-Commerce publik?');">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-[#765f58] hover:text-[#d5282d] font-bold underline transition" title="Tarik produk agar tidak muncul di katalog">
                                                Tarik (Unpublish)
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 inline-flex items-center gap-1">
                                            <i class="bi bi-dash-circle"></i> Draft / Belum Tayang
                                        </span>
                                        @if($hasDrive)
                                            <form action="{{ route('superadmin.products.toggle-publish', $prod->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] shadow-xs inline-flex items-center gap-1 transition font-heading">
                                                    <i class="bi bi-cloud-upload"></i> Publish ke E-Commerce
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('superadmin.products.edit', $prod->id) }}#bank-konten" class="px-2.5 py-1 rounded-lg bg-[#d57028]/10 hover:bg-[#d57028]/20 text-[#d57028] font-bold text-[10px] inline-flex items-center gap-1 transition font-heading" title="Lengkapi Google Drive Bank Konten terlebih dahulu untuk mempublikasikan">
                                                <i class="bi bi-plus-circle"></i> Isi GDrive Dulu
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('catalog.show', $prod->slug) }}" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-[#f7eee8] hover:bg-[#f7eee8]/80 text-[#421b13] transition" title="Lihat Halaman Katalog">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.products.edit', $prod->id) }}" class="p-2 rounded-lg bg-[#d57028]/10 hover:bg-[#d57028]/20 text-[#d57028] transition" title="Edit Produk & Bank Konten">
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
