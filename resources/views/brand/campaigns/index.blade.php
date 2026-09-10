@extends('brand.layouts.app')

@section('title', 'Semua Campaign')
@section('page-title', 'Daftar Campaign')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#071d49] font-heading">Campaign Anda</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau seluruh daftar campaign yang Anda miliki.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        @if ($campaigns->isEmpty())
            <div class="flex h-56 flex-col items-center justify-center p-6 text-center text-slate-500">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-slate-50 text-[#0b64d4]">
                    <i class="bi bi-megaphone text-2xl"></i>
                </div>
                <p class="mt-3 text-sm font-semibold text-[#071d49]">Belum ada campaign</p>
                <p class="mt-1 text-xs text-slate-500">Campaign yang Anda buat akan tampil di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-heading">
                        <tr>
                            <th class="px-5 py-4 sm:px-6">Nama Campaign</th>
                            <th class="px-5 py-4">Periode</th>
                            <th class="px-5 py-4">Budget</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($campaigns as $campaign)
                            <tr class="group hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="font-bold text-[#071d49] group-hover:text-[#0b64d4] transition block font-heading">
                                        {{ $campaign->name }}
                                    </span>
                                    @if ($campaign->description)
                                        <p class="mt-1 text-xs text-slate-500 line-clamp-1">{{ $campaign->description }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-slate-500">
                                    <i class="bi bi-calendar3 mr-1 text-[#0b64d4]"></i>
                                    {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} - 
                                    {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-slate-800 font-semibold">
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
                <div class="border-t border-slate-100 p-5 sm:px-6">
                    {{ $campaigns->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
