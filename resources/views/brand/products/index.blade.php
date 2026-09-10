@extends('brand.layouts.app')

@section('title', 'Katalog Produk')
@section('page-title', 'Katalog Produk')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-[#421b13] font-heading">Katalog Produk Brand</h1>
        <p class="mt-1 text-sm text-[#765f58]">Kelola produk yang didaftarkan untuk kampanye endorsement dan bank konten.</p>
    </div>
    <a href="{{ route('brand.products.create') }}" class="btn-majapahit-primary self-start sm:self-auto">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Produk Baru</span>
    </a>
</div>

<div class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="border-b border-[#421b13]/6 bg-[#fff9f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                <tr>
                    <th class="px-5 py-4 sm:px-6">Produk</th>
                    <th class="px-5 py-4">Harga Jual</th>
                    <th class="px-5 py-4">Komisi KOL</th>
                    <th class="px-5 py-4">Status Verifikasi</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#421b13]/6">
                @forelse($products as $product)
                <tr class="group hover:bg-[#fff9f4]/60 transition">
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3.5">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-11 rounded-xl object-cover border border-[#421b13]/10 bg-[#f7eee8]">
                            <div>
                                <span class="font-bold text-[#421b13] group-hover:text-[#d57028] transition font-heading block">
                                    {{ $product->name }}
                                </span>
                                <span class="text-xs text-[#765f58]">{{ $product->category->name ?? 'Kategori Umum' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-[#421b13]">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-xs font-semibold text-[#d57028]">
                        {{ $product->locked_commission_percent ? $product->locked_commission_percent.'%' : '40%' }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        @if($product->verification_status === 'approved')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                <i class="bi bi-check-circle-fill text-[11px]"></i> Disetujui
                            </span>
                        @elseif($product->verification_status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200" title="{{ $product->rejection_reason }}">
                                <i class="bi bi-x-circle-fill text-[11px]"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 border border-amber-200">
                                <i class="bi bi-clock-fill text-[11px]"></i> Menunggu Review
                            </span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('brand.products.edit', $product) }}" class="rounded-lg p-2 text-[#765f58] hover:bg-[#fff9f4] hover:text-[#d57028] transition" title="Edit Produk">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('brand.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg p-2 text-[#765f58] hover:bg-rose-50 hover:text-rose-600 transition" title="Hapus Produk">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-[#765f58]">
                        <div class="flex size-14 mx-auto items-center justify-center rounded-2xl bg-[#fff9f4] text-[#d57028] mb-3">
                            <i class="bi bi-box-seam text-2xl"></i>
                        </div>
                        <p class="font-bold text-[#421b13]">Belum ada produk yang didaftarkan</p>
                        <p class="mt-1 text-xs text-[#765f58]">Tambahkan produk brand Anda agar dapat ditinjau dan dipromosikan oleh KOL.</p>
                        <a href="{{ route('brand.products.create') }}" class="btn-majapahit-primary mt-4 text-xs">
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Produk Pertama</span>
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div class="border-t border-[#421b13]/6 p-5 sm:px-6">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
