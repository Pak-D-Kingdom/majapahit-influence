@extends('superadmin.layouts.app')

@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')

@section('content')
<div class="mb-6">
    <p class="text-sm text-slate-500">Catatan aktivitas kritis yang bersifat read-only.</p>
    <h2 class="mt-1 text-2xl font-bold text-slate-950">Audit Trail</h2>
</div>

<form method="GET" class="mb-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-5">
        <input name="action" value="{{ request('action') }}" placeholder="Cari action..." class="rounded-xl border-slate-200 text-sm">
        <select name="entity_type" class="rounded-xl border-slate-200 text-sm">
            <option value="">Semua entity</option>
            @foreach ($entityTypes as $entity)
                <option value="{{ $entity }}" @selected(request('entity_type') === $entity)>{{ str($entity)->replace('_', ' ')->title() }}</option>
            @endforeach
        </select>
        <select name="user_id" class="rounded-xl border-slate-200 text-sm">
            <option value="">Semua user</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border-slate-200 text-sm" aria-label="Tanggal mulai">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border-slate-200 text-sm" aria-label="Tanggal akhir">
    </div>
    <div class="mt-3 flex gap-2">
        <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Terapkan filter</button>
        <a href="{{ route('superadmin.audit.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
    </div>
</form>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400"><tr><th class="px-5 py-3">Waktu</th><th class="px-5 py-3">User</th><th class="px-5 py-3">Action</th><th class="px-5 py-3">Entity</th><th class="px-5 py-3">Perubahan</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr class="align-top">
                        <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4"><p class="font-semibold text-slate-700">{{ $log->user?->name ?: 'Sistem' }}</p>@if($log->ip_address)<p class="mt-1 text-xs text-slate-400">{{ $log->ip_address }}</p>@endif</td>
                        <td class="px-5 py-4"><span class="rounded-lg bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700">{{ $log->action }}</span></td>
                        <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $log->entity_type }} #{{ $log->entity_id ?: '-' }}</td>
                        <td class="max-w-sm px-5 py-4 text-xs text-slate-500"><details><summary class="cursor-pointer font-semibold text-indigo-600">Lihat detail</summary><div class="mt-2 space-y-2"><div><p class="font-semibold text-slate-400">Sebelum</p><pre class="overflow-x-auto rounded-lg bg-slate-50 p-2">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-' }}</pre></div><div><p class="font-semibold text-slate-400">Sesudah</p><pre class="overflow-x-auto rounded-lg bg-slate-50 p-2">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-' }}</pre></div></div></details></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-16 text-center text-sm text-slate-400">Belum ada audit log.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-slate-100 px-5 py-4">{{ $logs->links() }}</div>
</div>
@endsection
