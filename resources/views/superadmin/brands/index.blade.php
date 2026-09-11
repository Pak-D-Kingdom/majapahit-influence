@extends('superadmin.layouts.app')

@section('title', 'Brand & Klien | Superadmin')
@section('page-title', 'Brand & Klien')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-kerajaan-orange font-heading">Manajemen Klien</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-black tracking-tight text-kerajaan-dark font-heading">Brand & Klien</h2>
            <p class="mt-1 text-xs text-kerajaan-muted">Kelola portfolio brand partner dan klien yang berkolaborasi dengan agensi.</p>
        </div>
        <a href="{{ route('superadmin.brands.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-brown px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-kerajaan-brown hover:to-[#0953b3] font-heading">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Brand</span>
        </a>
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-kerajaan-dark/8 bg-white p-4 shadow-xs">
        <div class="relative min-w-[240px] flex-1">
            <i class="bi bi-search absolute left-3.5 top-2.5 text-xs text-kerajaan-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama brand atau PIC..." class="w-full rounded-xl border border-kerajaan-dark/15 bg-white py-2 pl-9 pr-3 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
        </div>
        <div class="w-44">
            <select name="status" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="rounded-xl bg-kerajaan-dark px-4 py-2 text-xs font-bold text-white transition hover:bg-[#071d49] font-heading">
            Terapkan Filter
        </button>
        <a href="{{ route('superadmin.brands.index') }}" class="rounded-xl border border-kerajaan-dark/15 px-4 py-2 text-xs font-bold text-kerajaan-muted transition hover:bg-kerajaan-sand font-heading">
            Reset
        </a>
    </form>

    {{-- Brand Cards Grid --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($brands as $brand)
            <a href="{{ route('superadmin.brands.show', $brand) }}" class="group rounded-2xl border border-kerajaan-dark/8 bg-white p-5 shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-kerajaan-orange/40 hover:shadow-md hover:shadow-kerajaan-dark/5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-kerajaan-orange to-kerajaan-red text-lg font-black text-white shadow-xs font-heading">
                        {{ str($brand->name)->substr(0, 1)->upper() }}
                    </div>
                    <x-dashboard.status-badge :status="$brand->is_active ? 'aktif' : 'nonaktif'" />
                </div>
                <h3 class="mt-4 text-base font-extrabold text-kerajaan-dark transition group-hover:text-kerajaan-orange font-heading">
                    {{ $brand->name }}
                </h3>
                <p class="mt-1 text-xs text-kerajaan-muted">
                    {{ $brand->industry ?: 'Sektor industri belum ditentukan' }}
                </p>
                <div class="mt-4 flex items-center justify-between border-t border-kerajaan-dark/5 pt-3 text-xs text-kerajaan-muted">
                    <span class="flex items-center gap-1.5 font-semibold text-kerajaan-dark">
                        <i class="bi bi-megaphone text-kerajaan-orange"></i>
                        {{ $brand->campaigns_count }} campaign
                    </span>
                    <span class="inline-flex items-center gap-1 font-bold text-kerajaan-orange font-heading">
                        <span>Detail</span>
                        <i class="bi bi-arrow-up-right text-[10px] transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-kerajaan-dark/15 bg-white py-16 text-center">
                <i class="bi bi-building mb-3 block text-3xl text-kerajaan-muted/30"></i>
                <p class="text-sm font-bold text-kerajaan-dark font-heading">Belum Ada Data Brand</p>
                <p class="mt-1 text-xs text-kerajaan-muted">Silakan tambahkan profil brand partner pertama agensi Anda.</p>
                <div class="mt-4">
                    <a href="{{ route('superadmin.brands.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-kerajaan-orange px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-kerajaan-brown font-heading">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Brand Baru</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($brands->hasPages())
        <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-4 shadow-xs">
            {{ $brands->links() }}
        </div>
    @endif
</div>
@endsection
