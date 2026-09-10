@extends('superadmin.layouts.app')

@section('title', 'Pendaftaran Mitra Brand | Superadmin kerajaan Influence')
@section('page-title', 'Pendaftaran Mitra Brand')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-kerajaan-dark font-heading">Pendaftaran Mitra Brand</h1>
            <p class="text-xs text-kerajaan-muted mt-1">Review pengajuan kemitraan promosi dan layanan maklon dari Brand baru.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.brands.index') }}" class="btn-kerajaan-secondary text-xs">
                <i class="bi bi-building"></i> Data Brand Aktif
            </a>
        </div>
    </div>

    {{-- Stat Filter Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('superadmin.brand-registrations.index') }}" 
           class="p-4 rounded-2xl bg-white border {{ !$status ? 'border-kerajaan-orange ring-2 ring-kerajaan-orange/20' : 'border-kerajaan-dark/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-kerajaan-muted uppercase font-heading">Total Pendaftar</span>
            <strong class="block text-2xl font-extrabold text-kerajaan-dark mt-1 font-heading">{{ $counts['total'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'pending']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'pending' ? 'border-kerajaan-orange ring-2 ring-kerajaan-orange/20' : 'border-kerajaan-dark/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-kerajaan-brown uppercase font-heading">Menunggu Review</span>
            <strong class="block text-2xl font-extrabold text-kerajaan-orange mt-1 font-heading">{{ $counts['pending'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'approved']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'approved' ? 'border-kerajaan-orange ring-2 ring-kerajaan-orange/20' : 'border-kerajaan-dark/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-emerald-700 uppercase font-heading">Disetujui</span>
            <strong class="block text-2xl font-extrabold text-emerald-600 mt-1 font-heading">{{ $counts['approved'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'rejected']) }}" 
           class="p-4 rounded-2xl bg-white border {{ $status === 'rejected' ? 'border-kerajaan-orange ring-2 ring-kerajaan-orange/20' : 'border-kerajaan-dark/8' }} shadow-sm transition hover:-translate-y-0.5">
            <span class="text-xs font-bold text-kerajaan-red uppercase font-heading">Ditolak</span>
            <strong class="block text-2xl font-extrabold text-kerajaan-red mt-1 font-heading">{{ $counts['rejected'] }}</strong>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-kerajaan-dark/8 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-kerajaan-cream border-b border-kerajaan-dark/8 text-kerajaan-muted font-heading font-bold uppercase tracking-wider text-[11px]">
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
                <tbody class="divide-y divide-kerajaan-dark/6">
                    @forelse ($registrations as $reg)
                        <tr class="hover:bg-kerajaan-cream/60 transition-colors">
                            <td class="py-4 px-4">
                                <strong class="text-sm font-bold text-kerajaan-dark font-heading block">{{ $reg->brand_name }}</strong>
                                <span class="text-[11px] text-kerajaan-muted">{{ $reg->company_name ?: 'Badan Usaha Pribadi' }}</span>
                            </td>
                            <td class="py-4 px-4 font-semibold text-kerajaan-dark">
                                <span class="px-2.5 py-1 rounded-lg bg-kerajaan-sand text-kerajaan-dark font-bold border border-kerajaan-dark/10">
                                    {{ $reg->industry_category }}
                                </span>
                            </td>
                            <td class="py-4 px-4 space-y-0.5">
                                <strong class="text-kerajaan-dark block">{{ $reg->pic_name }} ({{ $reg->pic_title ?: 'PIC' }})</strong>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->pic_phone) }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline flex items-center gap-1 font-bold">
                                    <i class="bi bi-whatsapp"></i> {{ $reg->pic_phone }}
                                </a>
                                <span class="text-kerajaan-muted block">{{ $reg->pic_email }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg font-bold text-[11px]
                                    @if ($reg->service_need === 'maklon') bg-kerajaan-orange/10 text-kerajaan-orange border border-kerajaan-orange/25
                                    @elseif ($reg->service_need === 'endorsement') bg-kerajaan-yellow/20 text-kerajaan-brown border border-kerajaan-yellow/50
                                    @else bg-kerajaan-sand text-kerajaan-dark border border-kerajaan-dark/10 @endif">
                                    {{ $reg->service_need_label }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <x-dashboard.status-badge :status="$reg->status"/>
                            </td>
                            <td class="py-4 px-4 text-kerajaan-muted whitespace-nowrap">
                                {{ $reg->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('superadmin.brand-registrations.show', $reg->id) }}" class="btn-kerajaan-primary text-xs py-1.5 px-3">
                                    <i class="bi bi-eye"></i>
                                    <span>Review</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-kerajaan-muted">
                                <div class="flex size-12 mx-auto items-center justify-center rounded-2xl bg-kerajaan-sand text-kerajaan-muted mb-3">
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
            <div class="p-4 border-t border-kerajaan-dark/8">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
