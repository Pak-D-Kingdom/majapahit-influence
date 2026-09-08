@extends('superadmin.layouts.app')

@section('title', 'Laporan & Analisis | Superadmin Majapahit Influence')
@section('page-title', 'Laporan & Analisis')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-[#421b13] font-heading">Laporan Operasional Agensi</h2>
            <p class="text-xs text-[#765f58] mt-1">Rekap data performa agensi, perputaran keuangan komisi, dan metrik kreator KOL.</p>
        </div>
    </div>

    {{-- Metric Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card 
            label="Total KOL Terdaftar" 
            :value="number_format($stats['total_kols'])" 
            icon="bi-people-fill" 
            hint="Database kreator" 
            accent="orange"
        />
        <x-dashboard.stat-card 
            label="Total Endorsement" 
            :value="number_format($stats['total_endorsements'])" 
            icon="bi-briefcase-fill" 
            hint="Proyek campaign berjalan" 
            accent="amber"
        />
        <x-dashboard.stat-card 
            label="Total Nilai Komisi" 
            :value="'Rp ' . number_format($stats['total_commissions'], 0, ',', '.')" 
            icon="bi-cash-stack" 
            hint="Akumulasi seluruh komisi" 
            accent="amber"
        />
        <x-dashboard.stat-card 
            label="Komisi Telah Dicairkan" 
            :value="'Rp ' . number_format($stats['total_disbursed'], 0, ',', '.')" 
            icon="bi-check-circle-fill" 
            hint="Sukses ditransfer" 
            accent="emerald"
        />
    </div>

    {{-- Export Cards Section --}}
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        {{-- Export Komisi Card --}}
        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="flex size-12 items-center justify-center rounded-xl bg-[#d57028]/10 text-[#d57028] text-xl">
                    <i class="bi bi-wallet2"></i>
                </span>
                <div>
                    <h3 class="font-bold text-[#421b13] font-heading text-base">Laporan Keuangan & Komisi</h3>
                    <p class="text-xs text-[#765f58]">Data riwayat endorsement, rate, dan status pencairan komisi.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('superadmin.reports.commissions.export') }}" class="mt-6 space-y-4">
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Dari Tanggal
                        <input type="date" name="date_from" class="mt-1.5 w-full rounded-xl border border-[#421b13]/15 text-xs py-2 px-3 text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                    </label>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Sampai Tanggal
                        <input type="date" name="date_to" class="mt-1.5 w-full rounded-xl border border-[#421b13]/15 text-xs py-2 px-3 text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Status Komisi
                        <select name="status" class="mt-1.5 w-full rounded-xl border border-[#421b13]/15 text-xs py-2 px-3 text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="dicairkan">Dicairkan</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn-majapahit-primary w-full text-xs py-2.5 font-heading">
                    <i class="bi bi-download mr-1"></i>
                    Export Laporan Komisi (CSV)
                </button>
            </form>
        </div>

        {{-- Export Database KOL Card --}}
        <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="flex size-12 items-center justify-center rounded-xl bg-[#d57028]/10 text-[#d57028] text-xl">
                    <i class="bi bi-person-lines-fill"></i>
                </span>
                <div>
                    <h3 class="font-bold text-[#421b13] font-heading text-base">Laporan Database KOL</h3>
                    <p class="text-xs text-[#765f58]">Daftar kreator terdaftar beserta klasifikasi tier, niche, dan medsos.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('superadmin.reports.kol.export') }}" class="mt-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Filter Tier KOL
                        <select name="tier_id" class="mt-1.5 w-full rounded-xl border border-[#421b13]/15 text-xs py-2 px-3 text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                            <option value="">Semua Tier</option>
                            @foreach ($tiers as $tier)
                                <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#421b13] font-heading">
                        Status Keaktifan
                        <select name="status" class="mt-1.5 w-full rounded-xl border border-[#421b13]/15 text-xs py-2 px-3 text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="pending">Pending</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn-majapahit-secondary w-full text-xs py-2.5 font-heading">
                    <i class="bi bi-download mr-1"></i>
                    Export Data KOL (CSV)
                </button>
            </form>
        </div>
    </div>
@endsection