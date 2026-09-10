@extends('kol.layouts.app')

@section('title', 'KOL Dashboard')
@section('page-title', 'Dashboard Saya')

@section('content')
    {{-- Hero Welcome Banner --}}
    <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-[#071d49] via-[#0c3685] to-[#04102b] p-6 text-white shadow-xl shadow-[#071d49]/15 sm:p-8">
        {{-- Decorative glowing orbs --}}
        <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full bg-[#0b64d4]/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 right-32 size-48 rounded-full bg-[#1698f6]/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
            <div class="max-w-2xl">
                <h2 class="mt-4 text-2xl font-extrabold tracking-tight sm:text-3xl font-heading">
                    Halo, <span class="text-[#78a5d6]">{{ $profile->nickname ?: $profile->user->name }}</span>!
                </h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-200 sm:text-base">
                    Kembangkan pengaruhmu dan buka peluang kolaborasi bersama brand terpercaya. Pantau progress campaign dan komisi transparanmu di sini.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('kol.endorsements.index') }}" class="btn-kerajaan-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1698f6] focus-visible:ring-offset-2 focus-visible:ring-offset-[#071d49]">
                    <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                    <span>Lihat Endorsement</span>
                </a>
                <a href="{{ route('kol.commissions.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-xs transition hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1698f6] focus-visible:ring-offset-2 focus-visible:ring-offset-[#071d49]">
                    <i class="bi bi-wallet2 text-[#78a5d6]" aria-hidden="true"></i>
                    <span>Cek Komisi</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card
            label="Endorsement Aktif"
            :value="number_format($stats['activeEndorsements'])"
            icon="bi-megaphone"
            hint="Sedang berjalan & dikerjakan"
            accent="orange"
        />
        <x-dashboard.stat-card
            label="Komisi Bulan Ini"
            :value="'Rp ' . number_format($stats['monthCommission'], 0, ',', '.')"
            icon="bi-cash-stack"
            hint="Total periode berjalan"
            accent="emerald"
        />
        <x-dashboard.stat-card
            label="Tugas Pending"
            :value="number_format($stats['pendingTasks'])"
            icon="bi-check2-circle"
            hint="Perlu upload / tindakan"
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

    {{-- Main Sections: Endorsements & Schedule --}}
    <div class="mt-8 grid gap-6 xl:grid-cols-[1.25fr_1fr]">
        {{-- Recent Endorsements --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 p-5 sm:px-6">
                <div>
                    <h3 class="text-base font-extrabold text-[#071d49] font-heading">Endorsement Terbaru</h3>
                    <p class="text-xs text-slate-500">Progress pekerjaan dan kolaborasi aktif</p>
                </div>
                <a href="{{ route('kol.endorsements.index') }}" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-bold text-[#0b64d4] hover:bg-slate-50 hover:text-[#0c3685] transition font-heading focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]">
                    <span>Lihat semua</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            @if ($recentEndorsements->isEmpty())
                <div class="flex h-56 flex-col items-center justify-center p-6 text-center text-slate-500">
                    <div class="flex size-14 items-center justify-center rounded-2xl bg-slate-50 text-[#0b64d4]">
                        <i class="bi bi-briefcase text-2xl" aria-hidden="true"></i>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-[#071d49]">Belum ada endorsement</p>
                    <p class="mt-1 text-xs text-slate-500">Endorsement baru yang ditugaskan kepadamu akan tampil di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-heading">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 sm:px-6">Campaign & Brand</th>
                                <th scope="col" class="px-5 py-3.5">Deadline</th>
                                <th scope="col" class="px-5 py-3.5">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentEndorsements as $endorsement)
                                <tr class="group hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-4 sm:px-6">
                                        <a href="{{ route('kol.endorsements.show', $endorsement) }}" class="font-bold text-[#071d49] group-hover:text-[#0b64d4] transition font-heading block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4] rounded">
                                            {{ $endorsement->campaign->name }}
                                        </a>
                                        <p class="text-xs text-slate-500">{{ $endorsement->campaign->brand->name }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-slate-500">
                                        <i class="bi bi-calendar3 mr-1 text-[#0b64d4]" aria-hidden="true"></i>
                                        {{ $endorsement->deadline->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <x-dashboard.status-badge :status="$endorsement->status" />
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        <a href="{{ route('kol.endorsements.show', $endorsement) }}"
                                           class="inline-flex min-h-[40px] min-w-[40px] items-center justify-center rounded-xl bg-slate-50 text-[#0b64d4] hover:bg-[#0b64d4] hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4] focus-visible:ring-offset-2"
                                           aria-label="Lihat detail endorsement {{ $endorsement->campaign->name }}">
                                            <i class="bi bi-arrow-right text-base" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Upcoming Schedule --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 p-5 sm:px-6">
                <div>
                    <h3 class="text-base font-extrabold text-[#071d49] font-heading">Jadwal Mendatang</h3>
                    <p class="text-xs text-slate-500">Agenda deadline & kolaborasi</p>
                </div>
                <a href="{{ route('kol.endorsements.index', ['tab' => 'mendatang']) }}" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-bold text-[#0b64d4] hover:bg-slate-50 hover:text-[#0c3685] transition font-heading focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]">
                    <span>Semua jadwal</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            @if ($upcomingEndorsements->isEmpty())
                <div class="flex h-56 flex-col items-center justify-center p-6 text-center text-slate-500">
                    <div class="flex size-14 items-center justify-center rounded-2xl bg-slate-50 text-[#0b64d4]">
                        <i class="bi bi-calendar-check text-2xl" aria-hidden="true"></i>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-[#071d49]">Tidak ada jadwal mendesak</p>
                    <p class="mt-1 text-xs text-slate-500">Semua agenda pekerjaan saat ini sudah terselesaikan.</p>
                </div>
            @else
                <div class="space-y-3 p-5 sm:p-6">
                    @foreach ($upcomingEndorsements as $endorsement)
                        <div class="flex items-center gap-3.5 rounded-xl border border-slate-100 bg-slate-50/50 p-3.5 transition hover:border-[#0b64d4]/30 hover:bg-white">
                            {{-- Date Badge --}}
                            <div class="flex size-12 shrink-0 flex-col items-center justify-center rounded-xl bg-gradient-to-br from-[#0b64d4] to-[#1698f6] text-white shadow-xs">
                                <span class="text-base font-extrabold leading-none font-heading">{{ $endorsement->deadline->format('d') }}</span>
                                <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider font-heading">{{ $endorsement->deadline->format('M') }}</span>
                            </div>

                            {{-- Details --}}
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('kol.endorsements.show', $endorsement) }}" class="truncate text-sm font-bold text-[#071d49] hover:text-[#0b64d4] transition font-heading block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4] rounded">
                                    {{ $endorsement->campaign->name }}
                                </a>
                                <p class="mt-0.5 truncate text-xs text-slate-500">
                                    {{ $endorsement->campaign->brand->name }} •
                                    <span class="font-medium text-[#0b64d4]">{{ str($endorsement->content_type)->replace('_', ' ')->title() }}</span>
                                </p>
                            </div>

                            <a href="{{ route('kol.endorsements.show', $endorsement) }}"
                               class="inline-flex min-h-[40px] min-w-[40px] items-center justify-center rounded-xl text-slate-400 hover:bg-white hover:text-[#0b64d4] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]"
                               aria-label="Lihat jadwal {{ $endorsement->campaign->name }}">
                                <i class="bi bi-chevron-right text-base" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    {{-- Top Leaderboard Spotlight Section --}}
    @if (!empty($topLeaders) && $topLeaders->isNotEmpty())
        <section aria-labelledby="leaderboard-heading" class="mt-8 rounded-2xl border border-blue-200/70 bg-gradient-to-r from-blue-50/60 via-white to-sky-50/50 p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-blue-100/80 pb-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-yellow-500 text-white shadow-xs">
                        <i class="bi bi-trophy-fill text-sm"></i>
                    </span>
                    <div>
                        <h3 id="leaderboard-heading" class="text-base font-extrabold text-[#071d49] font-heading">Top Kreator Bulan Ini</h3>
                        <p class="text-xs text-slate-500">Kreator dengan performa campaign tertinggi</p>
                    </div>
                </div>
                <a href="{{ route('kol.leaderboard.index') }}" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-bold text-[#0b64d4] hover:bg-white hover:text-[#0c3685] transition font-heading focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]">
                    <span>Buka Leaderboard</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                @foreach ($topLeaders as $idx => $leader)
                    <div class="flex items-center gap-3 rounded-xl border border-slate-200/80 bg-white p-3.5 shadow-2xs">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl font-heading font-black text-sm {{ $idx === 0 ? 'bg-amber-400 text-white shadow-xs' : ($idx === 1 ? 'bg-slate-200 text-slate-700' : 'bg-amber-700 text-white') }}">
                            #{{ $idx + 1 }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-[#071d49] font-heading truncate">{{ $leader->user?->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">Tier {{ $leader->tier?->name ?? 'KOL' }} &bull; {{ number_format($leader->completed_endorsements_count) }} Selesai</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Recent Notifications --}}
    <section aria-labelledby="notifications-heading" class="mt-8 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs sm:p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 id="notifications-heading" class="text-base font-extrabold text-[#071d49] font-heading">Notifikasi Terbaru</h3>
                <p class="text-xs text-slate-500">Pembaruan status endorsement dan komisi</p>
            </div>
            <a href="{{ route('kol.notifications.index') }}" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-bold text-[#0b64d4] hover:bg-slate-50 hover:text-[#0c3685] transition font-heading focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]">
                <span>Lihat semua</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        @if ($notifications->isEmpty())
            <div class="flex h-32 flex-col items-center justify-center text-center text-slate-500">
                <i class="bi bi-bell-slash text-2xl text-slate-400" aria-hidden="true"></i>
                <p class="mt-2 text-xs text-slate-500">Belum ada notifikasi baru.</p>
            </div>
        @else
            <div class="mt-4 grid gap-3 md:grid-cols-3">
                @foreach ($notifications as $notification)
                    <div class="rounded-xl border border-slate-200/70 bg-slate-50/60 p-4 transition hover:border-[#0b64d4]/30 hover:bg-white">
                        <div class="flex items-start gap-3">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-[#0b64d4]/10 text-[#0b64d4]">
                                <i class="bi bi-bell-fill text-sm" aria-hidden="true"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-[#071d49] font-heading truncate">{{ $notification->title }}</p>
                                <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-600">{{ $notification->body }}</p>
                                <p class="mt-2 text-[11px] font-medium text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection

