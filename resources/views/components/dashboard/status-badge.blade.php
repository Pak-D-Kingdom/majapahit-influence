@props(['status'])

@php
    $styles = [
        'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'content_approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'dicairkan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'pending' => 'bg-[#fec200]/20 text-[#b86021] border-[#fec200]/50',
        'pending_review' => 'bg-[#fec200]/20 text-[#b86021] border-[#fec200]/50',
        'in_progress' => 'bg-[#d57028]/10 text-[#d57028] border-[#d57028]/30',
        'assigned' => 'bg-[#d57028]/10 text-[#d57028] border-[#d57028]/30',
        'content_submitted' => 'bg-[#d57028]/10 text-[#d57028] border-[#d57028]/30',
        'rejected' => 'bg-[#d5282d]/10 text-[#d5282d] border-[#d5282d]/30',
        'content_rejected' => 'bg-[#d5282d]/10 text-[#d5282d] border-[#d5282d]/30',
        'nonaktif' => 'bg-[#f7eee8] text-[#765f58] border-[#421b13]/10',
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

<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold {{ $styles[$key] ?? 'bg-[#f7eee8] text-[#765f58] border-[#421b13]/10' }}">
    <span class="size-1.5 rounded-full bg-current"></span>
    {{ $labels[$key] ?? str($status)->replace('_', ' ')->title() }}
</span>
