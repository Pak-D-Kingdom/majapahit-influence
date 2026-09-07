@extends('superadmin.layouts.app')

@section('title', 'Database KOL | Superadmin Majapahit Influence')
@section('page-title', 'Database KOL')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-[#421b13] font-heading">Database Kreator KOL</h1>
            <p class="text-xs text-[#765f58] mt-1">Kelola seluruh profil kreator dalam jaringan agensi, status aktif, dan klasifikasi tier.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.kol.export', request()->query()) }}" class="btn-majapahit-secondary text-xs">
                <i class="bi bi-download"></i>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('superadmin.kol.create') }}" class="btn-majapahit-primary text-xs">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah KOL Baru</span>
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <form method="GET" class="rounded-2xl border border-[#421b13]/8 bg-white p-5 shadow-sm">
        <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Pencarian</label>
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-2.5 text-[#765f58]"></i>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari nama atau email kreator..." class="w-full rounded-xl border border-[#421b13]/15 py-2 pl-9 pr-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Status Keaktifan</label>
                <select name="status" class="w-full rounded-xl border border-[#421b13]/15 py-2 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                    <option value="">Semua Status</option>
                    @foreach (['aktif' => 'Aktif', 'pending' => 'Pending', 'nonaktif' => 'Nonaktif', 'blacklist' => 'Blacklist'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Klasifikasi Tier</label>
                <select name="tier_id" class="w-full rounded-xl border border-[#421b13]/15 py-2 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                    <option value="">Semua Tier</option>
                    @foreach ($tiers as $tier)
                        <option value="{{ $tier->id }}" @selected((string) request('tier_id') === (string) $tier->id)>{{ $tier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Per Halaman</label>
                <select name="per_page" class="w-full rounded-xl border border-[#421b13]/15 py-2 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                    <option value="10">10 per halaman</option>
                    <option value="25" @selected(request('per_page') == 25)>25 per halaman</option>
                    <option value="50" @selected(request('per_page') == 50)>50 per halaman</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-2 pt-3 border-t border-[#421b13]/8">
            <select name="niche_id" class="rounded-xl border border-[#421b13]/15 py-1.5 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                <option value="">Semua Niche</option>
                @foreach ($niches as $niche)
                    <option value="{{ $niche->id }}" @selected((string) request('niche_id') === (string) $niche->id)>{{ $niche->name }}</option>
                @endforeach
            </select>

            <select name="sort" class="rounded-xl border border-[#421b13]/15 py-1.5 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                <option value="joined">Terbaru Bergabung</option>
                <option value="name" @selected(request('sort') === 'name')>Nama</option>
                <option value="followers" @selected(request('sort') === 'followers')>Followers</option>
                <option value="engagement" @selected(request('sort') === 'engagement')>Engagement Rate</option>
            </select>

            <select name="direction" class="rounded-xl border border-[#421b13]/15 py-1.5 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                <option value="desc">Terbesar / Terbaru</option>
                <option value="asc" @selected(request('direction') === 'asc')>Terkecil / Terlama</option>
            </select>

            <button type="submit" class="btn-majapahit-primary text-xs py-1.5 px-3.5">
                Terapkan Filter
            </button>
            <a href="{{ route('superadmin.kol.index') }}" class="btn-majapahit-secondary text-xs py-1.5 px-3">
                Reset
            </a>
        </div>
    </form>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-[#421b13]/8 px-5 py-3.5 bg-[#fbf7f4]">
            <p class="text-xs font-bold text-[#765f58] font-heading">
                Menampilkan <span class="text-[#421b13] font-extrabold">{{ $kols->total() }}</span> KOL terdaftar
            </p>
        </div>

        @if ($kols->isEmpty())
            <div class="flex min-h-72 flex-col items-center justify-center p-8 text-center text-[#765f58]">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-[#f7eee8] text-[#765f58] mb-3">
                    <i class="bi bi-people text-xl"></i>
                </div>
                <p class="font-medium text-sm">Tidak ada data KOL yang sesuai kriteria filter.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-xs">
                    <thead class="bg-[#fbf7f4] border-b border-[#421b13]/8 text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                        <tr>
                            <th class="px-5 py-3.5">KOL</th>
                            <th class="px-5 py-3.5">Niche</th>
                            <th class="px-5 py-3.5">Platform</th>
                            <th class="px-5 py-3.5">Followers</th>
                            <th class="px-5 py-3.5">Engagement</th>
                            <th class="px-5 py-3.5">Tier</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#421b13]/6">
                        @foreach ($kols as $kol)
                            <tr class="transition hover:bg-[#fff9f4]/60">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-tr from-[#d57028] to-[#d5282d] font-bold text-white shadow-xs font-heading">
                                            {{ str($kol->user->name ?? 'K')->substr(0, 1)->upper() }}
                                        </div>
                                        <div>
                                            <a href="{{ route('superadmin.kol.show', $kol) }}" class="font-bold text-[#421b13] font-heading hover:text-[#d57028] transition">
                                                {{ $kol->user->name }}
                                            </a>
                                            <p class="text-[11px] text-[#765f58]">{{ $kol->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-[#765f58] font-medium">
                                    {{ $kol->niches->pluck('name')->join(', ') ?: '-' }}
                                </td>
                                <td class="px-5 py-4 text-[#421b13] font-semibold">
                                    {{ $kol->socialMedia->pluck('platform')->map(fn ($p) => str($p)->title())->join(', ') ?: '-' }}
                                </td>
                                <td class="px-5 py-4 font-bold text-[#421b13] font-heading">
                                    {{ number_format($kol->socialMedia->max('followers_count') ?? 0) }}
                                </td>
                                <td class="px-5 py-4 text-[#765f58] font-medium">
                                    {{ number_format($kol->socialMedia->max('engagement_rate') ?? 0, 2) }}%
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-lg bg-[#f7eee8] border border-[#421b13]/10 px-2.5 py-1 text-xs font-bold text-[#421b13]">
                                        {{ $kol->tier?->name ?: '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <x-dashboard.status-badge :status="$kol->status"/>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('superadmin.kol.show', $kol) }}" class="btn-majapahit-primary text-xs py-1.5 px-3">
                                        <span>Detail</span>
                                        <i class="bi bi-chevron-right text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($kols->hasPages())
                <div class="border-t border-[#421b13]/8 px-5 py-4">
                    {{ $kols->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
