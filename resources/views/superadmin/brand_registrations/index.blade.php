@extends('superadmin.layouts.app')

@section('title', 'Pendaftaran Brand — Superadmin Majapahit Influence')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Pendaftaran Mitra Brand</h1>
            <p class="text-xs text-gray-500 mt-1">Review pengajuan kemitraan promosi dan layanan maklon dari Brand baru.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.brands.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-xl transition-colors">
                <i class="bi bi-building mr-1"></i> Data Brand Aktif
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('superadmin.brand-registrations.index') }}" class="p-4 rounded-2xl bg-white border {{ !$status ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-gray-100' }} shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase">Total Pendaftar</span>
            <strong class="block text-2xl font-black text-gray-900 mt-1">{{ $counts['total'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'pending']) }}" class="p-4 rounded-2xl bg-white border {{ $status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-gray-100' }} shadow-sm">
            <span class="text-xs font-bold text-amber-600 uppercase">Menunggu Review</span>
            <strong class="block text-2xl font-black text-amber-600 mt-1">{{ $counts['pending'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'approved']) }}" class="p-4 rounded-2xl bg-white border {{ $status === 'approved' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-gray-100' }} shadow-sm">
            <span class="text-xs font-bold text-green-600 uppercase">Disetujui</span>
            <strong class="block text-2xl font-black text-green-600 mt-1">{{ $counts['approved'] }}</strong>
        </a>
        <a href="{{ route('superadmin.brand-registrations.index', ['status' => 'rejected']) }}" class="p-4 rounded-2xl bg-white border {{ $status === 'rejected' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-gray-100' }} shadow-sm">
            <span class="text-xs font-bold text-red-600 uppercase">Ditolak</span>
            <strong class="block text-2xl font-black text-red-600 mt-1">{{ $counts['rejected'] }}</strong>
        </a>
    </div>

    {{-- Flash Alerts --}}
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-xs font-bold flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-base text-green-600"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
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
                <tbody class="divide-y divide-gray-100">
                    @forelse ($registrations as $reg)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-4 px-4">
                                <strong class="text-sm font-bold text-gray-900 block">{{ $reg->brand_name }}</strong>
                                <span class="text-[11px] text-gray-500">{{ $reg->company_name ?: 'Badan Usaha Pribadi' }}</span>
                            </td>
                            <td class="py-4 px-4 font-semibold text-gray-700">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-800 font-bold">
                                    {{ $reg->industry_category }}
                                </span>
                            </td>
                            <td class="py-4 px-4 space-y-0.5">
                                <strong class="text-gray-800 block">{{ $reg->pic_name }} ({{ $reg->pic_title ?: 'PIC' }})</strong>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->pic_phone) }}" target="_blank" class="text-green-600 hover:underline flex items-center gap-1 font-bold">
                                    <i class="bi bi-whatsapp"></i> {{ $reg->pic_phone }}
                                </a>
                                <span class="text-gray-400 block">{{ $reg->pic_email }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg font-bold text-[11px]
                                    @if ($reg->service_need === 'maklon') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif ($reg->service_need === 'endorsement') bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-amber-50 text-amber-800 border border-amber-200 @endif">
                                    {{ $reg->service_need_label }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider
                                    @if ($reg->status === 'approved') bg-green-100 text-green-800
                                    @elseif ($reg->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-gray-500">
                                {{ $reg->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('superadmin.brand-registrations.show', $reg->id) }}" class="px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm transition-colors inline-flex items-center gap-1">
                                    <i class="bi bi-eye"></i> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Belum ada pendaftaran Brand.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $registrations->links() }}
        </div>
    </div>

</div>
@endsection
