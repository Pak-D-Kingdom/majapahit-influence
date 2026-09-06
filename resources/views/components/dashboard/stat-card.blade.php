@props(['label', 'value', 'icon' => 'bi-bar-chart', 'accent' => 'orange', 'hint' => null])

@php
    $accentStyles = [
        'orange' => 'bg-[#d57028]/10 text-[#d57028]',
        'indigo' => 'bg-[#d57028]/10 text-[#d57028]',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'amber' => 'bg-[#fec200]/20 text-[#b86021]',
        'rose' => 'bg-[#d5282d]/10 text-[#d5282d]',
    ];
    $badgeStyle = $accentStyles[$accent] ?? 'bg-[#d57028]/10 text-[#d57028]';
@endphp

<article class="group rounded-2xl border border-[#421b13]/8 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#d57028]/30 hover:shadow-md hover:shadow-[#421b13]/5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">{{ $label }}</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-[#421b13] font-heading">{{ $value }}</p>
            @if ($hint)
                <p class="mt-2 flex items-center gap-1.5 text-xs text-[#765f58]">
                    <span class="size-1.5 rounded-full bg-[#d57028]/60"></span>
                    {{ $hint }}
                </p>
            @endif
        </div>
        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl {{ $badgeStyle }} transition duration-200 group-hover:scale-105">
            <i class="bi {{ $icon }} text-xl"></i>
        </span>
    </div>
</article>

