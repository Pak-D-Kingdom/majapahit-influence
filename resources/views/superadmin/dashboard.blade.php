@extends('superadmin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Ringkasan Operasional')

@section('content')
    {{-- Header Sambutan & Quick Actions --}}
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-medium text-[#765f58]">Selamat datang kembali di Workspace Superadmin.</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#421b13] font-heading">
                Pantau Aktivitas Agensi Hari Ini
            </h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.reports.index') }}" class="btn-majapahit-secondary">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan & Ekspor</span>
            </a>
            <a href="{{ route('superadmin.campaigns.create') }}" class="btn-majapahit-primary">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Buat Campaign</span>
            </a>
        </div>
    </div>

    {{-- 5 Kartu Statistik Utama --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-dashboard.stat-card label="KOL Aktif" :value="number_format($stats['activeKols'])" icon="bi-people-fill" hint="Kreator terverifikasi" accent="orange"/>
        <x-dashboard.stat-card label="Endorsement Berjalan" :value="number_format($stats['activeEndorsements'])" icon="bi-briefcase-fill" hint="Project aktif" accent="amber"/>
        <x-dashboard.stat-card label="Pendaftaran Pending" :value="number_format($stats['pendingRegistrations'])" icon="bi-person-plus-fill" hint="Perlu verifikasi" accent="amber"/>
        <x-dashboard.stat-card label="Pencairan Pending" :value="number_format($stats['pendingDisbursements'])" icon="bi-wallet2" hint="Menunggu persetujuan" accent="rose"/>
        <x-dashboard.stat-card label="Komisi Belum Cair" :value="'Rp ' . number_format($stats['unpaidCommission'], 0, ',', '.')" icon="bi-cash-stack" hint="Total hak kreator" accent="emerald"/>
    </div>

    {{-- Visualisasi Tren & Notifikasi Terbaru --}}
    <div class="mt-8 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
        {{-- Grafik Tren Endorsement --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-heading text-lg font-bold text-[#421b13]">Tren Endorsement</h3>
                    <p class="mt-0.5 text-xs text-[#765f58]">Volume endorsement dalam 6 bulan terakhir</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-[#d57028]/20 bg-[#d57028]/10 px-3 py-1 text-xs font-bold text-[#b86021] font-heading">
                    <span class="size-1.5 rounded-full bg-[#d57028]"></span>
                    {{ $totalCampaigns }} Campaign Aktif
                </span>
            </div>

            <div class="mt-8 flex h-56 items-end justify-between gap-3 border-b border-[#421b13]/8 px-2 pb-2">
                @php $maxTrend = max(1, $endorsementTrend->max('total')); @endphp
                @foreach ($endorsementTrend as $month)
                    <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <span class="text-xs font-bold text-[#765f58] transition group-hover:text-[#d57028] group-hover:scale-110 font-heading">
                            {{ $month['total'] }}
                        </span>
                        <div class="w-full max-w-11 rounded-t-xl bg-gradient-to-t from-[#d57028] to-[#d5282d] transition-all duration-300 group-hover:from-[#d5282d] group-hover:to-[#fec200] group-hover:shadow-md group-hover:shadow-[#d57028]/20" 
                             style="height: {{ max(10, ($month['total'] / $maxTrend) * 85) }}%"></div>
                        <span class="text-[11px] font-semibold text-[#765f58]">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Notifikasi Terbaru --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading text-lg font-bold text-[#421b13]">Notifikasi Terbaru</h3>
                        <p class="mt-0.5 text-xs text-[#765f58]">Pemberitahuan aktivitas sistem penting</p>
                    </div>
                    <a href="{{ route('superadmin.notifications.index') }}" class="text-xs font-bold text-[#d57028] hover:text-[#b86021] transition hover:underline">
                        Lihat semua
                    </a>
                </div>

                @if ($notifications->isEmpty())
                    <div class="flex h-48 flex-col items-center justify-center text-center text-sm text-[#765f58]">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-[#f7eee8] text-[#765f58] mb-3">
                            <i class="bi bi-bell-slash text-xl"></i>
                        </div>
                        <p class="font-medium">Belum ada notifikasi baru.</p>
                        <p class="text-xs text-[#765f58]/80 mt-1">Aktivitas penting akan muncul otomatis di sini.</p>
                    </div>
                @else
                    <div class="mt-5 space-y-3.5">
                        @foreach ($notifications->take(4) as $notification)
                            <div class="flex items-start gap-3 rounded-xl p-2.5 transition hover:bg-[#fbf7f4]">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-[#d57028]/10 text-[#d57028]">
                                    <i class="bi bi-bell-fill text-sm"></i>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-[#421b13] font-heading">{{ $notification->title }}</p>
                                    <p class="mt-0.5 text-xs text-[#765f58] line-clamp-2">{{ $notification->body }}</p>
                                    <span class="mt-1 block text-[10px] text-[#765f58]/70">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if (!$notifications->isEmpty())
                <div class="mt-4 pt-4 border-t border-[#421b13]/8">
                    <a href="{{ route('superadmin.notifications.index') }}" class="block text-center text-xs font-bold text-[#d57028] hover:text-[#b86021]">
                        Buka Pusat Notifikasi Lengkap &rarr;
                    </a>
                </div>
            @endif
        </section>
    </div>

    {{-- Tabel Ringkasan Operasional --}}
    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        {{-- Endorsement Mendekati Deadline --}}
        <section class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-[#421b13]/8">
                <div>
                    <h3 class="font-heading text-base font-bold text-[#421b13]">Endorsement Mendekati Deadline</h3>
                    <p class="text-xs text-[#765f58]">Perlu pemantauan progres konten kreator</p>
                </div>
                <a href="{{ route('superadmin.endorsements.index') }}" class="text-xs font-bold text-[#d57028] hover:text-[#b86021] hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($upcomingEndorsements->isEmpty())
                <div class="flex h-44 flex-col items-center justify-center text-center text-sm text-[#765f58] p-6">
                    <div class="flex size-11 items-center justify-center rounded-xl bg-[#f7eee8] text-[#765f58] mb-2">
                        <i class="bi bi-calendar-check text-xl"></i>
                    </div>
                    <p class="font-medium">Tidak ada endorsement mendekati deadline saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-[#421b13]/8 bg-[#fbf7f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                            <tr>
                                <th class="px-5 py-3.5 font-semibold">KOL / Brand</th>
                                <th class="px-5 py-3.5 font-semibold">Tenggat Waktu</th>
                                <th class="px-5 py-3.5 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#421b13]/6">
                            @foreach ($upcomingEndorsements as $endorsement)
                                <tr class="transition hover:bg-[#fff9f4]/60">
                                    <td class="px-5 py-3.5">
                                        <p class="font-bold text-[#421b13] font-heading">{{ $endorsement->kolProfile->user->name ?? 'KOL' }}</p>
                                        <p class="text-xs text-[#765f58]">{{ $endorsement->campaign->brand->name ?? 'Brand' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-xs font-semibold text-[#421b13]">
                                        <i class="bi bi-clock mr-1 text-[#d57028]"></i>
                                        {{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <x-dashboard.status-badge :status="$endorsement->status"/>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Pendaftaran KOL Terbaru --}}
        <section class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-[#421b13]/8">
                <div>
                    <h3 class="font-heading text-base font-bold text-[#421b13]">Pendaftaran KOL Terbaru</h3>
                    <p class="text-xs text-[#765f58]">Kreator baru yang menunggu review verifikasi</p>
                </div>
                <a href="{{ route('superadmin.registrations.index') }}" class="text-xs font-bold text-[#d57028] hover:text-[#b86021] hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($recentRegistrations->isEmpty())
                <div class="flex h-44 flex-col items-center justify-center text-center text-sm text-[#765f58] p-6">
                    <div class="flex size-11 items-center justify-center rounded-xl bg-[#f7eee8] text-[#765f58] mb-2">
                        <i class="bi bi-person-plus text-xl"></i>
                    </div>
                    <p class="font-medium">Belum ada pengajuan pendaftaran baru.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-[#421b13]/8 bg-[#fbf7f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                            <tr>
                                <th class="px-5 py-3.5 font-semibold">Nama Kreator</th>
                                <th class="px-5 py-3.5 font-semibold">Tanggal Daftar</th>
                                <th class="px-5 py-3.5 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#421b13]/6">
                            @foreach ($recentRegistrations as $registration)
                                <tr class="transition hover:bg-[#fff9f4]/60">
                                    <td class="px-5 py-3.5">
                                        <p class="font-bold text-[#421b13] font-heading">{{ $registration->full_name }}</p>
                                        <p class="text-xs text-[#765f58]">{{ $registration->city ?: 'Lokasi belum diisi' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-xs text-[#765f58]">
                                        {{ $registration->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <x-dashboard.status-badge :status="$registration->status"/>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
