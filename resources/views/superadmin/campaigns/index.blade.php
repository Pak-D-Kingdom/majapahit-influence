@extends('superadmin.layouts.app')

@section('title', 'Daftar Campaign | Superadmin')
@section('page-title', 'Campaign')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-kerajaan-orange font-heading">Operasional Agensi</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-black tracking-tight text-kerajaan-dark font-heading">Daftar Campaign</h2>
            <p class="mt-1 text-xs text-kerajaan-muted">Pantau seluruh program promosi brand dan penugasan kreator.</p>
        </div>
        <a href="{{ route('superadmin.campaigns.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-brown px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-kerajaan-brown hover:to-[#0953b3] font-heading">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Campaign</span>
        </a>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-kerajaan-dark/8 bg-white p-4 shadow-xs">
        <div class="w-56">
            <select name="status" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                <option value="">Semua Status Campaign</option>
                @foreach (['draft' => 'Draft', 'aktif' => 'Aktif', 'selesai' => 'Selesai'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-xl bg-kerajaan-dark px-4 py-2 text-xs font-bold text-white transition hover:bg-[#071d49] font-heading">
            Filter
        </button>
        <a href="{{ route('superadmin.campaigns.index') }}" class="rounded-xl border border-kerajaan-dark/15 px-4 py-2 text-xs font-bold text-kerajaan-muted transition hover:bg-kerajaan-sand font-heading">
            Reset
        </a>
    </form>

    {{-- Campaigns Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-kerajaan-dark/8 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] text-left text-sm">
                <thead class="border-y border-kerajaan-dark/10 bg-kerajaan-cream text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">
                    <tr>
                        <th class="px-5 py-3.5">Campaign & Budget</th>
                        <th class="px-5 py-3.5">Brand Partner</th>
                        <th class="px-5 py-3.5">Periode</th>
                        <th class="px-5 py-3.5">Assignment</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kerajaan-dark/5">
                    @forelse ($campaigns as $campaign)
                        <tr class="transition hover:bg-kerajaan-sand/40">
                            <td class="px-5 py-4">
                                <a href="{{ route('superadmin.campaigns.show', $campaign) }}" class="font-extrabold text-kerajaan-dark hover:text-kerajaan-orange font-heading">
                                    {{ $campaign->name }}
                                </a>
                                <p class="mt-0.5 text-xs font-semibold text-kerajaan-orange">
                                    Rp{{ number_format($campaign->budget, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-xs font-semibold text-kerajaan-dark">
                                {{ $campaign->brand->name }}
                            </td>
                            <td class="px-5 py-4 text-xs text-kerajaan-muted">
                                {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} s/d {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-kerajaan-dark font-heading">
                                    <i class="bi bi-people text-kerajaan-orange"></i>
                                    {{ $campaign->endorsements_count }} KOL
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <x-dashboard.status-badge :status="$campaign->status" />
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('superadmin.campaigns.show', $campaign) }}" class="inline-flex items-center gap-1 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
                                    <span>Kelola</span>
                                    <i class="bi bi-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <i class="bi bi-megaphone mb-3 block text-3xl text-kerajaan-muted/30"></i>
                                <p class="text-sm font-bold text-kerajaan-dark font-heading">Belum Ada Campaign</p>
                                <p class="mt-1 text-xs text-kerajaan-muted">Mulai buat campaign baru dan tugaskan kreator pilihan Anda.</p>
                                <div class="mt-4">
                                    <a href="{{ route('superadmin.campaigns.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-kerajaan-orange px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-kerajaan-brown font-heading">
                                        <i class="bi bi-plus-lg"></i>
                                        <span>Buat Campaign Pertama</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($campaigns->hasPages())
            <div class="border-t border-kerajaan-dark/5 p-4">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
