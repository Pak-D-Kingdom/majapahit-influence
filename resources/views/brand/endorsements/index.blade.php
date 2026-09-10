@extends('brand.layouts.app')

@section('title', 'Semua Endorsement')
@section('page-title', 'Daftar Endorsement')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#071d49] font-heading">Endorsement KOL</h1>
            <p class="mt-1 text-sm text-slate-500">Monitor seluruh progres endorsement KOL pada campaign Anda.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        @if ($endorsements->isEmpty())
            <div class="flex h-56 flex-col items-center justify-center p-6 text-center text-slate-500">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-slate-50 text-[#0b64d4]">
                    <i class="bi bi-people text-2xl"></i>
                </div>
                <p class="mt-3 text-sm font-semibold text-[#071d49]">Belum ada endorsement</p>
                <p class="mt-1 text-xs text-slate-500">Endorsement dari KOL yang bekerja sama dengan Anda akan tampil di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 font-heading">
                        <tr>
                            <th class="px-5 py-4 sm:px-6">KOL</th>
                            <th class="px-5 py-4">Campaign</th>
                            <th class="px-5 py-4">Tipe Konten</th>
                            <th class="px-5 py-4">Deadline</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($endorsements as $endorsement)
                            <tr class="group hover:bg-slate-50/70 transition">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 shrink-0 overflow-hidden rounded-full bg-slate-100 border border-slate-200">
                                            @if ($endorsement->kolProfile->avatar_path)
                                                <img src="{{ Storage::url($endorsement->kolProfile->avatar_path) }}" alt="{{ $endorsement->kolProfile->nickname ?? $endorsement->kolProfile->user->name }}" class="h-full w-full object-cover" />
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-xs font-bold text-[#0b64d4]">
                                                    {{ substr($endorsement->kolProfile->nickname ?? $endorsement->kolProfile->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-[#071d49] group-hover:text-[#0b64d4] transition block font-heading">
                                                {{ $endorsement->kolProfile->nickname ?? $endorsement->kolProfile->user->name }}
                                            </span>
                                            <span class="text-xs text-slate-500">{{ $endorsement->kolProfile->social_media_platform ?? 'Platform' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-medium text-[#071d49] block">{{ $endorsement->campaign->name }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-slate-500">
                                    {{ str($endorsement->content_type)->replace('_', ' ')->title() }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-xs font-medium text-slate-500">
                                    <i class="bi bi-calendar3 mr-1 text-[#0b64d4]"></i>
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
                <div class="border-t border-slate-100 p-5 sm:px-6">
                    {{ $endorsements->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
