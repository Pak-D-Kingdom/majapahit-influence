@extends('brand.layouts.app')

@section('title', 'Brand Dashboard')
@section('page-title', 'Dashboard Saya')

@section('content')
    {{-- Hero Welcome Banner --}}
    <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-[#421b13] via-[#31140d] to-[#240e09] p-6 text-white shadow-xl shadow-[#421b13]/10 sm:p-8">
        {{-- Decorative glowing orbs like landing page --}}
        <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full bg-[#d57028]/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 right-32 size-48 rounded-full bg-[#d5282d]/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-[#fec200] backdrop-blur-xs font-heading">
                    <span class="size-2 rounded-full bg-[#fec200] animate-ping"></span>
                    PORTAL BRAND PAK DE GROUP
                </div>
                <h2 class="mt-4 text-2xl font-extrabold tracking-tight sm:text-3xl font-heading">
                    Halo, <span class="text-[#fec200]">{{ $brand->name }}</span>!
                </h2>
                <p class="mt-2 text-sm leading-relaxed text-white/75 sm:text-base">
                    Pantau seluruh aktivitas campaign, kelola produk, dan lihat perkembangan endorsement secara real-time di sini.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('brand.campaigns.index') }}" class="btn-majapahit-primary">
                    <i class="bi bi-megaphone-fill"></i>
                    <span>Semua Campaign</span>
                </a>
                <a href="{{ route('brand.endorsements.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-xs transition hover:bg-white/20">
                    <i class="bi bi-people-fill text-[#fec200]"></i>
                    <span>Lihat Endorsement</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card
            label="Total Campaign"
            :value="number_format($stats['totalCampaigns'])"
            icon="bi-megaphone"
            hint="Campaign yang dibuat"
            accent="orange"
        />
        <x-dashboard.stat-card
            label="Total Endorsement"
            :value="number_format($stats['totalEndorsements'])"
            icon="bi-people"
            hint="Kerjasama dengan KOL"
            accent="emerald"
        />
        <x-dashboard.stat-card
            label="Produk Pending"
            :value="number_format($stats['pendingProducts'])"
            icon="bi-box-seam"
            hint="Menunggu verifikasi"
            accent="amber"
        />
        <x-dashboard.stat-card
            label="Notifikasi Baru"
            :value="number_format($stats['unreadNotifications'])"
            icon="bi-bell"
            hint="Belum dibaca"
            accent="rose"
        />
    </div>

    {{-- Main Sections: Campaigns & Endorsements --}}
    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        {{-- Recent Campaigns --}}
        <section class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-[#421b13]/6 p-5 sm:px-6">
                <div>
                    <h3 class="text-base font-extrabold text-[#421b13] font-heading">Campaign Terbaru</h3>
                    <p class="text-xs text-[#765f58]">Daftar campaign yang baru dibuat</p>
                </div>
                <a href="{{ route('brand.campaigns.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#d57028] hover:text-[#d5282d] transition font-heading">
                    <span>Lihat semua</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if ($recentCampaigns->isEmpty())
                <div class="flex h-56 flex-col items-center justify-center p-6 text-center text-[#765f58]">
                    <div class="flex size-14 items-center justify-center rounded-2xl bg-[#fff9f4] text-[#d57028]">
                        <i class="bi bi-megaphone text-2xl"></i>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-[#421b13]">Belum ada campaign</p>
                    <p class="mt-1 text-xs text-[#765f58]">Campaign yang Anda buat akan tampil di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-[#421b13]/6 bg-[#fff9f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                            <tr>
                                <th class="px-5 py-3.5 sm:px-6">Nama Campaign</th>
                                <th class="px-5 py-3.5">Periode</th>
                                <th class="px-5 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#421b13]/6">
                            @foreach ($recentCampaigns as $campaign)
                                <tr class="group hover:bg-[#fff9f4]/60 transition">
                                    <td class="px-5 py-4 sm:px-6">
                                        <span class="font-bold text-[#421b13] group-hover:text-[#d57028] transition font-heading block">
                                            {{ $campaign->name }}
                                        </span>
                                        <p class="text-xs text-[#765f58]">Budget: Rp {{ number_format($campaign->budget, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-[#765f58]">
                                        <i class="bi bi-calendar3 mr-1 text-[#d57028]"></i>
                                        {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} - 
                                        {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <x-dashboard.status-badge :status="$campaign->status" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Recent Endorsements --}}
        <section class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-[#421b13]/6 p-5 sm:px-6">
                <div>
                    <h3 class="text-base font-extrabold text-[#421b13] font-heading">Endorsement Terbaru</h3>
                    <p class="text-xs text-[#765f58]">Monitoring status endorsement KOL</p>
                </div>
                <a href="{{ route('brand.endorsements.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#d57028] hover:text-[#d5282d] transition font-heading">
                    <span>Lihat semua</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if ($recentEndorsements->isEmpty())
                <div class="flex h-56 flex-col items-center justify-center p-6 text-center text-[#765f58]">
                    <div class="flex size-14 items-center justify-center rounded-2xl bg-[#fff9f4] text-[#d57028]">
                        <i class="bi bi-people text-2xl"></i>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-[#421b13]">Belum ada endorsement</p>
                    <p class="mt-1 text-xs text-[#765f58]">Kerjasama KOL akan tampil di sini setelah ditugaskan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-[#421b13]/6 bg-[#fff9f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                            <tr>
                                <th class="px-5 py-3.5 sm:px-6">KOL & Campaign</th>
                                <th class="px-5 py-3.5">Deadline</th>
                                <th class="px-5 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#421b13]/6">
                            @foreach ($recentEndorsements as $endorsement)
                                <tr class="group hover:bg-[#fff9f4]/60 transition">
                                    <td class="px-5 py-4 sm:px-6">
                                        <span class="font-bold text-[#421b13] group-hover:text-[#d57028] transition font-heading block">
                                            {{ $endorsement->kolProfile->nickname ?? $endorsement->kolProfile->user->name }}
                                        </span>
                                        <p class="text-xs text-[#765f58]">{{ $endorsement->campaign->name }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-[#765f58]">
                                        <i class="bi bi-calendar3 mr-1 text-[#d57028]"></i>
                                        {{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <x-dashboard.status-badge :status="$endorsement->status" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    {{-- Recent Notifications --}}
    <div class="mt-8 rounded-2xl border border-[#421b13]/8 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex items-center justify-between border-b border-[#421b13]/6 pb-4">
            <div>
                <h3 class="text-base font-extrabold text-[#421b13] font-heading">Notifikasi Terbaru</h3>
                <p class="text-xs text-[#765f58]">Pembaruan status dari aktivitas Anda</p>
            </div>
            <a href="{{ route('brand.notifications.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#d57028] hover:text-[#d5282d] transition font-heading">
                <span>Lihat semua</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if ($notifications->isEmpty())
            <div class="flex h-32 flex-col items-center justify-center text-center text-[#765f58]">
                <i class="bi bi-bell-slash text-2xl text-[#d57028]"></i>
                <p class="mt-2 text-xs text-[#765f58]">Belum ada notifikasi baru.</p>
            </div>
        @else
            <div class="mt-4 grid gap-3 md:grid-cols-3">
                @foreach ($notifications as $notification)
                    <div class="rounded-xl border border-[#421b13]/6 bg-[#fff9f4]/70 p-4 transition hover:border-[#d57028]/30 hover:bg-white">
                        <div class="flex items-start gap-3">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-[#d57028]/10 text-[#d57028]">
                                <i class="bi bi-bell-fill text-sm"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-[#421b13] font-heading truncate">{{ $notification->title }}</p>
                                <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-[#765f58]">{{ $notification->body }}</p>
                                <p class="mt-2 text-[10px] text-[#765f58]/80">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
