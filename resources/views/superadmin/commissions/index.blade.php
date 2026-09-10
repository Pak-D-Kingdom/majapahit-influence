@extends('superadmin.layouts.app')

@section('title', 'Manajemen Komisi & Pencairan | Superadmin kerajaan Influence')
@section('page-title', 'Manajemen Komisi')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-kerajaan-dark font-heading">Komisi & Pencairan Dana</h2>
            <p class="text-xs text-kerajaan-muted mt-1">Validasi komisi endorsement dan persetujuan pencairan saldo kreator.</p>
        </div>
        <a href="{{ route('superadmin.reports.commissions.export') }}" class="btn-kerajaan-secondary text-xs">
            <i class="bi bi-download"></i>
            <span>Export CSV</span>
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
            label="Menunggu Review" 
            :value="'Rp ' . number_format($stats['total_pending_review'], 0, ',', '.')" 
            icon="bi-clock-history" 
            hint="Perlu tindakan admin" 
            accent="rose"
        />
        <x-dashboard.stat-card 
            label="Siap Dicairkan" 
            :value="'Rp ' . number_format($stats['total_approved'], 0, ',', '.')" 
            icon="bi-wallet2" 
            hint="Saldo telah disetujui" 
            accent="orange"
        />
        <x-dashboard.stat-card 
            label="Dicairkan Bulan Ini" 
            :value="'Rp ' . number_format($stats['total_disbursed_this_month'], 0, ',', '.')" 
            icon="bi-check-circle-fill" 
            hint="Berhasil ditransfer" 
            accent="emerald"
        />
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="mt-6 rounded-2xl border border-kerajaan-dark/8 bg-white p-4 shadow-sm">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="block text-xs font-bold text-kerajaan-dark font-heading">Cari KOL / Campaign</label>
                <div class="relative mt-1">
                    <i class="bi bi-search absolute left-3 top-2.5 text-kerajaan-muted"></i>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari nama / judul..." class="w-full rounded-xl border border-kerajaan-dark/15 py-2 pl-9 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-kerajaan-dark font-heading">Status Komisi</label>
                <select name="status" class="mt-1 w-full rounded-xl border border-kerajaan-dark/15 py-2 px-3 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="pending_review" @selected(request('status') === 'pending_review')>Menunggu Review</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="dicairkan" @selected(request('status') === 'dicairkan')>Dicairkan</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-kerajaan-dark font-heading">Filter KOL</label>
                <select name="kol_profile_id" class="mt-1 w-full rounded-xl border border-kerajaan-dark/15 py-2 px-3 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none">
                    <option value="">Semua Kreator KOL</option>
                    @foreach ($kolProfiles as $profile)
                        <option value="{{ $profile->id }}" @selected(request('kol_profile_id') == $profile->id)>{{ $profile->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn-kerajaan-primary text-xs py-2 px-4 w-full">
                    Terapkan Filter
                </button>
                @if (request()->hasAny(['search', 'status', 'kol_profile_id']))
                    <a href="{{ route('superadmin.commissions.index') }}" class="btn-kerajaan-secondary text-xs py-2 px-3">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Commission Table --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-kerajaan-dark/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-xs">
                <thead class="border-b border-kerajaan-dark/8 bg-kerajaan-cream text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">
                    <tr>
                        <th class="px-5 py-3.5">KOL / Kreator</th>
                        <th class="px-5 py-3.5">Campaign & Brand</th>
                        <th class="px-5 py-3.5">Nominal Komisi</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kerajaan-dark/6">
                    @forelse ($commissions as $commission)
                        <tr class="hover:bg-kerajaan-cream/60 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-9 items-center justify-center rounded-xl bg-gradient-to-tr from-kerajaan-orange to-kerajaan-red font-bold text-white text-xs font-heading shadow-xs">
                                        {{ str($commission->kolProfile->user->name ?? 'KOL')->substr(0, 2)->upper() }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-kerajaan-dark font-heading">{{ $commission->kolProfile->user->name ?? '-' }}</p>
                                        <p class="text-xs text-kerajaan-muted">{{ $commission->kolProfile->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-kerajaan-dark font-heading">{{ $commission->endorsement->campaign->name ?? '-' }}</p>
                                <p class="text-xs text-kerajaan-muted">{{ $commission->endorsement->campaign->brand->name ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4 font-bold text-kerajaan-dark font-heading">
                                Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-xs text-kerajaan-muted whitespace-nowrap">
                                {{ $commission->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <x-dashboard.status-badge :status="$commission->status" />
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('superadmin.commissions.show', $commission) }}" class="btn-kerajaan-primary text-xs py-1.5 px-3">
                                    <span>Detail</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-kerajaan-muted text-sm">
                                <div class="flex size-12 mx-auto items-center justify-center rounded-2xl bg-kerajaan-sand text-kerajaan-muted mb-3">
                                    <i class="bi bi-wallet2 text-2xl"></i>
                                </div>
                                <p class="font-medium">Belum ada data komisi yang tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($commissions->hasPages())
            <div class="border-t border-kerajaan-dark/8 px-5 py-4">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
@endsection