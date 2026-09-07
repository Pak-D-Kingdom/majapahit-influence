@extends('superadmin.layouts.app')

@section('title', 'Pendaftaran Mitra Brand | Superadmin Majapahit Influence')
@section('page-title', 'Pendaftaran Mitra Brand')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#421b13] font-heading">Pendaftaran Mitra Brand</h1>
            <p class="text-xs text-[#765f58] mt-1">Review pengajuan kemitraan promosi dan layanan maklon dari Brand baru.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.brands.index') }}" class="btn-majapahit-secondary text-xs">
                <i class="bi bi-building"></i> Data Brand Aktif
            </a>
        </div>
    </div>

    {{-- Stat Filter Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('superadmin.brand-registrations.index') }}" 
           class="p-4 rounded-2xl bg-white border {{ !$status ? 'border-[#d57028] ring-2 ring-[#d57028]/20' : 'border-[#421b13]/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-[#765f58] uppercase font-heading">Total Pendaftar</span>
            <strong class="block text-2xl font-extrabold text-[#421b13] mt-1 font-heading">{{ $counts['total'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'pending']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'pending' ? 'border-[#d57028] ring-2 ring-[#d57028]/20' : 'border-[#421b13]/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-[#b86021] uppercase font-heading">Menunggu Review</span>
            <strong class="block text-2xl font-extrabold text-[#d57028] mt-1 font-heading">{{ $counts['pending'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'approved']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'approved' ? 'border-[#d57028] ring-2 ring-[#d57028]/20' : 'border-[#421b13]/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-emerald-700 uppercase font-heading">Disetujui</span>
            <strong class="block text-2xl font-extrabold text-emerald-600 mt-1 font-heading">{{ $counts['approved'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'rejected']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'rejected' ? 'border-[#d57028] ring-2 ring-[#d57028]/20' : 'border-[#421b13]/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-[#d5282d] uppercase font-heading">Ditolak</span>
            <strong class="block text-2xl font-extrabold text-[#d5282d] mt-1 font-heading">{{ $counts['rejected'] }}</strong>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-[#421b13]/8 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#fbf7f4] border-b border-[#421b13]/8 text-[#765f58] font-heading font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Brand / Perusahaan</th>
                        <th class="py-3.5 px-4">Kategori Industri</th>
                        <th class="py-3.5 px-4">PIC & Kontak</th>
                        <th class="py-3.5 px-4">Kebutuhan Layanan</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Tanggal Daftar</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#421b13]/6">
                    @forelse ($registrations as $reg)
                        <tr class="hover:bg-[#fff9f4]/60 transition-colors">
                            <td class="py-4 px-4">
                                <strong class="text-sm font-bold text-[#421b13] font-heading block">{{ $reg->brand_name }}</strong>
                                <span class="text-[11px] text-[#765f58]">{{ $reg->company_name ?: 'Badan Usaha Pribadi' }}</span>
                            </td>
                            <td class="py-4 px-4 font-semibold text-[#421b13]">
                                <span class="px-2.5 py-1 rounded-lg bg-[#f7eee8] text-[#421b13] font-bold border border-[#421b13]/10">
                                    {{ $reg->industry_category }}
                                </span>
                            </td>
                            <td class="py-4 px-4 space-y-0.5">
                                <strong class="text-[#421b13] block">{{ $reg->pic_name }} ({{ $reg->pic_title ?: 'PIC' }})</strong>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->pic_phone) }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline flex items-center gap-1 font-bold">
                                    <i class="bi bi-whatsapp"></i> {{ $reg->pic_phone }}
                                </a>
                                <span class="text-[#765f58] block">{{ $reg->pic_email }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg font-bold text-[11px]
                                    @if ($reg->service_need === 'maklon') bg-[#d57028]/10 text-[#d57028] border border-[#d57028]/25
                                    @elseif ($reg->service_need === 'endorsement') bg-[#fec200]/20 text-[#b86021] border border-[#fec200]/50
                                    @else bg-[#f7eee8] text-[#421b13] border border-[#421b13]/10 @endif">
                                    {{ $reg->service_need_label }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <x-dashboard.status-badge :status="$reg->status"/>
                            </td>
                            <td class="py-4 px-4 text-[#765f58] whitespace-nowrap">
                                {{ $reg->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('superadmin.brand-registrations.show', $reg->id) }}" class="btn-majapahit-primary text-xs py-1.5 px-3">
                                    <i class="bi bi-eye"></i>
                                    <span>Review</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#765f58]">
                                <div class="flex size-12 mx-auto items-center justify-center rounded-2xl bg-[#f7eee8] text-[#765f58] mb-3">
                                    <i class="bi bi-building-slash text-xl"></i>
                                </div>
                                <p class="font-medium text-sm">Belum ada pengajuan pendaftaran Brand.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registrations->hasPages())
            <div class="p-4 border-t border-[#421b13]/8">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
