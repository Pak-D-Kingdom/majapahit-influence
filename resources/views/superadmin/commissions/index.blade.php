@extends('superadmin.layouts.app')

@section('title', 'Manajemen Komisi & Pencairan')
@section('page-title', 'Manajemen Komisi')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm text-slate-500">Kelola validasi komisi endorsement dan persetujuan pencairan dana kreator.</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Komisi & Pencairan Dana</h2>
        </div>
        <a href="{{ route('superadmin.reports.commissions.export') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-xs hover:bg-slate-50 transition">
            <i class="bi bi-download"></i>
            Export CSV
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card 
            label="Total Komisi Pending" 
            :value="'Rp ' . number_format($stats['total_pending'], 0, ',', '.')" 
            icon="bi-hourglass-split" 
            hint="Menunggu konten selesai" 
            accent="amber"
        />
        <x-dashboard.stat-card 
            label="Menunggu Review / Approval" 
            :value="'Rp ' . number_format($stats['total_pending_review'], 0, ',', '.')" 
            icon="bi-clock-history" 
            hint="Perlu tindakan admin" 
            accent="rose"
        />
        <x-dashboard.stat-card 
            label="Siap Dicairkan (Approved)" 
            :value="'Rp ' . number_format($stats['total_approved'], 0, ',', '.')" 
            icon="bi-wallet2" 
            hint="Saldo disetujui" 
            accent="indigo"
        />
        <x-dashboard.stat-card 
            label="Dicairkan Bulan Ini" 
            :value="'Rp ' . number_format($stats['total_disbursed_this_month'], 0, ',', '.')" 
            icon="bi-check-circle-fill" 
            hint="Sudah ditransfer" 
            accent="emerald"
        />
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="block text-xs font-medium text-slate-500">Cari KOL / Campaign</label>
                <div class="relative mt-1">
                    <i class="bi bi-search absolute left-3 top-2.5 text-slate-400"></i>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari nama / judul..." class="w-full rounded-xl border-slate-200 py-2 pl-9 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Status Komisi</label>
                <select name="status" class="mt-1 w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="pending_review" @selected(request('status') === 'pending_review')>Menunggu Review</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="dicairkan" @selected(request('status') === 'dicairkan')>Dicairkan</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Filter KOL</label>
                <select name="kol_profile_id" class="mt-1 w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Kreator KOL</option>
                    @foreach ($kolProfiles as $profile)
                        <option value="{{ $profile->id }}" @selected(request('kol_profile_id') == $profile->id)>{{ $profile->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700 transition">
                    Terapkan Filter
                </button>
                <a href="{{ route('superadmin.commissions.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- Commission Table --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-5 py-3.5">KOL / Kreator</th>
                        <th class="px-5 py-3.5">Campaign & Brand</th>
                        <th class="px-5 py-3.5">Nominal Komisi</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($commissions as $commission)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-9 items-center justify-center rounded-xl bg-indigo-100 font-bold text-indigo-700 text-xs">
                                        {{ str($commission->kolProfile->user->name ?? 'KOL')->substr(0, 2)->upper() }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $commission->kolProfile->user->name ?? '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $commission->kolProfile->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ $commission->endorsement->campaign->name ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $commission->endorsement->campaign->brand->name ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900">
                                    Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500">
                                {{ $commission->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <x-dashboard.status-badge :status="$commission->status" />
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('superadmin.commissions.show', $commission) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    <span>Detail</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-slate-400 text-sm">
                                <i class="bi bi-wallet2 block text-3xl mb-2 text-slate-300"></i>
                                Belum ada data komisi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($commissions->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
@endsection