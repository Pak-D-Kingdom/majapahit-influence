@extends('brand.layouts.app')

@section('title', $product->name)
@section('page-title', 'Detail Produk')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <a href="{{ route('brand.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0b64d4] hover:text-[#0c3685] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog Produk</span>
        </a>
        <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-[#071d49] font-heading">
                    {{ $product->name }}
                </h2>
                <p class="mt-0.5 text-xs text-slate-500">
                    Kategori: {{ $product->category->name ?? 'Umum' }}
                </p>
            </div>
            @if($product->verification_status !== 'pending_delete')
                <a href="{{ route('brand.products.edit', $product) }}" class="btn-kerajaan-primary text-xs">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit Produk</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Status Banner --}}
    @if($product->verification_status === 'pending_update')
        <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-4 text-xs text-[#071d49] flex items-start gap-3">
            <i class="bi bi-hourglass-split text-base text-[#0b64d4] shrink-0 mt-0.5"></i>
            <div>
                <p class="font-bold font-heading text-[#071d49]">Pengajuan Perubahan Sedang Ditinjau</p>
                <p class="mt-0.5 text-slate-600">Perubahan data yang Anda ajukan sedang menunggu persetujuan Superadmin.</p>
            </div>
        </div>
    @elseif($product->verification_status === 'pending_delete')
        <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-4 text-xs text-rose-800 flex items-start gap-3">
            <i class="bi bi-trash-fill text-base text-rose-600 shrink-0 mt-0.5"></i>
            <div>
                <p class="font-bold font-heading text-rose-900">Pengajuan Penghapusan Sedang Ditinjau</p>
                <p class="mt-0.5 text-rose-700">Alasan: "{{ $product->deletion_reason }}"</p>
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs space-y-6">
        <div class="flex flex-col md:flex-row gap-6">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-48 rounded-2xl object-cover border border-slate-200 bg-slate-50 shrink-0">
            <div class="space-y-4 flex-1">
                <div>
                    <h3 class="text-lg font-extrabold text-[#071d49] font-heading">{{ $product->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1">SKU: {{ $product->sku ?: '-' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 rounded-xl bg-slate-50 p-4">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Harga Jual</span>
                        <p class="text-base font-extrabold text-[#071d49] font-heading mt-0.5">Rp {{ number_format((float)$product->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Komisi KOL</span>
                        <p class="text-base font-extrabold text-[#0b64d4] font-heading mt-0.5">{{ $product->locked_commission_percent }}% (Rp {{ number_format((float)$product->locked_commission_amount, 0, ',', '.') }})</p>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-[#071d49] font-heading mb-1">Deskripsi Produk</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
