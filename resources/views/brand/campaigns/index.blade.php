@extends('brand.layouts.app')

@section('title', 'Semua Campaign')
@section('page-title', 'Daftar Campaign')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#421b13] font-heading">Campaign Anda</h1>
            <p class="mt-1 text-sm text-[#765f58]">Pantau seluruh daftar campaign yang Anda miliki.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
        @if ($campaigns->isEmpty())
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
                            <th class="px-5 py-4 sm:px-6">Nama Campaign</th>
                            <th class="px-5 py-4">Periode</th>
                            <th class="px-5 py-4">Budget</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#421b13]/6">
                        @foreach ($campaigns as $campaign)
                            <tr class="group hover:bg-[#fff9f4]/60 transition">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="font-bold text-[#421b13] block font-heading">
                                        {{ $campaign->name }}
                                    </span>
                                    @if ($campaign->description)
                                        <p class="mt-1 text-xs text-[#765f58] line-clamp-1">{{ $campaign->description }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-[#765f58]">
                                    <i class="bi bi-calendar3 mr-1 text-[#d57028]"></i>
                                    {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} - 
                                    {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-[#421b13]">
                                    Rp {{ number_format($campaign->budget, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <x-dashboard.status-badge :status="$campaign->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($campaigns->hasPages())
                <div class="border-t border-[#421b13]/6 p-5 sm:px-6">
                    {{ $campaigns->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
