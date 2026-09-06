@extends('kol.layouts.app')

@section('title', 'Detail Komisi')
@section('page-title', 'Detail Komisi')

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('kol.commissions.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#d57028] hover:text-[#d5282d] transition font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Riwayat Komisi</span>
        </a>
        <div class="mt-3 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <span class="rounded-md bg-[#fff9f4] px-2.5 py-1 text-xs font-bold text-[#b86021] border border-[#d57028]/20 font-heading">
                    {{ $commission->endorsement->campaign->brand->name }}
                </span>
                <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#421b13] font-heading">
                    Detail Komisi: {{ $commission->endorsement->campaign->name }}
                </h2>
            </div>
            <div>
                <x-dashboard.status-badge :status="$commission->status" />
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 text-sm font-medium text-emerald-800 shadow-xs">
            <i class="bi bi-check-circle-fill text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Calculation Breakdown --}}
        <section class="rounded-3xl border border-[#421b13]/8 bg-white p-6 sm:p-8 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-[#421b13]/6 pb-4">
                <div>
                    <h3 class="text-base font-extrabold text-[#421b13] font-heading">Rincian Perhitungan Komisi</h3>
                    <p class="text-xs text-[#765f58]">Skema bagi hasil berdasarkan tier level yang berlaku</p>
                </div>
                <span class="rounded-full bg-[#fff9f4] px-3 py-1 text-xs font-bold text-[#d57028] border border-[#d57028]/20 font-heading">
                    ID #{{ $commission->id }}
                </span>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-[#421b13]/6 bg-[#fff9f4] p-4 sm:p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Fee Endorsement</p>
                    <p class="mt-2 text-xl font-extrabold text-[#421b13] font-heading">
                        Rp {{ number_format($commission->endorsement_fee, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-[11px] text-[#765f58]">Nilai kontrak campaign</p>
                </div>

                <div class="rounded-2xl border border-[#421b13]/6 bg-[#fff9f4] p-4 sm:p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Bagi Hasil Komisi</p>
                    <p class="mt-2 text-xl font-extrabold text-[#d57028] font-heading">
                        {{ $commission->commission_pct }}%
                    </p>
                    <p class="mt-1 text-[11px] text-[#765f58]">Sesuai tier akunmu</p>
                </div>

                <div class="rounded-2xl border border-[#421b13]/6 bg-[#fff9f4] p-4 sm:p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Komisi Bersih Kamu</p>
                    <p class="mt-2 text-xl font-extrabold text-emerald-600 font-heading">
                        Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-[11px] text-[#765f58]">Diterima ke rekening</p>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-[#421b13]/6 bg-[#fff9f4]/60 p-4 text-xs leading-relaxed text-[#765f58]">
                <div class="flex items-start gap-2.5">
                    <i class="bi bi-info-circle-fill text-sm text-[#d57028]"></i>
                    <span>
                        Komisi dihitung transparan otomatis dari fee endorsement dikalikan persentase tier Anda.
                        Pencairan komisi dapat diajukan setelah status komisi dinyatakan <strong>Approved (Disetujui)</strong> oleh Superadmin.
                    </span>
                </div>
            </div>
        </section>

        {{-- Disbursement Action --}}
        <section class="rounded-3xl border border-[#421b13]/8 bg-white p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="border-b border-[#421b13]/6 pb-4">
                    <h3 class="text-base font-extrabold text-[#421b13] font-heading">Ajukan Pencairan</h3>
                    <p class="text-xs text-[#765f58]">Transfer ke rekening terdaftar</p>
                </div>

                <div class="mt-5">
                    @if ($commission->status === 'approved' && ! $commission->approvals->contains('action', 'request'))
                        <form method="POST" action="{{ route('kol.commissions.request-disbursement', $commission) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#421b13] font-heading mb-2">
                                    Catatan Pengajuan (Opsional)
                                </label>
                                <textarea name="notes"
                                          rows="3"
                                          placeholder="Tuliskan pesan atau konfirmasi nomor rekening jika diperlukan..."
                                          class="w-full rounded-xl border border-[#421b13]/15 bg-white p-3 text-xs text-[#421b13] placeholder:text-[#765f58]/50 transition focus:border-[#d57028] focus:outline-hidden focus:ring-2 focus:ring-[#d57028]/20"></textarea>
                            </div>
                            <button type="submit" class="btn-majapahit-primary w-full">
                                <i class="bi bi-cash-stack"></i>
                                <span>Ajukan Pencairan Sekarang</span>
                            </button>
                        </form>
                    @elseif ($commission->status === 'dicairkan')
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-5 text-center">
                            <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                <i class="bi bi-patch-check-fill text-2xl"></i>
                            </div>
                            <p class="mt-3 text-sm font-extrabold text-emerald-800 font-heading">Komisi Telah Dicairkan</p>
                            <p class="mt-1 text-xs text-emerald-700">
                                Dicairkan pada {{ $commission->disbursed_at ? $commission->disbursed_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    @elseif ($commission->approvals->contains('action', 'request'))
                        <div class="rounded-2xl border border-[#fec200]/50 bg-[#fec200]/15 p-5 text-center">
                            <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-[#fec200]/30 text-[#b86021]">
                                <i class="bi bi-hourglass-split text-2xl"></i>
                            </div>
                            <p class="mt-3 text-sm font-extrabold text-[#421b13] font-heading">Pengajuan Sedang Diproses</p>
                            <p class="mt-1 text-xs text-[#765f58]">Tim Admin sedang memverifikasi dan memproses transfer dana ke rekeningmu.</p>
                        </div>
                    @else
                        <div class="rounded-2xl border border-[#421b13]/6 bg-[#fff9f4] p-5 text-center">
                            <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-white text-[#765f58] shadow-xs">
                                <i class="bi bi-lock-fill text-xl"></i>
                            </div>
                            <p class="mt-3 text-sm font-bold text-[#421b13] font-heading">Belum Dapat Diajukan</p>
                            <p class="mt-1 text-xs text-[#765f58]">
                                Komisi dapat diajukan setelah bukti konten Anda selesai direview dan disetujui oleh Superadmin.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 border-t border-[#421b13]/6 pt-4 text-[11px] text-[#765f58]">
                Rekening tujuan pencairan diambil otomatis dari profil bank akun KOL Anda.
            </div>
        </section>
    </div>

    {{-- Timeline / Process Trail --}}
    <section class="mt-8 rounded-3xl border border-[#421b13]/8 bg-white p-6 sm:p-8 shadow-sm">
        <div class="border-b border-[#421b13]/6 pb-4">
            <h3 class="text-base font-extrabold text-[#421b13] font-heading">Riwayat Proses & Approval</h3>
            <p class="text-xs text-[#765f58]">Catatan setiap langkah perubahan status komisi</p>
        </div>

        <div class="mt-6">
            <div class="space-y-6">
                @forelse ($commission->approvals as $approval)
                    <div class="relative flex items-start gap-4">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#d57028] to-[#d5282d] text-white shadow-xs text-xs">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1 rounded-2xl border border-[#421b13]/6 bg-[#fff9f4]/50 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-xs font-extrabold text-[#421b13] font-heading">
                                    {{ str($approval->action)->replace('_', ' ')->title() }}
                                </p>
                                <span class="text-[11px] text-[#765f58]">
                                    {{ $approval->created_at->format('d M Y, H:i') }} • Oleh {{ $approval->performer?->name ?: 'Sistem' }}
                                </span>
                            </div>
                            @if ($approval->notes)
                                <p class="mt-2 text-xs text-[#765f58] bg-white p-2.5 rounded-xl border border-[#421b13]/6">
                                    {{ $approval->notes }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center py-6 text-xs text-[#765f58]">Belum ada riwayat approval pada komisi ini.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
