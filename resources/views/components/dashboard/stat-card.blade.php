@props(['label', 'value', 'icon' => 'bi-bar-chart', 'accent' => 'orange', 'hint' => null])

@php
    $accentStyles = [
        'orange' => 'bg-blue-50 text-[#0b64d4]',
        'blue' => 'bg-blue-50 text-[#0b64d4]',
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'rose' => 'bg-rose-50 text-rose-600',
    ];
    $badgeStyle = $accentStyles[$accent] ?? 'bg-blue-50 text-[#0b64d4]';
@endphp

<article class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md hover:shadow-blue-950/5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 font-heading">{{ $label }}</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-[#0c3685] font-heading">{{ $value }}</p>
            @if ($hint)
                <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="size-1.5 rounded-full bg-[#1698f6]"></span>
                    {{ $hint }}
                </p>
            @endif
        </div>
        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl {{ $badgeStyle }} transition duration-200 group-hover:scale-105">
            <i class="bi {{ $icon }} text-xl"></i>
        </span>
    </div>
</article>
