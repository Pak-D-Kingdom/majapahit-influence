@extends('superadmin.layouts.app')

@section('title', 'Review Pendaftaran Brand | ' . $registration->brand_name)
@section('page-title', 'Detail Pengajuan Brand')

@section('content')
<div class="space-y-6 max-w-5xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-[#765f58]">
        <a href="{{ route('superadmin.brand-registrations.index') }}" class="hover:text-[#d57028] transition font-medium">Pendaftaran Brand</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-[#421b13] font-bold font-heading">{{ $registration->brand_name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#421b13] font-heading">{{ $registration->brand_name }}</h1>
            <p class="text-xs text-[#765f58] mt-0.5">Pengajuan Kemitraan: {{ $registration->service_need_label }}</p>
        </div>
        <div>
            <x-dashboard.status-badge :status="$registration->status"/>
        </div>
    </div>

    <div class="grid md:grid-cols-12 gap-6">
        
        {{-- Left: Details --}}
        <div class="md:col-span-8 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-[#421b13]/8 shadow-sm space-y-6">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#765f58] font-heading">1. Data Profil Usaha</h3>

                <div class="grid grid-cols-2 gap-5 text-xs">
                    <div>
                        <span class="text-[#765f58] block mb-1">Nama Brand:</span>
                        <strong class="text-sm font-bold text-[#421b13] font-heading">{{ $registration->brand_name }}</strong>
                    </div>
                    <div>
                        <span class="text-[#765f58] block mb-1">Nama Perusahaan:</span>
                        <strong class="text-sm font-bold text-[#421b13] font-heading">{{ $registration->company_name ?: '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-[#765f58] block mb-1">Kategori Industri:</span>
                        <span class="inline-block px-2.5 py-1 rounded-lg bg-[#f7eee8] text-[#421b13] font-bold border border-[#421b13]/10">
                            {{ $registration->industry_category }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[#765f58] block mb-1">Kebutuhan Layanan:</span>
                        <span class="inline-block px-2.5 py-1 rounded-lg bg-[#d57028]/10 text-[#d57028] font-bold border border-[#d57028]/25">
                            {{ $registration->service_need_label }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[#765f58] block mb-1">Media Sosial:</span>
                        <span class="font-bold text-[#421b13]">{{ $registration->social_media ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[#765f58] block mb-1">Website:</span>
                        @if ($registration->website)
                            <a href="{{ $registration->website }}" target="_blank" rel="noopener noreferrer" class="text-[#d57028] hover:underline font-bold inline-flex items-center gap-1">
                                {{ $registration->website }} <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                            </a>
                        @else
                            <span class="text-[#765f58]">-</span>
                        @endif
                    </div>
                </div>

                <div class="pt-5 border-t border-[#421b13]/8">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#765f58] font-heading mb-2">2. Catatan / Rencana Kebutuhan Produk</h3>
                    <div class="p-4 bg-[#fbf7f4] rounded-xl border border-[#421b13]/8 text-xs text-[#421b13] leading-relaxed whitespace-pre-line">
                        {{ $registration->notes ?: 'Tidak ada catatan tambahan.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: PIC & Actions --}}
        <div class="md:col-span-4 space-y-6">
            {{-- PIC Card --}}
            <div class="bg-white rounded-2xl p-6 border border-[#421b13]/8 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#765f58] font-heading">Kontak PIC</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <strong class="block text-sm font-bold text-[#421b13] font-heading">{{ $registration->pic_name }}</strong>
                        <span class="text-[#765f58]">{{ $registration->pic_title ?: 'Penanggung Jawab' }}</span>
                    </div>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->pic_phone) }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                        <i class="bi bi-whatsapp text-base text-emerald-600"></i> Hubungi WhatsApp
                    </a>

                    <div class="p-3 bg-[#fbf7f4] rounded-xl border border-[#421b13]/8 text-[11px] text-[#765f58] space-y-1.5">
                        <div>Email: <strong class="text-[#421b13]">{{ $registration->pic_email }}</strong></div>
                        <div>No. Telp: <strong class="text-[#421b13]">{{ $registration->pic_phone }}</strong></div>
                    </div>
                </div>
            </div>

            {{-- Action Form Card --}}
            <div class="bg-white rounded-2xl p-6 border border-[#421b13]/8 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#765f58] font-heading">Tindakan Admin</h3>

                @if ($registration->status === 'pending')
                    {{-- Approve Form --}}
                    <form action="{{ route('superadmin.brand-registrations.approve', $registration->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Catatan persetujuan (opsional)" class="w-full px-3.5 py-2.5 rounded-xl border border-[#421b13]/15 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui Brand ini dan membuat entitas Brand baru?');" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-colors flex items-center justify-center gap-1.5 font-heading">
                            <i class="bi bi-check-circle-fill"></i> Setujui & Buat Brand
                        </button>
                    </form>

                    {{-- Reject Form --}}
                    <form action="{{ route('superadmin.brand-registrations.reject', $registration->id) }}" method="POST" class="space-y-3 pt-3 border-t border-[#421b13]/8">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Alasan penolakan..." required class="w-full px-3.5 py-2.5 rounded-xl border border-[#421b13]/15 text-xs text-[#421b13] focus:border-[#d5282d] focus:ring-2 focus:ring-[#d5282d]/20 focus:outline-none">
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?');" class="w-full py-2.5 rounded-xl bg-[#d5282d]/10 hover:bg-[#d5282d]/20 text-[#d5282d] font-bold text-xs border border-[#d5282d]/30 transition-colors flex items-center justify-center gap-1.5 font-heading">
                            <i class="bi bi-x-circle"></i> Tolak Pendaftaran
                        </button>
                    </form>
                @else
                    <div class="p-3.5 bg-[#fbf7f4] rounded-xl border border-[#421b13]/8 text-xs space-y-1">
                        <span class="text-[#765f58] block">Direview oleh:</span>
                        <strong class="text-[#421b13] font-bold block">{{ $registration->reviewer->name ?? 'Admin' }}</strong>
                        <span class="text-[#765f58] text-[11px] block">{{ $registration->reviewed_at ? $registration->reviewed_at->format('d M Y H:i') : '-' }}</span>
                        @if ($registration->admin_notes)
                            <div class="mt-2 pt-2 border-t border-[#421b13]/10 text-[#421b13] font-medium italic">
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
