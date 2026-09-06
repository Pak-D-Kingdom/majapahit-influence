@extends('kol.layouts.app')

@section('title', 'Endorsement Saya')
@section('page-title', 'Endorsement Saya')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#d57028] font-heading">Progress Kolaborasi</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#421b13] font-heading">Endorsement Saya</h2>
            <p class="mt-1 text-sm text-[#765f58]">Pantau semua brief campaign, tenggat waktu konten, dan upload bukti pekerjaanmu.</p>
        </div>
    </div>

    {{-- Tabs Filter --}}
    <div class="mb-6 flex overflow-x-auto rounded-2xl border border-[#421b13]/8 bg-white p-1.5 shadow-xs">
        @php
            $tabs = [
                'aktif' => ['label' => 'Sedang Berjalan', 'icon' => 'bi-play-circle-fill'],
                'mendatang' => ['label' => 'Jadwal Mendatang', 'icon' => 'bi-calendar-event-fill'],
                'riwayat' => ['label' => 'Riwayat Selesai', 'icon' => 'bi-check-circle-fill'],
            ];
        @endphp

        @foreach ($tabs as $key => $item)
            @php $isSelected = ($tab ?? 'aktif') === $key; @endphp
            <a href="{{ route('kol.endorsements.index', ['tab' => $key]) }}"
               class="flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-xs font-bold transition font-heading {{ $isSelected ? 'bg-gradient-to-r from-[#d57028] to-[#d5282d] text-white shadow-sm shadow-[#d57028]/25' : 'text-[#765f58] hover:bg-[#fff9f4] hover:text-[#421b13]' }}">
                <i class="bi {{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Endorsement List --}}
    <div class="space-y-3.5">
        @forelse ($endorsements as $endorsement)
            <a href="{{ route('kol.endorsements.show', $endorsement) }}"
               class="group block rounded-2xl border border-[#421b13]/8 bg-white p-5 shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-[#d57028]/30 hover:shadow-md hover:shadow-[#421b13]/5">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    {{-- Left info --}}
                    <div class="flex items-start sm:items-center gap-4">
                        <div class="flex size-13 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#d57028]/15 to-[#d5282d]/15 text-[#d57028] transition duration-200 group-hover:scale-105 group-hover:from-[#d57028] group-hover:to-[#d5282d] group-hover:text-white">
                            <i class="bi bi-megaphone-fill text-xl"></i>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-md bg-[#fff9f4] px-2 py-0.5 text-[11px] font-bold text-[#b86021] border border-[#d57028]/20">
                                    {{ $endorsement->campaign->brand->name }}
                                </span>
                                <span class="text-xs text-[#765f58]">•</span>
                                <span class="text-xs font-medium text-[#765f58]">
                                    {{ str($endorsement->content_type)->replace('_', ' ')->title() }}
                                </span>
                            </div>
                            <h3 class="mt-1.5 text-base font-extrabold text-[#421b13] group-hover:text-[#d57028] transition font-heading">
                                {{ $endorsement->campaign->name }}
                            </h3>
                            <p class="mt-1 text-xs font-semibold text-emerald-600">
                                Fee: Rp {{ number_format($endorsement->fee, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Right status & deadline --}}
                    <div class="flex items-center justify-between gap-5 sm:justify-end sm:text-right border-t border-[#421b13]/6 pt-3 sm:border-0 sm:pt-0">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Deadline</p>
                            <p class="mt-0.5 text-xs font-bold text-[#421b13]">
                                <i class="bi bi-calendar3 text-[#d57028] mr-1"></i>
                                {{ $endorsement->deadline->format('d M Y') }}
                            </p>
                        </div>
                        <div class="shrink-0">
                            <x-dashboard.status-badge :status="$endorsement->status" />
                        </div>
                        <div class="hidden sm:flex size-8 items-center justify-center rounded-lg bg-[#fff9f4] text-[#d57028] group-hover:bg-[#d57028] group-hover:text-white transition">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="rounded-3xl border border-dashed border-[#421b13]/15 bg-white py-16 px-6 text-center shadow-xs">
                <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-[#fff9f4] text-[#d57028]">
                    <i class="bi bi-briefcase text-3xl"></i>
                </div>
                <h4 class="mt-4 text-base font-extrabold text-[#421b13] font-heading">Tidak ada endorsement</h4>
                <p class="mt-1 text-xs text-[#765f58] max-w-sm mx-auto">
                    Belum ada endorsement yang masuk di kategori tab ini. Pantau terus notifikasi untuk assignment baru.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($endorsements->hasPages())
        <div class="mt-6">
            {{ $endorsements->links() }}
        </div>
    @endif
@endsection
