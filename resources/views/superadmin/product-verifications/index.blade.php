@extends('superadmin.layouts.app')

@section('title', 'Verifikasi & Persetujuan Produk Brand')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Verifikasi & Persetujuan Produk</h1>
        <p class="text-slate-500 text-sm mt-1">Persetujuan untuk pendaftaran produk baru, pengajuan perubahan (edit), dan pengajuan hapus dari Brand.</p>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 scrollbar-none">
    <a href="{{ route('superadmin.product-verifications.index', ['type' => 'all']) }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition {{ $type === 'all' ? 'bg-[#071d49] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        <span>Semua Antrean</span>
        <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold {{ $type === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $allCount }}</span>
    </a>
    <a href="{{ route('superadmin.product-verifications.index', ['type' => 'create']) }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition {{ $type === 'create' ? 'bg-[#0b64d4] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        <span>Produk Baru</span>
        <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold {{ $type === 'create' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-700' }}">{{ $createCount }}</span>
    </a>
    <a href="{{ route('superadmin.product-verifications.index', ['type' => 'update']) }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition {{ $type === 'update' ? 'bg-[#0b64d4] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        <span>Pengajuan Edit</span>
        <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold {{ $type === 'update' ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-700' }}">{{ $updateCount }}</span>
    </a>
    <a href="{{ route('superadmin.product-verifications.index', ['type' => 'delete']) }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition {{ $type === 'delete' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
        <span>Pengajuan Hapus</span>
        <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold {{ $type === 'delete' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-700' }}">{{ $deleteCount }}</span>
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipe & Produk</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Brand</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Detail Pengajuan / Perubahan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi Persetujuan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50/50 transition">
                    {{-- Tipe & Produk --}}
                    <td class="px-6 py-4">
                        <div class="mb-2">
                            @if($product->verification_status === 'pending_update')
                                <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-0.5 text-[11px] font-bold text-blue-700 border border-blue-200">
                                    <i class="bi bi-pencil-square"></i> Pengajuan Edit
                                </span>
                            @elseif($product->verification_status === 'pending_delete')
                                <span class="inline-flex items-center gap-1 rounded-md bg-rose-50 px-2 py-0.5 text-[11px] font-bold text-rose-700 border border-rose-200">
                                    <i class="bi bi-trash"></i> Pengajuan Hapus
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-[11px] font-bold text-amber-800 border border-amber-200">
                                    <i class="bi bi-plus-circle"></i> Produk Baru
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover bg-slate-100 border border-slate-200 shrink-0">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Brand --}}
                    <td class="px-6 py-4 text-sm text-slate-900 whitespace-nowrap">
                        <p class="font-bold text-slate-900">{{ $product->brand->name ?? $product->brand->brand_name ?? 'Unknown Brand' }}</p>
                        <p class="text-xs text-slate-500">{{ $product->brand->pic_email ?? '-' }}</p>
                    </td>

                    {{-- Detail Perubahan / Pengajuan --}}
                    <td class="px-6 py-4 text-xs">
                        @if($product->verification_status === 'pending_update' && !empty($product->pending_changes))
                            {{-- DIFF VIEWER FOR UPDATE --}}
                            <div class="space-y-1.5 rounded-xl bg-slate-50 border border-slate-200/80 p-3 max-w-md">
                                <p class="text-[11px] font-bold text-[#071d49] border-b border-slate-200 pb-1">
                                    Perbandingan Data Perubahan:
                                </p>
                                @if(isset($product->pending_changes['name']) && $product->pending_changes['name'] !== $product->name)
                                    <div>
                                        <span class="text-slate-500">Nama:</span>
                                        <span class="line-through text-rose-500 mr-1">{{ $product->name }}</span>
                                        <strong class="text-emerald-700">→ {{ $product->pending_changes['name'] }}</strong>
                                    </div>
                                @endif
                                @if(isset($product->pending_changes['price']) && (float)$product->pending_changes['price'] != (float)$product->price)
                                    <div>
                                        <span class="text-slate-500">Harga:</span>
                                        <span class="line-through text-rose-500 mr-1">Rp {{ number_format((float)$product->price, 0, ',', '.') }}</span>
                                        <strong class="text-emerald-700">→ Rp {{ number_format((float)$product->pending_changes['price'], 0, ',', '.') }}</strong>
                                    </div>
                                @endif
                                @if(isset($product->pending_changes['locked_commission_percent']) && (float)$product->pending_changes['locked_commission_percent'] != (float)$product->locked_commission_percent)
                                    <div>
                                        <span class="text-slate-500">Komisi:</span>
                                        <span class="line-through text-rose-500 mr-1">{{ $product->locked_commission_percent }}%</span>
                                        <strong class="text-emerald-700">→ {{ $product->pending_changes['locked_commission_percent'] }}%</strong>
                                    </div>
                                @endif
                                @if(isset($product->pending_changes['category_id']) && $product->pending_changes['category_id'] != $product->category_id)
                                    <div>
                                        <span class="text-slate-500">Kategori:</span>
                                        <span class="line-through text-rose-500 mr-1">{{ $product->category?->name ?? '-' }}</span>
                                        <strong class="text-emerald-700">→ {{ $product->pending_category?->name ?? 'Kategori Baru' }}</strong>
                                    </div>
                                @endif
                                @if(!empty($product->pending_changes['image_path']))
                                    <div class="flex items-center gap-2 pt-1 border-t border-slate-200">
                                        <span class="text-slate-500">Foto Baru:</span>
                                        <img src="{{ $product->pending_image_url }}" alt="Foto baru" class="size-8 rounded-lg object-cover border border-emerald-300">
                                        <span class="text-[10px] text-emerald-700 font-bold">Foto telah diunggah</span>
                                    </div>
                                @endif
                            </div>
                        @elseif($product->verification_status === 'pending_delete')
                            {{-- DELETION REASON --}}
                            <div class="rounded-xl bg-rose-50 border border-rose-200 p-3 max-w-md">
                                <p class="text-[11px] font-bold text-rose-900">Alasan Pengajuan Hapus:</p>
                                <p class="text-xs text-rose-700 mt-1 italic">
                                    "{{ $product->deletion_reason ?: 'Tidak ada alasan khusus dicantumkan.' }}"
                                </p>
                            </div>
                        @else
                            {{-- NEW PRODUCT INFO --}}
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Rp {{ number_format((float)$product->price, 0, ',', '.') }}</div>
                                <div class="text-xs text-emerald-600 font-medium mt-0.5">Komisi KOL: {{ $product->locked_commission_percent }}%</div>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                            </div>
                        @endif
                    </td>

                    {{-- Aksi Verifikasi --}}
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Button Setujui Modal --}}
                            <button type="button" 
                                onclick="openApproveModal('{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ $product->verification_status }}')"
                                class="px-3.5 py-1.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-xs flex items-center gap-1.5 font-heading">
                                <i class="bi bi-check-lg"></i>
                                <span>Setujui</span>
                            </button>
                            
                            {{-- Button Tolak Modal --}}
                            <button type="button" 
                                onclick="openRejectModal('{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ $product->verification_status }}')"
                                class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-100 transition flex items-center gap-1.5 font-heading">
                                <i class="bi bi-x-lg"></i>
                                <span>Tolak</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 text-sm">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <i class="bi bi-check2-all text-xl"></i>
                            </div>
                            <p class="font-bold text-slate-700">Tidak ada pengajuan verifikasi</p>
                            <p class="text-xs text-slate-400 mt-1">Semua produk sudah terverifikasi dengan baik.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $products->links() }}
    </div>
    @endif
</div>

{{-- MODAL TOLAK VERIFIKASI PRODUK --}}
<div id="modal-reject-verification" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200" role="dialog" aria-modal="true">
    <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-rose-100">
        {{-- Close button --}}
        <button type="button" onclick="closeRejectModal()" class="absolute right-5 top-5 inline-flex size-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
            <i class="bi bi-x-lg text-sm"></i>
        </button>

        <div class="flex items-center gap-3.5 mb-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-200">
                <i class="bi bi-x-circle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-[#071d49] font-heading">Tolak Verifikasi Produk</h3>
                <p class="text-xs text-[#64748b]">Berikan alasan penolakan secara jelas untuk diteruskan ke Brand.</p>
            </div>
        </div>

        <div class="mb-4 rounded-2xl bg-slate-50 border border-slate-200/80 p-3.5 text-xs text-slate-700">
            <span class="text-slate-500 block text-[11px] font-medium">Produk Terkait:</span>
            <strong id="reject-modal-product-name" class="text-slate-900 font-bold text-sm block mt-0.5">-</strong>
        </div>

        <form id="reject-verification-form" method="POST" action="" class="space-y-4">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="filter_type" value="{{ $type }}">

            <div>
                <label for="reject-modal-reason" class="block text-xs font-bold text-[#071d49] font-heading mb-1.5">
                    Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    name="rejection_reason" 
                    id="reject-modal-reason" 
                    rows="4" 
                    required 
                    placeholder="Tuliskan alasan penolakan (contoh: Foto produk buram, deskripsi kurang lengkap, atau rincian komisi tidak sesuai)..."
                    class="w-full rounded-2xl border border-slate-300 p-3.5 text-xs text-slate-800 placeholder-slate-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 focus:outline-hidden transition"></textarea>
                <p class="mt-1 text-[11px] text-slate-400">Brand akan menerima notifikasi beserta catatan ini untuk perbaikan data.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeRejectModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition font-heading">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition font-heading">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>Kirim Penolakan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL SETUJUI VERIFIKASI PRODUK --}}
