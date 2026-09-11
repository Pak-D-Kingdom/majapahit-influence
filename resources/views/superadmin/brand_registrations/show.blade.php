@extends('superadmin.layouts.app')

@section('title', 'Review Pendaftaran Brand | ' . $registration->brand_name)
@section('page-title', 'Detail Pengajuan Brand')

@section('content')
<div class="space-y-6 max-w-5xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-kerajaan-muted">
        <a href="{{ route('superadmin.brand-registrations.index') }}" class="hover:text-kerajaan-orange transition font-medium">Pendaftaran Brand</a>
        <i class="bi bi-chevron-right text-[10px]"></i>
        <span class="text-kerajaan-dark font-bold font-heading">{{ $registration->brand_name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-kerajaan-dark font-heading">{{ $registration->brand_name }}</h1>
            <p class="text-xs text-kerajaan-muted mt-0.5">Pengajuan Kemitraan: {{ $registration->service_need_label }}</p>
        </div>
        <div>
            <x-dashboard.status-badge :status="$registration->status"/>
        </div>
    </div>

    <div class="grid md:grid-cols-12 gap-6">
        
        {{-- Left: Details --}}
        <div class="md:col-span-8 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-kerajaan-dark/8 shadow-sm space-y-6">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-kerajaan-muted font-heading">1. Data Profil Usaha</h3>

                <div class="grid grid-cols-2 gap-5 text-xs">
                    <div>
                        <span class="text-kerajaan-muted block mb-1">Nama Brand:</span>
                        <strong class="text-sm font-bold text-kerajaan-dark font-heading">{{ $registration->brand_name }}</strong>
                    </div>
                    <div>
                        <span class="text-kerajaan-muted block mb-1">Nama Perusahaan:</span>
                        <strong class="text-sm font-bold text-kerajaan-dark font-heading">{{ $registration->company_name ?: '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-kerajaan-muted block mb-1">Kategori Industri:</span>
                        <span class="inline-block px-2.5 py-1 rounded-lg bg-kerajaan-sand text-kerajaan-dark font-bold border border-kerajaan-dark/10">
                            {{ $registration->industry_category }}
                        </span>
                    </div>
                    <div>
                        <span class="text-kerajaan-muted block mb-1">Kebutuhan Layanan:</span>
                        <span class="inline-block px-2.5 py-1 rounded-lg bg-kerajaan-orange/10 text-kerajaan-orange font-bold border border-kerajaan-orange/25">
                            {{ $registration->service_need_label }}
                        </span>
                    </div>
                    <div>
                        <span class="text-kerajaan-muted block mb-1">Media Sosial:</span>
                        <span class="font-bold text-kerajaan-dark">{{ $registration->social_media ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-kerajaan-muted block mb-1">Website:</span>
                        @if ($registration->website)
                            <a href="{{ $registration->website }}" target="_blank" rel="noopener noreferrer" class="text-kerajaan-orange hover:underline font-bold inline-flex items-center gap-1">
                                {{ $registration->website }} <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                            </a>
                        @else
                            <span class="text-kerajaan-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="pt-5 border-t border-kerajaan-dark/8">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-kerajaan-muted font-heading mb-2">2. Catatan / Rencana Kebutuhan Produk</h3>
                    <div class="p-4 bg-kerajaan-cream rounded-xl border border-kerajaan-dark/8 text-xs text-kerajaan-dark leading-relaxed whitespace-pre-line">
                        {{ $registration->notes ?: 'Tidak ada catatan tambahan.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: PIC & Actions --}}
        <div class="md:col-span-4 space-y-6">
            {{-- PIC Card --}}
            <div class="bg-white rounded-2xl p-6 border border-kerajaan-dark/8 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-kerajaan-muted font-heading">Kontak PIC</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <strong class="block text-sm font-bold text-kerajaan-dark font-heading">{{ $registration->pic_name }}</strong>
                        <span class="text-kerajaan-muted">{{ $registration->pic_title ?: 'Penanggung Jawab' }}</span>
                    </div>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->pic_phone) }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                        <i class="bi bi-whatsapp text-base text-emerald-600"></i> Hubungi WhatsApp
                    </a>

                    <div class="p-3 bg-kerajaan-cream rounded-xl border border-kerajaan-dark/8 text-[11px] text-kerajaan-muted space-y-1.5">
                        <div>Email: <strong class="text-kerajaan-dark">{{ $registration->pic_email }}</strong></div>
                        <div>No. Telp: <strong class="text-kerajaan-dark">{{ $registration->pic_phone }}</strong></div>
                    </div>
                </div>
            </div>

            {{-- Action Form Card --}}
            <div class="bg-white rounded-2xl p-6 border border-kerajaan-dark/8 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-kerajaan-muted font-heading">Tindakan Admin</h3>

                @if ($registration->status === 'pending')
                    <div class="space-y-3">
                        <button type="button" onclick="openApproveBrandModal()" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-colors flex items-center justify-center gap-1.5 font-heading">
                            <i class="bi bi-check-circle-fill"></i> Setujui & Buat Brand
                        </button>

                        <button type="button" onclick="openRejectBrandModal()" class="w-full py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition-colors flex items-center justify-center gap-1.5 font-heading">
                            <i class="bi bi-x-circle"></i> Tolak Pendaftaran
                        </button>
                    </div>
                @else
                    <div class="p-3.5 bg-kerajaan-cream rounded-xl border border-kerajaan-dark/8 text-xs space-y-1">
                        <span class="text-kerajaan-muted block">Direview oleh:</span>
                        <strong class="text-kerajaan-dark font-bold block">{{ $registration->reviewer->name ?? 'Admin' }}</strong>
                        <span class="text-kerajaan-muted text-[11px] block">{{ $registration->reviewed_at ? $registration->reviewed_at->format('d M Y H:i') : '-' }}</span>
                        @if ($registration->admin_notes)
                            <div class="mt-2 pt-2 border-t border-kerajaan-dark/10 text-kerajaan-dark font-medium italic">
                                "{{ $registration->admin_notes }}"
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

@if ($registration->status === 'pending')
    {{-- MODAL APPROVE BRAND --}}
    <div id="modal-approve-brand" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-emerald-100">
            <button type="button" onclick="closeApproveBrandModal()" class="absolute right-5 top-5 inline-flex size-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>

            <div class="flex items-center gap-3.5 mb-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                    <i class="bi bi-building-check text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-[#071d49] font-heading">Setujui Brand</h3>
                    <p class="text-xs text-[#64748b]">Buat akun dan entitas Brand resmi.</p>
                </div>
            </div>

            <div class="mb-4 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 p-3.5 text-xs text-emerald-900">
                <span class="text-emerald-700 block text-[11px] font-medium">Brand yang Disetujui:</span>
                <strong class="text-emerald-950 font-bold text-sm block mt-0.5">{{ $registration->brand_name }}</strong>
                <p class="mt-1 text-xs text-emerald-800">Sistem akan membuat akun Brand aktif dan mengirimkan konfirmasi pendaftaran.</p>
            </div>

            <form action="{{ route('superadmin.brand-registrations.approve', $registration->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#071d49] font-heading mb-1.5">
                        Catatan Persetujuan (Opsional)
                    </label>
                    <input type="text" name="admin_notes" placeholder="Tulis catatan persetujuan jika ada..." class="w-full rounded-2xl border border-slate-300 p-3 text-xs text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-hidden transition">
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeApproveBrandModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition font-heading">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition font-heading">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Ya, Setujui & Buat Akun</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL REJECT BRAND --}}
    <div id="modal-reject-brand" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-rose-100">
            <button type="button" onclick="closeRejectBrandModal()" class="absolute right-5 top-5 inline-flex size-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>

            <div class="flex items-center gap-3.5 mb-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-200">
                    <i class="bi bi-x-circle text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-[#071d49] font-heading">Tolak Pendaftaran Brand</h3>
                    <p class="text-xs text-[#64748b]">Berikan alasan penolakan secara jelas.</p>
                </div>
            </div>

            <div class="mb-4 rounded-2xl bg-slate-50 border border-slate-200/80 p-3.5 text-xs text-slate-700">
                <span class="text-slate-500 block text-[11px] font-medium">Brand:</span>
                <strong class="text-slate-900 font-bold text-sm block mt-0.5">{{ $registration->brand_name }}</strong>
            </div>

            <form action="{{ route('superadmin.brand-registrations.reject', $registration->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="reject-brand-notes" class="block text-xs font-bold text-[#071d49] font-heading mb-1.5">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        name="admin_notes" 
                        id="reject-brand-notes" 
                        rows="4" 
                        required 
                        placeholder="Tuliskan alasan penolakan pendaftaran brand ini..."
                        class="w-full rounded-2xl border border-slate-300 p-3.5 text-xs text-slate-800 placeholder-slate-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 focus:outline-hidden transition"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeRejectBrandModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition font-heading">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition font-heading">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Tolak Pendaftaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    function openApproveBrandModal() {
        const modal = document.getElementById('modal-approve-brand');
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeApproveBrandModal() {
        const modal = document.getElementById('modal-approve-brand');
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function openRejectBrandModal() {
        const modal = document.getElementById('modal-reject-brand');
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => document.getElementById('reject-brand-notes')?.focus(), 50);
    }

    function closeRejectBrandModal() {
        const modal = document.getElementById('modal-reject-brand');
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['modal-approve-brand', 'modal-reject-brand'].forEach(id => {
            const modal = document.getElementById(id);
            modal?.addEventListener('click', function(e) {
                if (e.target === modal) {
                    if (id === 'modal-approve-brand') closeApproveBrandModal();
                    if (id === 'modal-reject-brand') closeRejectBrandModal();
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApproveBrandModal();
                closeRejectBrandModal();
            }
        });
    });
</script>
@endpush
