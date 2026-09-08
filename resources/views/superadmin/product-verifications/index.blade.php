@extends('superadmin.layouts.app')

@section('title', 'Verifikasi Produk Brand')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Verifikasi Produk Brand</h1>
    <p class="text-slate-500 mt-1">Daftar produk dari Brand yang menunggu persetujuan Admin.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-b border-emerald-100 text-emerald-700 text-sm font-medium flex items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Brand</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga & Komisi</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover bg-slate-100 border border-slate-200">
                            @else
                            <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200">
                                <i class="bi bi-image"></i>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500">{{ Str::limit($product->description, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-900">
                        {{ $product->brand->company_name ?? $product->brand->brand_name ?? 'Unknown Brand' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-900 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="text-xs text-emerald-600 font-medium mt-0.5">Komisi: {{ $product->locked_commission_percent }}%</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('superadmin.product-verifications.verify', $product) }}" method="POST" class="inline-flex gap-2 justify-end items-center w-full">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-medium hover:bg-emerald-700 transition" onclick="return confirm('Setujui produk ini? Produk akan tampil di katalog KOL.');">
                                <i class="bi bi-check-lg"></i> Setujui
                            </button>
                        </form>
                        
                        <form action="{{ route('superadmin.product-verifications.verify', $product) }}" method="POST" class="inline-flex gap-2 justify-end items-center w-full mt-2" onsubmit="
                            const reason = prompt('Masukkan alasan penolakan:');
                            if(reason === null) return false;
                            this.querySelector('[name=rejection_reason]').value = reason;
                            return true;
                        ">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <input type="hidden" name="rejection_reason" value="">
                            <button type="submit" class="px-3 py-1.5 bg-rose-100 text-rose-700 rounded-lg text-xs font-medium hover:bg-rose-200 transition">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                <i class="bi bi-check2-all text-xl"></i>
                            </div>
                            <p>Tidak ada produk yang menunggu verifikasi.</p>
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
@endsection
