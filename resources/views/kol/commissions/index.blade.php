@extends('kol.layouts.app')

@section('title', 'Komisi Saya')
@section('page-title', 'Komisi Saya')

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <p class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Transparansi Finansial</p>
        <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#071d49] font-heading">Komisi Saya</h2>
        <p class="mt-1 text-sm text-slate-500">Pantau riwayat pembagian komisi, transparansi perhitungan, dan status pencairan ke rekeningmu.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <x-dashboard.stat-card
            label="Komisi Bulan Ini"
            :value="'Rp ' . number_format($stats['month'], 0, ',', '.')"
            icon="bi-calendar2-check-fill"
            hint="Total periode berjalan"
            accent="orange"
        />
        <x-dashboard.stat-card
            label="Belum Dicairkan"
            :value="'Rp ' . number_format($stats['pending'], 0, ',', '.')"
            icon="bi-hourglass-split"
            hint="Status pending & disetujui"
            accent="amber"
        />
        <x-dashboard.stat-card
            label="Sudah Dicairkan"
            :value="'Rp ' . number_format($stats['disbursed'], 0, ',', '.')"
            icon="bi-patch-check-fill"
            hint="Total sepanjang waktu"
            accent="emerald"
        />
    </div>

    {{-- Commission Table --}}
    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex items-center justify-between border-b border-slate-100 p-5 sm:px-6">
            <div>
                <h3 class="text-base font-extrabold text-[#071d49] font-heading">Riwayat Komisi Endorsement</h3>
                <p class="text-xs text-slate-500">Daftar komisi yang didapatkan dari tiap campaign yang selesai dikerjakan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] text-left text-sm">
                <thead class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-heading">
                    <tr>
                        <th class="px-5 py-3.5 sm:px-6">Campaign & Brand</th>
                        <th class="px-5 py-3.5">Fee Campaign</th>
                        <th class="px-5 py-3.5">Komisi Kamu</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($commissions as $commission)
                        <tr class="group hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 sm:px-6">
                                <a href="{{ route('kol.commissions.show', $commission) }}" class="font-bold text-[#071d49] group-hover:text-[#0b64d4] transition font-heading block">
                                    {{ $commission->endorsement->campaign->name }}
                                </a>
                                <p class="text-xs text-slate-500">{{ $commission->endorsement->campaign->brand->name }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs font-semibold text-slate-600">
                                Rp {{ number_format($commission->endorsement_fee, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <p class="text-sm font-extrabold text-emerald-600 font-heading">
                                    Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                                </p>
                                <span class="inline-block mt-0.5 rounded-md bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                    Bagi hasil: {{ $commission->commission_pct }}%
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <x-dashboard.status-badge :status="$commission->status" />
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a href="{{ route('kol.commissions.show', $commission) }}"
                                   class="inline-flex size-8 items-center justify-center rounded-lg bg-slate-50 text-[#0b64d4] hover:bg-[#0b64d4] hover:text-white transition">
                                    <i class="bi bi-chevron-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-slate-500">
                                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-slate-50 text-[#0b64d4]">
                                    <i class="bi bi-wallet2 text-2xl"></i>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-[#071d49]">Belum ada riwayat komisi</p>
                                <p class="mt-1 text-xs text-slate-500">Komisi akan tercatat otomatis saat endorsement kamu ditandai selesai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($commissions->hasPages())
            <div class="border-t border-slate-100 p-4 sm:px-6">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
@endsection
