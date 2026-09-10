@extends('kol.layouts.app')

@section('title', 'Leaderboard Kreator')
@section('page-title', 'Leaderboard Kreator')

@push('styles')
<style>
    @keyframes simpleFadeUp {
        0% {
            opacity: 0;
            transform: translateY(14px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-simple-fade {
        animation: simpleFadeUp 0.38s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-delay-1 {
        animation: simpleFadeUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) 0.06s both;
    }

    .animate-delay-2 {
        animation: simpleFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.12s both;
    }

    .animate-delay-3 {
        animation: simpleFadeUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) 0.18s both;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Header Banner & Period Selector --}}
    <div class="rounded-3xl border border-blue-100/80 bg-gradient-to-br from-[#071d49] via-[#0c3685] to-[#04102b] p-6 sm:p-8 text-white shadow-xl shadow-blue-950/10">
        <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
            <div class="space-y-2 max-w-xl">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight font-heading text-white">
                    Leaderboard Kreator KERAJAAN
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/80 leading-relaxed font-body">
                    Apresiasi untuk kreator teraktif dan berkinerja terbaik. Raih poin performa dari setiap campaign selesai dan raih posisi puncak!
                </p>
            </div>

            {{-- Filter Controls --}}
            <form method="GET" action="{{ route('kol.leaderboard.index') }}" id="leaderboard-filter-form" class="flex flex-wrap items-center gap-3 bg-white/10 p-2 sm:p-2.5 rounded-2xl backdrop-blur-md border border-white/15">
                {{-- Period Selector --}}
                <div class="inline-flex rounded-xl bg-black/20 p-1">
                    <a href="{{ route('kol.leaderboard.index', array_merge(request()->query(), ['period' => 'this_month'])) }}"
                       onclick="triggerFilterAnimation()"
                       class="px-3 py-1.5 rounded-lg text-xs font-bold transition font-heading {{ $currentPeriod === 'this_month' ? 'bg-[#0b64d4] text-white shadow-xs' : 'text-blue-200 hover:text-white' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('kol.leaderboard.index', array_merge(request()->query(), ['period' => 'all_time'])) }}"
                       onclick="triggerFilterAnimation()"
                       class="px-3 py-1.5 rounded-lg text-xs font-bold transition font-heading {{ $currentPeriod === 'all_time' ? 'bg-[#0b64d4] text-white shadow-xs' : 'text-blue-200 hover:text-white' }}">
                        Sepanjang Waktu
                    </a>
                </div>

                {{-- Tier Filter Dropdown --}}
                <select name="tier_id" onchange="handleTierChange(this)" class="rounded-xl border border-white/20 bg-slate-900/70 px-3 py-2 text-xs font-semibold text-white focus:outline-none focus:ring-2 focus:ring-[#1698f6] cursor-pointer transition">
                    <option value="" class="bg-slate-900 text-white">Semua Tier</option>
                    @foreach ($tiers as $tier)
                        <option value="{{ $tier->id }}" class="bg-slate-900 text-white" @selected($selectedTierId == $tier->id)>
                            Tier {{ $tier->name }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="period" value="{{ $currentPeriod }}">
            </form>
        </div>
    </div>

    {{-- Quick Tier Filter Pills --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        <span class="text-xs font-bold text-slate-500 shrink-0 font-heading mr-1 flex items-center gap-1.5">
            <i class="bi bi-funnel-fill text-[#0b64d4]"></i> Filter Tier:
        </span>
        <a href="{{ route('kol.leaderboard.index', ['period' => $currentPeriod, 'tier_id' => '']) }}"
           onclick="triggerFilterAnimation()"
           class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 shrink-0 font-heading {{ empty($selectedTierId) ? 'bg-[#071d49] text-white shadow-sm shadow-blue-900/20 ring-2 ring-blue-500/30' : 'bg-white text-slate-600 hover:bg-blue-50 hover:text-[#0b64d4] border border-slate-200' }}">
            Semua Tier
        </a>
        @foreach ($tiers as $tier)
            <a href="{{ route('kol.leaderboard.index', ['period' => $currentPeriod, 'tier_id' => $tier->id]) }}"
               onclick="triggerFilterAnimation()"
               class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 shrink-0 font-heading {{ $selectedTierId == $tier->id ? 'bg-[#0b64d4] text-white shadow-sm shadow-blue-500/20 ring-2 ring-blue-400/40' : 'bg-white text-slate-600 hover:bg-blue-50 hover:text-[#0b64d4] border border-slate-200' }}">
                <span>Tier {{ $tier->name }}</span>
            </a>
        @endforeach
    </div>

    {{-- Bottom Animated Container --}}
    <div id="leaderboard-content-wrapper" class="space-y-6 transition-all duration-300 animate-simple-fade">

        {{-- My Rank Sticky / Highlight Card --}}
        @if ($myRank)
            <div class="relative overflow-hidden rounded-2xl border border-blue-200/80 bg-gradient-to-r from-blue-50/90 via-white to-sky-50/70 p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0b64d4] to-[#1698f6] text-white font-heading font-black text-xl shadow-md shadow-blue-500/20">
                            #{{ $myRank->rank }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Posisi Anda Saat Ini</span>
                                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-[#071d49]">
                                    {{ $myRank->tier?->name ? 'Tier '.$myRank->tier->name : 'KOL' }}
                                </span>
                            </div>
                            <h3 class="text-base font-extrabold text-[#071d49] font-heading mt-0.5">
                                {{ $myRank->user?->name ?? 'Kreator' }} ({{ $myRank->nickname ?: 'Akun Anda' }})
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Peringkat ke-<strong>{{ $myRank->rank }}</strong> dari {{ $totalParticipants }} kreator terdaftar.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 border-t border-blue-100/80 pt-3 sm:border-t-0 sm:pt-0">
                        <div class="text-left sm:text-right">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Total Poin</p>
                            <p class="text-lg font-black text-[#0b64d4] font-heading">{{ number_format($myRank->score) }} pts</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Campaign Selesai</p>
                            <p class="text-lg font-black text-[#071d49] font-heading">{{ number_format($myRank->completed_endorsements_count) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Top 3 Podium (Spotlight) --}}
        @if ($topThree->isNotEmpty())
            <div class="space-y-4 animate-delay-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-[#071d49] font-heading flex items-center gap-2">
                            Top 3 Kreator Terbaik
                        </h2>
                        <p class="text-xs text-slate-500">Pemuncak klasemen {{ $currentPeriod === 'this_month' ? 'periode bulan ini' : 'sepanjang waktu' }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3 items-end pt-4">
                    {{-- Rank 2 (Silver) --}}
                    @if ($topThree->count() >= 2 && ($second = $topThree->get(1)))
                        <div class="order-2 md:order-1 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 text-center relative flex flex-col items-center">
                            <div class="absolute -top-4 rounded-full bg-gradient-to-r from-slate-300 to-slate-400 text-white px-3 py-0.5 text-xs font-black shadow-xs">
                                🥈 Peringkat #2
                            </div>
                            <div class="mt-3 flex size-20 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-700 text-2xl font-black font-heading border-2 border-slate-300 shadow-sm">
                                {{ strtoupper(substr($second->user?->name ?? 'K', 0, 2)) }}
                            </div>
                            <h3 class="mt-3 text-sm font-extrabold text-[#071d49] font-heading truncate max-w-full">
                                {{ $second->user?->name }}
                            </h3>
                            <p class="text-[11px] text-slate-500">Tier {{ $second->tier?->name ?? 'Standard' }}</p>

                            <div class="mt-4 w-full rounded-2xl bg-slate-50 p-3 space-y-1 text-xs">
                                <div class="flex justify-between text-slate-600">
                                    <span>Poin:</span>
                                    <strong class="text-[#0b64d4] font-bold">{{ number_format($second->score) }} pts</strong>
                                </div>
                                <div class="flex justify-between text-slate-600">
                                    <span>Campaign Selesai:</span>
                                    <strong class="text-slate-800 font-bold">{{ number_format($second->completed_endorsements_count) }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Rank 1 (Gold - Elevated) --}}
                    @if ($first = $topThree->get(0))
                        <div class="order-1 md:order-2 rounded-3xl border-2 border-amber-300 bg-gradient-to-b from-amber-50/50 via-white to-amber-50/20 p-7 shadow-lg shadow-amber-500/10 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-center relative flex flex-col items-center -translate-y-1">
                            <div class="absolute -top-5 rounded-full bg-gradient-to-r from-amber-400 to-yellow-500 text-white px-4 py-1 text-xs font-black shadow-md flex items-center gap-1.5 font-heading">
                                <i class="bi bi-crown-fill text-yellow-100"></i>
                                <span>🥇 JUARA #1</span>
                            </div>
                            <div class="mt-4 flex size-24 items-center justify-center rounded-3xl bg-gradient-to-br from-amber-400 to-yellow-500 text-white text-3xl font-black font-heading border-4 border-white shadow-md">
                                {{ strtoupper(substr($first->user?->name ?? 'K', 0, 2)) }}
                            </div>
                            <h3 class="mt-3.5 text-base font-black text-[#071d49] font-heading truncate max-w-full">
                                {{ $first->user?->name }}
                            </h3>
                            <div class="inline-flex items-center gap-1 rounded-full bg-amber-100/80 px-2.5 py-0.5 text-[10px] font-bold text-amber-900 mt-1">
                                Tier {{ $first->tier?->name ?? 'Top Creator' }}
                            </div>

                            <div class="mt-5 w-full rounded-2xl bg-amber-50/80 border border-amber-200/60 p-3.5 space-y-1.5 text-xs">
                                <div class="flex justify-between text-slate-700">
                                    <span>Total Poin:</span>
                                    <strong class="text-amber-700 font-black text-sm">{{ number_format($first->score) }} pts</strong>
                                </div>
                                <div class="flex justify-between text-slate-700">
                                    <span>Campaign Selesai:</span>
                                    <strong class="text-slate-900 font-bold">{{ number_format($first->completed_endorsements_count) }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Rank 3 (Bronze) --}}
                    @if ($topThree->count() >= 3 && ($third = $topThree->get(2)))
                        <div class="order-3 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 text-center relative flex flex-col items-center">
                            <div class="absolute -top-4 rounded-full bg-gradient-to-r from-amber-600 to-amber-700 text-white px-3 py-0.5 text-xs font-black shadow-xs">
                                🥉 Peringkat #3
                            </div>
                            <div class="mt-3 flex size-20 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-100 to-amber-200 text-amber-800 text-2xl font-black font-heading border-2 border-amber-300 shadow-sm">
                                {{ strtoupper(substr($third->user?->name ?? 'K', 0, 2)) }}
                            </div>
                            <h3 class="mt-3 text-sm font-extrabold text-[#071d49] font-heading truncate max-w-full">
                                {{ $third->user?->name }}
                            </h3>
                            <p class="text-[11px] text-slate-500">Tier {{ $third->tier?->name ?? 'Standard' }}</p>

                            <div class="mt-4 w-full rounded-2xl bg-slate-50 p-3 space-y-1 text-xs">
                                <div class="flex justify-between text-slate-600">
                                    <span>Poin:</span>
                                    <strong class="text-[#0b64d4] font-bold">{{ number_format($third->score) }} pts</strong>
                                </div>
                                <div class="flex justify-between text-slate-600">
                                    <span>Campaign Selesai:</span>
                                    <strong class="text-slate-800 font-bold">{{ number_format($third->completed_endorsements_count) }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Full Leaderboard Table --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden animate-delay-2">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-[#071d49] font-heading">Klasemen Lengkap Kreator</h3>
                    <p class="text-xs text-slate-500">Menampilkan peringkat performa seluruh kreator di ekosistem</p>
                </div>
                <span class="text-xs font-bold text-slate-500 font-heading">
                    Total: {{ $totalParticipants }} Kreator
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 font-heading">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-center w-16">Peringkat</th>
                            <th scope="col" class="px-5 py-3.5">KOL / Kreator</th>
                            <th scope="col" class="px-5 py-3.5">Tier & Niche</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Campaign Selesai</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Total Followers</th>
                            <th scope="col" class="px-5 py-3.5 text-right">Skor Performa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-body">
                        @forelse ($allKols as $kol)
                            @php
                                $isMe = $myRank && $myRank->id === $kol->id;
                            @endphp
                            <tr class="transition-colors duration-150 {{ $isMe ? 'bg-blue-50/60 font-semibold' : 'hover:bg-slate-50/60' }}">
                                {{-- Rank --}}
                                <td class="px-5 py-4 text-center">
                                    @if ($kol->rank === 1)
                                        <span class="inline-flex size-7 items-center justify-center rounded-full bg-amber-400 text-white font-black text-xs shadow-xs font-heading">1</span>
                                    @elseif ($kol->rank === 2)
                                        <span class="inline-flex size-7 items-center justify-center rounded-full bg-slate-300 text-slate-800 font-black text-xs shadow-xs font-heading">2</span>
                                    @elseif ($kol->rank === 3)
                                        <span class="inline-flex size-7 items-center justify-center rounded-full bg-amber-600 text-white font-black text-xs shadow-xs font-heading">3</span>
                                    @else
                                        <span class="font-bold text-slate-500 font-heading">#{{ $kol->rank }}</span>
                                    @endif
                                </td>

                                {{-- Creator --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#0b64d4] to-[#1698f6] text-white text-xs font-bold font-heading">
                                            {{ strtoupper(substr($kol->user?->name ?? 'K', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <p class="font-bold text-[#071d49] truncate font-heading text-xs">
                                                    {{ $kol->user?->name ?? 'Kreator' }}
                                                </p>
                                                @if ($isMe)
                                                    <span class="rounded-md bg-blue-100 px-1.5 py-0.5 text-[9px] font-extrabold text-[#0b64d4]">Anda</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-400 truncate">
                                                {{ $kol->city ?: 'Indonesia' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tier & Niche --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="rounded-lg bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-[#0b64d4] border border-blue-100">
                                            {{ $kol->tier?->name ?? 'Standard' }}
                                        </span>
                                        @foreach ($kol->niches->take(2) as $niche)
                                            <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600">
                                                {{ $niche->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Completed --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="font-extrabold text-slate-700 font-heading">
                                        {{ number_format($kol->completed_endorsements_count) }}
                                    </span>
                                </td>

                                {{-- Followers --}}
                                <td class="px-5 py-4 text-center text-slate-600">
                                    {{ number_format($kol->total_followers) }}
                                </td>

                                {{-- Score --}}
                                <td class="px-5 py-4 text-right">
                                    <span class="inline-flex items-center gap-1 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/80 px-2.5 py-1 text-xs font-black text-[#0b64d4] font-heading">
                                        <i class="bi bi-lightning-charge-fill text-amber-500"></i>
                                        {{ number_format($kol->score) }} pts
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-trophy text-3xl text-slate-300 block mb-2"></i>
                                    Belum ada data kreator untuk kriteria filter tier ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function triggerFilterAnimation() {
        const wrapper = document.getElementById('leaderboard-content-wrapper');
        if (wrapper) {
            wrapper.style.opacity = '0.4';
            wrapper.style.transform = 'translateY(8px)';
        }
    }

    function handleTierChange(selectElement) {
        triggerFilterAnimation();
        selectElement.form.submit();
    }
</script>
@endpush