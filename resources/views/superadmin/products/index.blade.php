@extends('superadmin.layouts.app')

@section('title', 'Katalog Produk & Bank Konten — Superadmin')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Katalog Produk & Bank Konten</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola produk e-commerce, penetapan komisi terkunci 40%, dan materi Bank Konten.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('catalog.index') }}" target="_blank" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-xl transition-colors inline-flex items-center gap-1.5">
                <i class="bi bi-eye"></i> Lihat Katalog Publik
            </a>
            <a href="{{ route('superadmin.products.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all inline-flex items-center gap-1.5">
                <i class="bi bi-plus-lg"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    {{-- Flash Alerts --}}
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-xs font-bold flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-base text-green-600"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Product Table Card --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Produk</th>
                        <th class="py-3.5 px-4">Brand & Kategori</th>
                        <th class="py-3.5 px-4">Harga Retail</th>
                        <th class="py-3.5 px-4">Komisi Locked</th>
                        <th class="py-3.5 px-4">Bank Konten</th>
                        <th class="py-3.5 px-4">Jalur Promosi</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $prod)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $prod->image_path ?: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=100&auto=format&fit=crop&q=80' }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover shadow-sm flex-shrink-0">
                                    <div>
                                        <strong class="text-sm font-bold text-gray-900 block leading-snug">{{ $prod->name }}</strong>
                                        <span class="text-[11px] text-gray-400">SKU: {{ $prod->sku ?: '-' }} | Stok: {{ $prod->stock }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <strong class="text-gray-900 block">{{ $prod->brand->name ?? 'Brand Partner' }}</strong>
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-bold mt-1 inline-block">
                                    {{ $prod->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-black text-gray-900">
                                {{ $prod->formatted_price }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-green-50 text-green-700 font-extrabold text-[11px] border border-green-200 block w-max">
                                    {{ number_format($prod->locked_commission_percent, 0) }}% ({{ $prod->formatted_commission }})
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-1 rounded-lg bg-amber-50 text-amber-800 font-bold text-[11px]">
                                    <i class="bi bi-folder2-open mr-1"></i> {{ $prod->contentBanks->count() }} Aset
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                                    @if ($prod->promotion_pathway === 'both') bg-purple-50 text-purple-700
                                    @elseif ($prod->promotion_pathway === 'marketplace') bg-green-50 text-green-700
                                    @else bg-blue-50 text-blue-700 @endif">
                                    {{ $prod->promotion_pathway }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('catalog.show', $prod->slug) }}" target="_blank" class="p-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors" title="Lihat Halaman">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.products.edit', $prod->id) }}" class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 transition-colors" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('superadmin.products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 transition-colors" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Belum ada produk di katalog.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
