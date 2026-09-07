@extends('superadmin.layouts.app')

@section('title', 'Review Pendaftaran Brand — ' . $registration->brand_name)

@section('content')
<div class="space-y-6 max-w-5xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-500">
        <a href="{{ route('superadmin.brand-registrations.index') }}" class="hover:text-amber-600">Pendaftaran Brand</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-bold">{{ $registration->brand_name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">{{ $registration->brand_name }}</h1>
            <p class="text-xs text-gray-500 mt-0.5">Pengajuan Kemitraan: {{ $registration->service_need_label }}</p>
        </div>
        <div>
            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                @if ($registration->status === 'approved') bg-green-100 text-green-800
                @elseif ($registration->status === 'rejected') bg-red-100 text-red-800
                @else bg-amber-100 text-amber-800 @endif">
                Status: {{ $registration->status }}
            </span>
        </div>
    </div>

    <div class="grid md:grid-cols-12 gap-6">
        
        {{-- Left: Details --}}
        <div class="md:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-6">
                <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">1. Data Profil Usaha</h3>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block">Nama Brand:</span>
                        <strong class="text-sm font-bold text-gray-900">{{ $registration->brand_name }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Nama Perusahaan:</span>
                        <strong class="text-sm font-bold text-gray-900">{{ $registration->company_name ?: '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Kategori Industri:</span>
                        <span class="inline-block px-2.5 py-1 rounded-lg bg-gray-100 text-gray-800 font-bold mt-1">
                            {{ $registration->industry_category }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Kebutuhan Layanan:</span>
                        <span class="inline-block px-2.5 py-1 rounded-lg bg-amber-50 text-amber-900 font-bold border border-amber-200 mt-1">
                            {{ $registration->service_need_label }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Social Media:</span>
                        <span class="font-bold text-gray-800">{{ $registration->social_media ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Website:</span>
                        @if ($registration->website)
                            <a href="{{ $registration->website }}" target="_blank" class="text-amber-600 hover:underline font-bold">
                                {{ $registration->website }} <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-2">2. Catatan / Rencana Kebutuhan Produk</h3>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $registration->notes ?: 'Tidak ada catatan tambahan.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: PIC & Actions --}}
        <div class="md:col-span-4 space-y-6">
            {{-- PIC Card --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Kontak PIC</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <strong class="block text-sm font-bold text-gray-900">{{ $registration->pic_name }}</strong>
                        <span class="text-gray-500">{{ $registration->pic_title ?: 'Penanggung Jawab' }}</span>
                    </div>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->pic_phone) }}" target="_blank" class="w-full py-2.5 px-3 rounded-xl bg-green-50 hover:bg-green-100 border border-green-200 text-green-800 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                        <i class="bi bi-whatsapp text-base"></i> Hubungi WhatsApp
                    </a>

                    <div class="p-3 bg-gray-50 rounded-xl text-[11px] text-gray-600 space-y-1">
                        <div>Email: <strong class="text-gray-800">{{ $registration->pic_email }}</strong></div>
                        <div>No. Telp: <strong class="text-gray-800">{{ $registration->pic_phone }}</strong></div>
                    </div>
                </div>
            </div>

            {{-- Action Form Card --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Tindakan Admin</h3>

                @if ($registration->status === 'pending')
                    {{-- Approve Form --}}
                    <form action="{{ route('superadmin.brand-registrations.approve', $registration->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Catatan persetujuan (opsional)" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui Brand ini dan membuat entitas Brand baru?');" class="w-full py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold text-xs shadow-md transition-colors flex items-center justify-center gap-1.5">
                            <i class="bi bi-check-circle-fill"></i> Setujui & Buat Brand
                        </button>
                    </form>

                    {{-- Reject Form --}}
                    <form action="{{ route('superadmin.brand-registrations.reject', $registration->id) }}" method="POST" class="space-y-3 pt-3 border-t border-gray-100">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Alasan penolakan..." required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?');" class="w-full py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs border border-red-200 transition-colors flex items-center justify-center gap-1.5">
                            <i class="bi bi-x-circle"></i> Tolak Pendaftaran
                        </button>
                    </form>
                @else
                    <div class="p-3 bg-gray-50 rounded-xl text-xs space-y-1">
                        <span class="text-gray-500 block">Direview oleh:</span>
                        <strong class="text-gray-900 block">{{ $registration->reviewer->name ?? 'Admin' }}</strong>
                        <span class="text-gray-400 text-[11px] block">{{ $registration->reviewed_at ? $registration->reviewed_at->format('d M Y H:i') : '-' }}</span>
                        @if ($registration->admin_notes)
                            <div class="mt-2 pt-2 border-t border-gray-200 text-gray-700 font-medium">
                                "{{ $registration->admin_notes }}"
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
