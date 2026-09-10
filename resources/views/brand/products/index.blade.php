@extends('brand.layouts.app')

@section('title', 'Katalog Produk')
@section('page-title', 'Katalog Produk')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-[#071d49] font-heading">Katalog Produk Brand</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola produk yang didaftarkan untuk kampanye endorsement dan bank konten.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('brand.notifications.index') }}" class="btn-kerajaan-secondary text-xs">
            <i class="bi bi-bell-fill"></i>
            <span>Pusat Notifikasi</span>
        </a>
        <a href="{{ route('brand.products.create') }}" class="btn-kerajaan-primary text-xs">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Produk Baru</span>
        </a>
    </div>
</div>

{{-- Product Rejection Alert Notice if any --}}
@php
    $rejectedProducts = $products->filter(fn($p) => $p->verification_status === 'rejected');
@endphp
@if ($rejectedProducts->isNotEmpty())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/70 p-4 shadow-xs">
        <div class="flex items-start gap-3">
            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                <i class="bi bi-info-circle-fill text-lg"></i>
            </span>
            <div class="min-w-0 flex-1">
                <h4 class="text-xs font-extrabold text-rose-900 font-heading">Pemberitahuan Penolakan Produk</h4>
                <p class="mt-0.5 text-xs text-rose-700">Terdapat {{ $rejectedProducts->count() }} produk yang belum disetujui oleh Superadmin:</p>
                <div class="mt-2.5 space-y-2">
                    @foreach ($rejectedProducts as $rejected)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 rounded-xl bg-white p-3 border border-rose-200/80 text-xs">
                            <div>
                                <strong class="text-slate-900">{{ $rejected->name }}</strong>: 
                                <span class="text-rose-700 italic">"{{ $rejected->rejection_reason ?: 'Periksa kembali kelengkapan foto dan deskripsi produk.' }}"</span>
                            </div>
                            <a href="{{ route('brand.products.edit', $rejected) }}" class="inline-flex items-center gap-1 font-bold text-[#0b64d4] hover:text-[#071d49] text-xs shrink-0 font-heading">
                                <i class="bi bi-pencil"></i> Perbaiki Data
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-heading">
                <tr>
                    <th class="px-5 py-4 sm:px-6">Produk</th>
                    <th class="px-5 py-4">Harga Jual</th>
                    <th class="px-5 py-4">Komisi KOL</th>
                    <th class="px-5 py-4">Status Verifikasi</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="group hover:bg-slate-50/70 transition">
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3.5">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-11 rounded-xl object-cover border border-slate-200 bg-slate-50">
                            <div>
                                <a href="{{ route('brand.products.show', $product) }}" class="font-bold text-[#071d49] group-hover:text-[#0b64d4] transition font-heading block">
                                    {{ $product->name }}
                                </a>
                                <span class="text-xs text-slate-500">{{ $product->category->name ?? 'Kategori Umum' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-[#071d49]">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-xs font-semibold text-[#0b64d4]">
                        {{ $product->locked_commission_percent ? $product->locked_commission_percent.'%' : '40%' }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        @if($product->verification_status === 'approved')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                <i class="bi bi-check-circle-fill text-[11px]"></i> Disetujui
                            </span>
                        @elseif($product->verification_status === 'pending_update')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-200" title="Perubahan data produk sedang menunggu persetujuan Superadmin.">
                                <i class="bi bi-hourglass-split text-[11px]"></i> Review Edit
                            </span>
                        @elseif($product->verification_status === 'pending_delete')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200" title="Pengajuan penghapusan produk sedang menunggu persetujuan Superadmin.">
                                <i class="bi bi-trash-fill text-[11px]"></i> Review Hapus
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
                            <a href="{{ route('brand.products.show', $product) }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-[#0b64d4] transition" title="Lihat Detail Produk">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($product->verification_status !== 'pending_delete')
                                <a href="{{ route('brand.products.edit', $product) }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-[#0b64d4] transition" title="Edit Data Produk">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button"
                                    onclick="openDeleteModal('{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ route('brand.products.destroy', $product) }}')"
                                    class="rounded-lg p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition cursor-pointer"
                                    title="Ajukan Hapus Produk">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @else
                                <span class="text-[11px] text-slate-400 italic">Menunggu Hapus</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500">
                        <div class="flex size-14 mx-auto items-center justify-center rounded-2xl bg-slate-50 text-[#0b64d4] mb-3">
                            <i class="bi bi-box-seam text-2xl"></i>
                        </div>
                        <p class="font-bold text-[#071d49]">Belum ada produk yang didaftarkan</p>
                        <p class="mt-1 text-xs text-slate-500">Tambahkan produk brand Anda agar dapat ditinjau dan dipromosikan oleh KOL.</p>
                        <a href="{{ route('brand.products.create') }}" class="btn-kerajaan-primary mt-4 text-xs">
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
    <div class="border-t border-slate-100 p-5 sm:px-6">
        {{ $products->links() }}
    </div>
    @endif
</div>

{{-- MODAL POPUP PENGAJUAN HAPUS PRODUK --}}
<div id="delete-product-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-[#071d49]/60 backdrop-blur-xs transition-all" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200/80">
        {{-- Modal Header --}}
        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100">
                <i class="bi bi-trash text-lg"></i>
            </span>
            <div>
                <h3 class="text-base font-extrabold text-[#071d49] font-heading">
                    Pengajuan Hapus Produk
                </h3>
                <p class="text-[11px] text-slate-500">Memerlukan persetujuan dari Superadmin</p>
            </div>
        </div>

        {{-- Modal Body --}}
        <form id="delete-product-form" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            @method('DELETE')

            <div>
                <label class="block text-xs font-bold text-slate-700 font-heading">Produk yang akan dihapus:</label>
                <div class="mt-1.5 rounded-xl bg-slate-50 p-3 border border-slate-200">
                    <p id="modal-product-name" class="text-xs font-bold text-[#071d49] truncate"></p>
                </div>
            </div>

            <div>
                <label for="modal-deletion-reason" class="block text-xs font-bold text-[#071d49] font-heading">
                    Alasan Penghapusan <span class="text-rose-500">*</span>
                </label>
                <textarea
                    name="deletion_reason"
                    id="modal-deletion-reason"
                    required
                    rows="3"
                    placeholder="Tuliskan alasan penghapusan produk untuk Superadmin (contoh: Produk sudah tidak diproduksi lagi / stok habis total)..."
                    class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white p-3 text-xs text-slate-800 placeholder-slate-400 focus:border-[#0b64d4] focus:ring-2 focus:ring-[#0b64d4]/20 focus:outline-hidden transition leading-relaxed"></textarea>
                <p class="mt-1 text-[10px] text-slate-400">Alasan ini akan ditinjau oleh Superadmin sebelum produk dihapus secara permanen.</p>
            </div>

            {{-- Modal Actions --}}
            <div class="mt-6 flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeDeleteModal()" class="btn-kerajaan-secondary text-xs px-4 py-2">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500">
                    <i class="bi bi-trash-fill"></i>
                    <span>Kirim Pengajuan Hapus</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(productId, productName, actionUrl) {
        const modal = document.getElementById('delete-product-modal');
        const form = document.getElementById('delete-product-form');
        const nameEl = document.getElementById('modal-product-name');
        const reasonInput = document.getElementById('modal-deletion-reason');

        if (modal && form && nameEl) {
            form.action = actionUrl;
            nameEl.textContent = productName;
            if (reasonInput) reasonInput.value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');

            setTimeout(() => {
                reasonInput?.focus();
            }, 100);
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-product-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Close on click backdrop
    document.getElementById('delete-product-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endpush

@endsection
