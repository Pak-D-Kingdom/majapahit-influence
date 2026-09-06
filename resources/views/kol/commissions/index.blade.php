@extends('kol.layouts.app')

@section('title', 'Komisi Saya')
@section('page-title', 'Komisi Saya')

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <p class="text-xs font-bold uppercase tracking-wider text-[#d57028] font-heading">Transparansi Finansial</p>
        <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#421b13] font-heading">Komisi Saya</h2>
        <p class="mt-1 text-sm text-[#765f58]">Pantau riwayat pembagian komisi, transparansi perhitungan, dan status pencairan ke rekeningmu.</p>
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
    <div class="mt-8 overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-[#421b13]/6 p-5 sm:px-6">
            <div>
                <h3 class="text-base font-extrabold text-[#421b13] font-heading">Riwayat Komisi Endorsement</h3>
                <p class="text-xs text-[#765f58]">Daftar komisi yang didapatkan dari tiap campaign yang selesai dikerjakan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] text-left text-sm">
                <thead class="border-b border-[#421b13]/6 bg-[#fff9f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                    <tr>
                        <th class="px-5 py-3.5 sm:px-6">Campaign & Brand</th>
                        <th class="px-5 py-3.5">Fee Campaign</th>
                        <th class="px-5 py-3.5">Komisi Kamu</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#421b13]/6">
                    @forelse ($commissions as $commission)
                        <tr class="group hover:bg-[#fff9f4]/60 transition">
                            <td class="px-5 py-4 sm:px-6">
                                <a href="{{ route('kol.commissions.show', $commission) }}" class="font-bold text-[#421b13] group-hover:text-[#d57028] transition font-heading block">
                                    {{ $commission->endorsement->campaign->name }}
                                </a>
                                <p class="text-xs text-[#765f58]">{{ $commission->endorsement->campaign->brand->name }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs font-semibold text-[#765f58]">
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
                                   class="inline-flex size-8 items-center justify-center rounded-lg bg-[#fff9f4] text-[#d57028] hover:bg-[#d57028] hover:text-white transition">
                                    <i class="bi bi-chevron-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-[#765f58]">
                                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-[#fff9f4] text-[#d57028]">
                                    <i class="bi bi-wallet2 text-2xl"></i>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-[#421b13]">Belum ada riwayat komisi</p>
                                <p class="mt-1 text-xs text-[#765f58]">Komisi akan tercatat otomatis saat endorsement kamu ditandai selesai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($commissions->hasPages())
            <div class="border-t border-[#421b13]/6 p-4 sm:px-6">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
@endsection
