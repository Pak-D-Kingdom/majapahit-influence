@props(['status'])

@php
    $styles = [
        'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'content_approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'dicairkan' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/80',
        'pending_review' => 'bg-amber-50 text-amber-700 border-amber-200/80',
        'in_progress' => 'bg-blue-50 text-[#0b64d4] border-blue-200/80',
        'assigned' => 'bg-sky-50 text-sky-700 border-sky-200/80',
        'content_submitted' => 'bg-indigo-50 text-indigo-700 border-indigo-200/80',
        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200/80',
        'content_rejected' => 'bg-rose-50 text-rose-700 border-rose-200/80',
        'nonaktif' => 'bg-slate-100 text-slate-600 border-slate-200/80',
    ];
    $labels = [
        'pending_review' => 'Menunggu review',
        'in_progress' => 'Sedang berjalan',
        'content_submitted' => 'Konten dikirim',
        'content_approved' => 'Konten disetujui',
        'content_rejected' => 'Konten ditolak',
        'assigned' => 'Ditugaskan',
        'dicairkan' => 'Sudah dicairkan',
    ];
    $key = strtolower((string) $status);
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $styles[$key] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
    <span class="size-1.5 rounded-full bg-current"></span>
    {{ $labels[$key] ?? str($status)->replace('_', ' ')->title() }}
</span>
