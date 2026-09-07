@extends('superadmin.layouts.app')

@section('title', 'Laporan & Analytics')
@section('page-title', 'Laporan & Analytics')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm text-slate-500">Rekap data performa agensi, keuangan komisi, dan kreator KOL.</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Laporan Operasional</h2>
        </div>
    </div>

    {{-- Metric Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card 
            label="Total KOL Terdaftar" 
            :value="number_format($stats['total_kols'])" 
            icon="bi-people-fill" 
            hint="Database kreator" 
            accent="indigo"
        />
        <x-dashboard.stat-card 
            label="Total Endorsement" 
            :value="number_format($stats['total_endorsements'])" 
            icon="bi-briefcase-fill" 
            hint="Proyek campaign" 
            accent="blue"
        />
        <x-dashboard.stat-card 
            label="Total Nilai Komisi" 
            :value="'Rp ' . number_format($stats['total_commissions'], 0, ',', '.')" 
            icon="bi-cash-stack" 
            hint="Akumulasi komisi" 
            accent="amber"
        />
        <x-dashboard.stat-card 
            label="Komisi Telah Dicairkan" 
            :value="'Rp ' . number_format($stats['total_disbursed'], 0, ',', '.')" 
            icon="bi-check-circle-fill" 
            hint="Sudah ditransfer" 
            accent="emerald"
        />
    </div>

    {{-- Export Cards Section --}}
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        {{-- Export Komisi Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="flex size-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-xl">
                    <i class="bi bi-wallet2"></i>
                </span>
                <div>
                    <h3 class="font-bold text-slate-950">Laporan Keuangan & Komisi</h3>
                    <p class="text-xs text-slate-500">Data riwayat endorsement, rate, dan status pencairan komisi.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('superadmin.reports.commissions.export') }}" class="mt-6 space-y-4">
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="block text-xs font-medium text-slate-700">
                        Dari Tanggal
                        <input type="date" name="date_from" class="mt-1.5 w-full rounded-xl border-slate-200 text-xs">
                    </label>
                    <label class="block text-xs font-medium text-slate-700">
                        Sampai Tanggal
                        <input type="date" name="date_to" class="mt-1.5 w-full rounded-xl border-slate-200 text-xs">
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700">
                        Status Komisi
                        <select name="status" class="mt-1.5 w-full rounded-xl border-slate-200 text-xs">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="dicairkan">Dicairkan</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-slate-800">
                    <i class="bi bi-download"></i>
                    Export Laporan Komisi (CSV)
                </button>
            </form>
        </div>

        {{-- Export Database KOL Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="flex size-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 text-xl">
                    <i class="bi bi-person-lines-fill"></i>
                </span>
                <div>
                    <h3 class="font-bold text-slate-950">Laporan Database KOL</h3>
                    <p class="text-xs text-slate-500">Daftar kreator terdaftar beserta tier, niche, dan medsos.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('superadmin.reports.kol.export') }}" class="mt-6 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700">
                        Filter Tier KOL
                        <select name="tier_id" class="mt-1.5 w-full rounded-xl border-slate-200 text-xs">
                            <option value="">Semua Tier</option>
                            @foreach ($tiers as $tier)
                                <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700">
                        Status Keaktifan
                        <select name="status" class="mt-1.5 w-full rounded-xl border-slate-200 text-xs">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="pending">Pending</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-indigo-700">
                    <i class="bi bi-download"></i>
                    Export Data KOL (CSV)
                </button>
            </form>
        </div>
    </div>
@endsection