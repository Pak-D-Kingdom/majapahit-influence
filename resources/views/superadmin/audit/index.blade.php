@extends('superadmin.layouts.app')

@section('title', 'Audit Trail | Superadmin')
@section('page-title', 'Audit Trail')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <p class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Keamanan & Kepatuhan</p>
        <h2 class="mt-1 text-2xl sm:text-3xl font-black tracking-tight text-[#071d49] font-heading">Audit Trail</h2>
        <p class="mt-1 text-xs text-slate-500">Catatan rekam jejak aktivitas kritis sistem yang bersifat permanen dan read-only.</p>
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Cari action..." class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-[#0b64d4] focus:ring-2 focus:ring-blue-500/20 focus:outline-hidden">

            <select name="entity_type" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-[#0b64d4] focus:ring-2 focus:ring-blue-500/20 focus:outline-hidden">
                <option value="">Semua Entity</option>
                @foreach ($entityTypes as $entity)
                    <option value="{{ $entity }}" @selected(request('entity_type') === $entity)>{{ str($entity)->replace('_', ' ')->title() }}</option>
                @endforeach
            </select>

            <select name="user_id" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-[#0b64d4] focus:ring-2 focus:ring-blue-500/20 focus:outline-hidden">
                <option value="">Semua Operator</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-[#0b64d4] focus:ring-2 focus:ring-blue-500/20 focus:outline-hidden" aria-label="Tanggal mulai">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-[#0b64d4] focus:ring-2 focus:ring-blue-500/20 focus:outline-hidden" aria-label="Tanggal akhir">
        </div>

        <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3">
            <button type="submit" class="rounded-xl bg-[#071d49] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#0c3685] font-heading">
                Terapkan Filter
            </button>
            <a href="{{ route('superadmin.audit.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50 font-heading">
                Reset
            </a>
        </div>
    </form>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-y border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500 font-heading">
                    <tr>
                        <th class="px-5 py-3.5">Waktu Eksekusi</th>
                        <th class="px-5 py-3.5">Operator</th>
                        <th class="px-5 py-3.5">Aksi / Event</th>
                        <th class="px-5 py-3.5">Entity</th>
                        <th class="px-5 py-3.5">Rekam Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="align-top transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-4 text-xs font-semibold text-slate-500">
                                {{ $log->created_at->format('d M Y') }}
                                <span class="block text-[11px] text-slate-400">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-[#071d49] font-heading">{{ $log->user?->name ?: 'Sistem Otomatis' }}</p>
                                @if ($log->ip_address)
                                    <p class="mt-0.5 text-[11px] font-mono text-slate-400">{{ $log->ip_address }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-bold text-[#0b64d4] font-heading">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs font-semibold text-[#071d49]">
                                {{ str($log->entity_type)->replace('_', ' ')->title() }} <span class="text-slate-400">#{{ $log->entity_id ?: '-' }}</span>
                            </td>
                            <td class="max-w-sm px-5 py-4 text-xs text-slate-500">
                                <details class="group">
                                    <summary class="cursor-pointer text-xs font-bold text-[#0b64d4] hover:text-[#071d49] font-heading">
                                        Lihat Detail Nilai
                                    </summary>
                                    <div class="mt-2 space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs">
                                        @if ($log->old_values)
                                            <div>
                                                <p class="font-bold uppercase tracking-wider text-rose-600 text-[10px] font-heading">Sebelum:</p>
                                                <pre class="mt-1 max-h-32 overflow-x-auto rounded-lg bg-white p-2 font-mono text-[11px] text-slate-800 border border-slate-200">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                        @if ($log->new_values)
                                            <div>
                                                <p class="font-bold uppercase tracking-wider text-emerald-700 text-[10px] font-heading">Sesudah:</p>
                                                <pre class="mt-1 max-h-32 overflow-x-auto rounded-lg bg-white p-2 font-mono text-[11px] text-slate-800 border border-slate-200">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <i class="bi bi-shield-check mb-3 block text-3xl text-slate-300"></i>
                                <p class="text-sm font-bold text-[#071d49] font-heading">Belum Ada Catatan Audit Log</p>
                                <p class="mt-1 text-xs text-slate-400">Aktivitas perubahan penting dalam sistem akan dicatat di halaman ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="border-t border-slate-100 p-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
