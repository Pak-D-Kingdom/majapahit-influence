@extends('superadmin.layouts.app')

@section('title', 'Manajemen Endorsement | Superadmin')
@section('page-title', 'Endorsement')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-kerajaan-orange font-heading">Siklus Kerja Kreator</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-black tracking-tight text-kerajaan-dark font-heading">Manajemen Endorsement</h2>
            <p class="mt-1 text-xs text-kerajaan-muted">Pantau seluruh assignment pekerjaan kreator mulai dari pengajuan brief hingga publikasi konten.</p>
        </div>
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-kerajaan-dark/8 bg-white p-4 shadow-xs">
        <div class="relative min-w-[220px] flex-1">
            <i class="bi bi-search absolute left-3.5 top-2.5 text-xs text-kerajaan-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama campaign atau kreator..." class="w-full rounded-xl border border-kerajaan-dark/15 bg-white py-2 pl-9 pr-3 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
        </div>

        <div class="w-48">
            <select name="status" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                <option value="">Semua Status Pekerjaan</option>
                @foreach (['assigned' => 'Ditugaskan', 'in_progress' => 'Sedang Berjalan', 'content_submitted' => 'Konten Diajukan', 'content_approved' => 'Konten Disetujui', 'content_rejected' => 'Perlu Revisi', 'selesai' => 'Selesai'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-48">
            <select name="campaign_id" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                <option value="">Semua Campaign</option>
                @foreach ($campaigns as $campaign)
                    <option value="{{ $campaign->id }}" @selected(request('campaign_id') == $campaign->id)>{{ $campaign->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="rounded-xl bg-kerajaan-dark px-4 py-2 text-xs font-bold text-white transition hover:bg-[#071d49] font-heading">
            Filter
        </button>
        <a href="{{ route('superadmin.endorsements.index') }}" class="rounded-xl border border-kerajaan-dark/15 px-4 py-2 text-xs font-bold text-kerajaan-muted transition hover:bg-kerajaan-sand font-heading">
            Reset
        </a>
    </form>

    {{-- Endorsements Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-kerajaan-dark/8 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-y border-kerajaan-dark/10 bg-kerajaan-cream text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">
                    <tr>
                        <th class="px-5 py-3.5">Kreator (KOL)</th>
                        <th class="px-5 py-3.5">Campaign & Brand</th>
                        <th class="px-5 py-3.5">Fee Endorsement</th>
                        <th class="px-5 py-3.5">Deadline</th>
                        <th class="px-5 py-3.5">Status Pengerjaan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kerajaan-dark/5">
                    @forelse ($endorsements as $endorsement)
                        <tr class="transition hover:bg-kerajaan-sand/40">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-9 items-center justify-center rounded-lg bg-gradient-to-br from-kerajaan-orange to-kerajaan-red text-xs font-black text-white font-heading">
                                        {{ str($endorsement->kolProfile->user->name)->substr(0, 1)->upper() }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-kerajaan-dark font-heading">{{ $endorsement->kolProfile->user->name }}</p>
                                        <p class="text-[11px] text-kerajaan-muted">{{ str($endorsement->content_type)->replace('_', ' ')->title() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-kerajaan-dark font-heading">{{ $endorsement->campaign->name }}</p>
                                <p class="text-xs text-kerajaan-muted">{{ $endorsement->campaign->brand->name }}</p>
                            </td>
                            <td class="px-5 py-4 font-bold text-kerajaan-dark">
                                Rp{{ number_format($endorsement->fee, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $isOverdue = $endorsement->deadline->isPast() && $endorsement->status !== 'selesai';
                                @endphp
                                <span class="{{ $isOverdue ? 'font-bold text-kerajaan-red' : 'text-kerajaan-muted' }} text-xs">
                                    {{ $endorsement->deadline->format('d M Y') }}
                                    @if ($isOverdue)
                                        <span class="block text-[10px] uppercase font-bold text-kerajaan-red">Lewat Deadline</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <x-dashboard.status-badge :status="$endorsement->status" />
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('superadmin.endorsements.show', $endorsement) }}" class="inline-flex items-center gap-1 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
                                    <span>Detail</span>
                                    <i class="bi bi-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <i class="bi bi-briefcase mb-3 block text-3xl text-kerajaan-muted/30"></i>
                                <p class="text-sm font-bold text-kerajaan-dark font-heading">Belum Ada Endorsement</p>
                                <p class="mt-1 text-xs text-kerajaan-muted">Assignment endorsement akan muncul saat Anda menugaskan KOL ke campaign.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($endorsements->hasPages())
            <div class="border-t border-kerajaan-dark/5 p-4">
                {{ $endorsements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
