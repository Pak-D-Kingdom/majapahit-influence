@props(['label', 'value', 'icon' => 'bi-bar-chart', 'accent' => 'blue', 'hint' => null])

@php
    $accentStyles = [
        'blue' => 'bg-blue-50 text-[#0b64d4] border border-blue-100',
        'orange' => 'bg-blue-50 text-[#0b64d4] border border-blue-100',
        'indigo' => 'bg-blue-50 text-[#0b64d4] border border-blue-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
        'amber' => 'bg-amber-50 text-amber-600 border border-amber-100',
        'rose' => 'bg-rose-50 text-rose-600 border border-rose-100',
    ];
    $badgeStyle = $accentStyles[$accent] ?? 'bg-blue-50 text-[#0b64d4] border border-blue-100';
@endphp

<article class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md hover:shadow-blue-950/5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 font-heading">{{ $label }}</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-[#0c3685] font-heading">{{ $value }}</p>
            @if ($hint)
                <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="size-1.5 rounded-full bg-[#0b64d4]"></span>
                    {{ $hint }}
                </p>
            @endif
        </div>
        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl {{ $badgeStyle }} transition duration-200 group-hover:scale-105 shadow-xs">
            <i class="bi {{ $icon }} text-xl"></i>
        </span>
    </div>
</article>

