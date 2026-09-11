@extends('superadmin.layouts.app')

@section('title', 'Katalog Produk & Bank Konten | Superadmin kerajaan Influence')
@section('page-title', 'Katalog Produk & Bank Konten')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-kerajaan-dark font-heading">Katalog Produk & Bank Konten</h1>
            <p class="text-xs text-kerajaan-muted mt-1">Kelola produk e-commerce mitra, penetapan komisi affiliate, dan materi Bank Konten promosi.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('catalog.index') }}" target="_blank" rel="noopener noreferrer" class="btn-kerajaan-secondary text-xs">
                <i class="bi bi-eye"></i> Lihat Katalog Publik
            </a>
            <a href="{{ route('superadmin.products.create') }}" class="btn-kerajaan-primary text-xs">
                <i class="bi bi-plus-lg"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    {{-- Product Table Card --}}
    <div class="bg-white rounded-2xl border border-kerajaan-dark/8 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-kerajaan-cream border-b border-kerajaan-dark/8 text-kerajaan-muted font-heading font-bold uppercase tracking-wider text-[11px]">
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
                <tbody class="divide-y divide-kerajaan-dark/6">
                    @forelse ($products as $prod)
                        @php
                            $hasDrive = $prod->contentBanks->whereNotNull('external_url')->isNotEmpty();
                        @endphp
                        <tr class="hover:bg-kerajaan-cream/60 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover shadow-xs border border-kerajaan-dark/8 shrink-0">
                                    <div>
                                        <strong class="text-sm font-bold text-kerajaan-dark font-heading block leading-snug">{{ $prod->name }}</strong>
                                        <span class="text-[11px] text-kerajaan-muted">SKU: {{ $prod->sku ?: '-' }} · Stok: {{ $prod->stock }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <strong class="text-kerajaan-dark font-heading block">{{ $prod->brand->name ?? 'Brand Partner' }}</strong>
                                <span class="px-2 py-0.5 rounded-md bg-kerajaan-sand text-kerajaan-dark text-[10px] font-bold mt-1 inline-block border border-kerajaan-dark/10">
                                    {{ $prod->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-kerajaan-dark font-heading">
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
                                        <button type="button" 
                                            onclick="openUnpublishModal('{{ $prod->id }}', '{{ addslashes($prod->name) }}')"
                                            class="text-[10px] text-slate-500 hover:text-rose-600 font-bold underline transition" 
                                            title="Tarik produk agar tidak muncul di katalog">
                                            Tarik (Unpublish)
                                        </button>
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
                                            <a href="{{ route('superadmin.products.edit', $prod->id) }}#bank-konten" class="px-2.5 py-1 rounded-lg bg-[#0b64d4]/10 hover:bg-[#0b64d4]/20 text-[#0b64d4] font-bold text-[10px] inline-flex items-center gap-1 transition font-heading" title="Lengkapi Google Drive Bank Konten terlebih dahulu untuk mempublikasikan">
                                                <i class="bi bi-plus-circle"></i> Isi GDrive Dulu
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('catalog.show', $prod->slug) }}" target="_blank" rel="noopener noreferrer" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Lihat Halaman Katalog">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.products.edit', $prod->id) }}" class="p-2 rounded-lg bg-[#0b64d4]/10 hover:bg-[#0b64d4]/20 text-[#0b64d4] transition" title="Edit Produk & Bank Konten">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                        onclick="openDeleteModal('{{ $prod->id }}', '{{ addslashes($prod->name) }}')"
                                        class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" 
                                        title="Hapus Produk">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-kerajaan-muted">
                                <div class="flex size-12 mx-auto items-center justify-center rounded-2xl bg-kerajaan-sand text-kerajaan-muted mb-3">
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
            <div class="p-4 border-t border-kerajaan-dark/8">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>

{{-- MODAL HAPUS PRODUK --}}
<div id="modal-delete-product" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200" role="dialog" aria-modal="true">
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-rose-100">
        <button type="button" onclick="closeDeleteModal()" class="absolute right-5 top-5 inline-flex size-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
            <i class="bi bi-x-lg text-sm"></i>
        </button>

        <div class="flex items-center gap-3.5 mb-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-200">
                <i class="bi bi-trash3 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-[#071d49] font-heading">Hapus Produk</h3>
                <p class="text-xs text-[#64748b]">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>

        <div class="mb-4 rounded-2xl bg-rose-50/60 border border-rose-200/80 p-3.5 text-xs text-rose-900">
            <span class="text-rose-700 block text-[11px] font-medium">Produk yang akan dihapus:</span>
            <strong id="delete-modal-product-name" class="text-rose-950 font-bold text-sm block mt-0.5">-</strong>
            <p class="mt-1 text-xs text-rose-800">Produk ini akan dihapus secara permanen dari sistem.</p>
        </div>

        <form id="delete-product-form" method="POST" action="" class="space-y-4">
            @csrf
            @method('DELETE')

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition font-heading">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition font-heading">
                    <i class="bi bi-trash-fill"></i>
                    <span>Ya, Hapus Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL UNPUBLISH PRODUK --}}
<div id="modal-unpublish-product" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200" role="dialog" aria-modal="true">
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-amber-100">
        <button type="button" onclick="closeUnpublishModal()" class="absolute right-5 top-5 inline-flex size-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
            <i class="bi bi-x-lg text-sm"></i>
        </button>

        <div class="flex items-center gap-3.5 mb-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 border border-amber-200">
                <i class="bi bi-eye-slash text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-[#071d49] font-heading">Tarik dari E-Commerce</h3>
                <p class="text-xs text-[#64748b]">Sembunyikan produk dari katalog publik.</p>
            </div>
        </div>

        <div class="mb-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 p-3.5 text-xs text-amber-900">
            <span class="text-amber-700 block text-[11px] font-medium">Produk:</span>
            <strong id="unpublish-modal-product-name" class="text-amber-950 font-bold text-sm block mt-0.5">-</strong>
            <p class="mt-1 text-xs text-amber-800">Produk ini akan berstatus draft dan tidak dapat dilihat pengunjung di katalog publik.</p>
        </div>

        <form id="unpublish-product-form" method="POST" action="" class="space-y-4">
            @csrf

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeUnpublishModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition font-heading">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-amber-700 transition font-heading">
                    <i class="bi bi-eye-slash-fill"></i>
                    <span>Ya, Tarik Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const baseProductUrl = "{{ url('superadmin/products') }}";

    function openDeleteModal(productId, productName) {
        const modal = document.getElementById('modal-delete-product');
        const form = document.getElementById('delete-product-form');
        const nameEl = document.getElementById('delete-modal-product-name');

        form.action = `${baseProductUrl}/${productId}`;
        nameEl.textContent = productName;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modal-delete-product');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function openUnpublishModal(productId, productName) {
        const modal = document.getElementById('modal-unpublish-product');
        const form = document.getElementById('unpublish-product-form');
        const nameEl = document.getElementById('unpublish-modal-product-name');

        form.action = `${baseProductUrl}/${productId}/toggle-publish`;
        nameEl.textContent = productName;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeUnpublishModal() {
        const modal = document.getElementById('modal-unpublish-product');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['modal-delete-product', 'modal-unpublish-product'].forEach(id => {
            const modal = document.getElementById(id);
            modal?.addEventListener('click', function(e) {
                if (e.target === modal) {
                    if (id === 'modal-delete-product') closeDeleteModal();
                    if (id === 'modal-unpublish-product') closeUnpublishModal();
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeUnpublishModal();
            }
        });
    });
</script>
@endpush
