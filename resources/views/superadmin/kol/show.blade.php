@extends('superadmin.layouts.app')

@section('title', $kol->user->name . ' | Detail KOL')
@section('page-title', 'Detail KOL')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb & Top Actions --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('superadmin.kol.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Data KOL</span>
            </a>
            <div class="mt-3 flex items-center gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#d57028] to-[#d5282d] text-xl font-black text-white shadow-sm font-heading">
                    {{ str($kol->user->name)->substr(0, 1)->upper() }}
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-[#421b13] font-heading">{{ $kol->user->name }}</h2>
                    <p class="text-xs text-[#765f58]">
                        {{ $kol->nickname ?: 'Belum ada nama panggilan' }} &bull; {{ $kol->city ?: 'Lokasi belum diisi' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-dashboard.status-badge :status="$kol->status" />
            <a href="{{ route('superadmin.kol.edit', $kol) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#421b13] px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#190906] font-heading">
                <i class="bi bi-pencil"></i>
                <span>Edit Profil</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-dashboard.stat-card label="Tier" :value="$kol->tier?->name ?: '-'" icon="bi-award" hint="Klasifikasi KOL" accent="orange" />
        <x-dashboard.stat-card label="Followers Utama" :value="number_format($kol->socialMedia->max('followers_count') ?? 0)" icon="bi-people" hint="Akun media sosial tertinggi" accent="amber" />
        <x-dashboard.stat-card label="Engagement Rate" :value="number_format($kol->socialMedia->max('engagement_rate') ?? 0, 2).'%'" icon="bi-graph-up" hint="Rata-rata interaksi" accent="emerald" />
        <x-dashboard.stat-card label="Total Endorsement" :value="number_format($kol->endorsements->count())" icon="bi-briefcase" hint="Assignment terdaftar" accent="orange" />
    </div>

    {{-- Main Info Grid --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Profile Information --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs lg:col-span-2">
            <div class="flex items-center justify-between border-b border-[#421b13]/5 pb-4">
                <h3 class="font-extrabold text-[#421b13] font-heading">Informasi Profil</h3>
                <span class="text-xs text-[#765f58]">ID #{{ $kol->id }}</span>
            </div>

            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Email Akun</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#421b13]">{{ $kol->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Domisili</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#421b13]">{{ collect([$kol->city, $kol->province])->filter()->join(', ') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Informasi Bank</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#421b13]">
                        @if ($kol->bank_name && $kol->bank_account_number)
                            {{ $kol->bank_name }} - {{ $kol->bank_account_number }} (a.n. {{ $kol->bank_account_name ?: '-' }})
                        @else
                            <span class="text-[#765f58]">Belum dilengkapi</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Tanggal Bergabung</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#421b13]">{{ $kol->joined_at ? $kol->joined_at->format('d M Y') : $kol->created_at->format('d M Y') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Niche & Kategori</dt>
                    <dd class="mt-2 flex flex-wrap gap-2">
                        @forelse ($kol->niches as $niche)
                            <span class="rounded-lg border border-[#d57028]/20 bg-[#d57028]/10 px-3 py-1 text-xs font-bold text-[#b86021] font-heading">
                                {{ $niche->name }}
                            </span>
                        @empty
                            <span class="text-xs text-[#765f58]">Belum ada niche terpilih</span>
                        @endforelse
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Bio Singkat</dt>
                    <dd class="mt-1 text-sm leading-relaxed text-[#765f58]">
                        {{ $kol->bio ?: 'Belum ada bio tercatat untuk kreator ini.' }}
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Social Media Accounts --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-[#421b13]/5 pb-4">
                <h3 class="font-extrabold text-[#421b13] font-heading">Media Sosial</h3>
                <span class="text-xs text-[#765f58]">{{ $kol->socialMedia->count() }} akun</span>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($kol->socialMedia as $social)
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-[#421b13]/5 bg-[#fbf7f4] p-3.5">
                        <div class="flex items-center gap-3">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#d57028]/10 text-base text-[#d57028]">
                                @if (str_contains(strtolower($social->platform), 'instagram'))
                                    <i class="bi bi-instagram"></i>
                                @elseif (str_contains(strtolower($social->platform), 'tiktok'))
                                    <i class="bi bi-tiktok"></i>
                                @elseif (str_contains(strtolower($social->platform), 'youtube'))
                                    <i class="bi bi-youtube"></i>
                                @else
                                    <i class="bi bi-globe"></i>
                                @endif
                            </span>
                            <div>
                                <p class="text-xs font-bold text-[#421b13] font-heading">{{ str($social->platform)->title() }}</p>
                                <p class="text-xs text-[#765f58]">{{ $social->username }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-[#421b13] font-heading">{{ number_format($social->followers_count) }} <span class="text-[10px] text-[#765f58]">fol</span></p>
                            <p class="text-[11px] text-[#d57028] font-bold">{{ number_format($social->engagement_rate, 2) }}% ER</p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <i class="bi bi-share text-2xl text-[#765f58]/40"></i>
                        <p class="mt-2 text-xs text-[#765f58]">Belum ada akun media sosial terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Endorsement History --}}
    <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
        <div class="flex items-center justify-between border-b border-[#421b13]/5 pb-4">
            <div>
                <h3 class="font-extrabold text-[#421b13] font-heading">Riwayat Endorsement</h3>
                <p class="text-xs text-[#765f58]">Daftar penugasan endorsement yang dikerjakan oleh KOL ini</p>
            </div>
            <span class="rounded-full bg-[#f7eee8] px-3 py-1 text-xs font-bold text-[#421b13] font-heading">
                {{ $kol->endorsements->count() }} assignment
            </span>
        </div>

        @if ($kol->endorsements->isEmpty())
            <div class="py-12 text-center">
                <i class="bi bi-briefcase text-3xl text-[#765f58]/30"></i>
                <p class="mt-2 text-xs text-[#765f58]">Belum ada riwayat penugasan endorsement.</p>
            </div>
        @else
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="border-y border-[#421b13]/10 bg-[#fbf7f4] text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">
                        <tr>
                            <th class="px-5 py-3.5">Campaign</th>
                            <th class="px-5 py-3.5">Brand</th>
                            <th class="px-5 py-3.5">Fee</th>
                            <th class="px-5 py-3.5">Deadline</th>
                            <th class="px-5 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#421b13]/5">
                        @foreach ($kol->endorsements->sortByDesc('created_at') as $endorsement)
                            <tr class="transition hover:bg-[#f7eee8]/40">
                                <td class="px-5 py-4 font-bold text-[#421b13] font-heading">
                                    {{ $endorsement->campaign->name }}
                                </td>
                                <td class="px-5 py-4 text-xs text-[#765f58]">
                                    {{ $endorsement->campaign->brand->name }}
                                </td>
                                <td class="px-5 py-4 font-bold text-[#421b13]">
                                    Rp{{ number_format($endorsement->fee, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-xs text-[#765f58]">
                                    {{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <x-dashboard.status-badge :status="$endorsement->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
