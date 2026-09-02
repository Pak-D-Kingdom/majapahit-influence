@props(['status'])

@php
    $styles = [
        'aktif' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'selesai' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'pending_review' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'in_progress' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'assigned' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'rejected' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'nonaktif' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
    ];
    $labels = [
        'pending_review' => 'Menunggu review',
        'in_progress' => 'Sedang berjalan',
        'content_submitted' => 'Konten dikirim',
        'content_approved' => 'Konten disetujui',
    ];
    $key = strtolower((string) $status);
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $styles[$key] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20' }}">
    {{ $labels[$key] ?? str($status)->replace('_', ' ')->title() }}
</span>
