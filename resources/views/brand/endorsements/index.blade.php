@extends('brand.layouts.app')

@section('title', 'Semua Endorsement')
@section('page-title', 'Daftar Endorsement')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#421b13] font-heading">Endorsement KOL</h1>
            <p class="mt-1 text-sm text-[#765f58]">Monitor seluruh progres endorsement KOL pada campaign Anda.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
        @if ($endorsements->isEmpty())
            <div class="flex h-56 flex-col items-center justify-center p-6 text-center text-[#765f58]">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-[#fff9f4] text-[#d57028]">
                    <i class="bi bi-people text-2xl"></i>
                </div>
                <p class="mt-3 text-sm font-semibold text-[#421b13]">Belum ada endorsement</p>
                <p class="mt-1 text-xs text-[#765f58]">Endorsement dari KOL yang bekerja sama dengan Anda akan tampil di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-[#421b13]/6 bg-[#fff9f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                        <tr>
                            <th class="px-5 py-4 sm:px-6">KOL</th>
                            <th class="px-5 py-4">Campaign</th>
                            <th class="px-5 py-4">Tipe Konten</th>
                            <th class="px-5 py-4">Deadline</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#421b13]/6">
                        @foreach ($endorsements as $endorsement)
                            <tr class="group hover:bg-[#fff9f4]/60 transition">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 shrink-0 overflow-hidden rounded-full bg-[#fff9f4] border border-[#421b13]/10">
                                            @if ($endorsement->kolProfile->avatar_path)
                                                <img src="{{ Storage::url($endorsement->kolProfile->avatar_path) }}" alt="{{ $endorsement->kolProfile->nickname ?? $endorsement->kolProfile->user->name }}" class="h-full w-full object-cover" />
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-xs font-bold text-[#d57028]">
                                                    {{ substr($endorsement->kolProfile->nickname ?? $endorsement->kolProfile->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-[#421b13] block font-heading">
                                                {{ $endorsement->kolProfile->nickname ?? $endorsement->kolProfile->user->name }}
                                            </span>
                                            <span class="text-xs text-[#765f58]">{{ $endorsement->kolProfile->social_media_platform ?? 'Platform' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-medium text-[#421b13] block">{{ $endorsement->campaign->name }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-[#765f58]">
                                    {{ str($endorsement->content_type)->replace('_', ' ')->title() }}
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
            @if ($endorsements->hasPages())
                <div class="border-t border-[#421b13]/6 p-5 sm:px-6">
                    {{ $endorsements->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
