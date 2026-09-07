@extends('superadmin.layouts.app')

@section('title', 'Brand & Klien | Superadmin')
@section('page-title', 'Brand & Klien')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#d57028] font-heading">Manajemen Klien</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-black tracking-tight text-[#421b13] font-heading">Brand & Klien</h2>
            <p class="mt-1 text-xs text-[#765f58]">Kelola portfolio brand partner dan klien yang berkolaborasi dengan agensi.</p>
        </div>
        <a href="{{ route('superadmin.brands.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-[#b86021] hover:to-[#934510] font-heading">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Brand</span>
        </a>
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-[#421b13]/8 bg-white p-4 shadow-xs">
        <div class="relative min-w-[240px] flex-1">
            <i class="bi bi-search absolute left-3.5 top-2.5 text-xs text-[#765f58]"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama brand atau PIC..." class="w-full rounded-xl border border-[#421b13]/15 bg-white py-2 pl-9 pr-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
        </div>
        <div class="w-44">
            <select name="status" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3 py-2 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="rounded-xl bg-[#421b13] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#190906] font-heading">
            Terapkan Filter
        </button>
        <a href="{{ route('superadmin.brands.index') }}" class="rounded-xl border border-[#421b13]/15 px-4 py-2 text-xs font-bold text-[#765f58] transition hover:bg-[#f7eee8] font-heading">
            Reset
        </a>
    </form>

    {{-- Brand Cards Grid --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($brands as $brand)
            <a href="{{ route('superadmin.brands.show', $brand) }}" class="group rounded-2xl border border-[#421b13]/8 bg-white p-5 shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-[#d57028]/40 hover:shadow-md hover:shadow-[#421b13]/5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#d57028] to-[#d5282d] text-lg font-black text-white shadow-xs font-heading">
                        {{ str($brand->name)->substr(0, 1)->upper() }}
                    </div>
                    <x-dashboard.status-badge :status="$brand->is_active ? 'aktif' : 'nonaktif'" />
                </div>
                <h3 class="mt-4 text-base font-extrabold text-[#421b13] transition group-hover:text-[#d57028] font-heading">
                    {{ $brand->name }}
                </h3>
                <p class="mt-1 text-xs text-[#765f58]">
                    {{ $brand->industry ?: 'Sektor industri belum ditentukan' }}
                </p>
                <div class="mt-4 flex items-center justify-between border-t border-[#421b13]/5 pt-3 text-xs text-[#765f58]">
                    <span class="flex items-center gap-1.5 font-semibold text-[#421b13]">
                        <i class="bi bi-megaphone text-[#d57028]"></i>
                        {{ $brand->campaigns_count }} campaign
                    </span>
                    <span class="inline-flex items-center gap-1 font-bold text-[#d57028] font-heading">
                        <span>Detail</span>
                        <i class="bi bi-arrow-up-right text-[10px] transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-[#421b13]/15 bg-white py-16 text-center">
                <i class="bi bi-building mb-3 block text-3xl text-[#765f58]/30"></i>
                <p class="text-sm font-bold text-[#421b13] font-heading">Belum Ada Data Brand</p>
                <p class="mt-1 text-xs text-[#765f58]">Silakan tambahkan profil brand partner pertama agensi Anda.</p>
                <div class="mt-4">
                    <a href="{{ route('superadmin.brands.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#d57028] px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-[#b86021] font-heading">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Brand Baru</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($brands->hasPages())
        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-4 shadow-xs">
            {{ $brands->links() }}
        </div>
    @endif
</div>
@endsection