<div id="modal-approve-verification" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200" role="dialog" aria-modal="true">
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-emerald-100">
        {{-- Close button --}}
        <button type="button" onclick="closeApproveModal()" class="absolute right-5 top-5 inline-flex size-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
            <i class="bi bi-x-lg text-sm"></i>
        </button>

        <div class="flex items-center gap-3.5 mb-4">
            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                <i class="bi bi-check-circle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-[#071d49] font-heading">Setujui Verifikasi</h3>
                <p class="text-xs text-[#64748b]">Konfirmasi persetujuan produk.</p>
            </div>
        </div>

        <div class="mb-4 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 p-3.5 text-xs text-emerald-900">
            <span class="text-emerald-700 block text-[11px] font-medium">Produk Terkait:</span>
            <strong id="approve-modal-product-name" class="text-emerald-950 font-bold text-sm block mt-0.5">-</strong>
            <p id="approve-modal-description" class="mt-2 text-xs text-emerald-800 leading-relaxed">-</p>
        </div>

        <form id="approve-verification-form" method="POST" action="" class="space-y-4">
            @csrf
            <input type="hidden" name="status" value="approved">
            <input type="hidden" name="filter_type" value="{{ $type }}">

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeApproveModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition font-heading">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition font-heading">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Ya, Setujui Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const baseVerifyRoute = "{{ url('superadmin/product-verifications') }}";

    function openRejectModal(productId, productName, status) {
        const modal = document.getElementById('modal-reject-verification');
        const form = document.getElementById('reject-verification-form');
        const nameEl = document.getElementById('reject-modal-product-name');
        const reasonEl = document.getElementById('reject-modal-reason');

        form.action = `${baseVerifyRoute}/${productId}/verify`;
        nameEl.textContent = productName;
        reasonEl.value = '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => reasonEl.focus(), 50);
    }

    function closeRejectModal() {
        const modal = document.getElementById('modal-reject-verification');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function openApproveModal(productId, productName, status) {
        const modal = document.getElementById('modal-approve-verification');
        const form = document.getElementById('approve-verification-form');
        const nameEl = document.getElementById('approve-modal-product-name');
        const descEl = document.getElementById('approve-modal-description');

        form.action = `${baseVerifyRoute}/${productId}/verify`;
        nameEl.textContent = productName;

        if (status === 'pending_delete') {
            descEl.textContent = 'Menyetujui permintaan penghapusan akan menghapus produk ini dari katalog publik.';
        } else if (status === 'pending_update') {
            descEl.textContent = 'Menyetujui permintaan pembaruan akan langsung menerapkan seluruh perubahan ke katalog.';
        } else {
            descEl.textContent = 'Menyetujui pendaftaran produk baru akan membuat produk aktif dan siap dipublikasikan.';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeApproveModal() {
        const modal = document.getElementById('modal-approve-verification');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['modal-reject-verification', 'modal-approve-verification'].forEach(id => {
            const modal = document.getElementById(id);
            modal?.addEventListener('click', function(e) {
                if (e.target === modal) {
                    if (id === 'modal-reject-verification') closeRejectModal();
                    if (id === 'modal-approve-verification') closeApproveModal();
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRejectModal();
                closeApproveModal();
            }
        });
    });
</script>
@endpush
