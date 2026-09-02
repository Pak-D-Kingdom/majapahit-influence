@props(['label', 'value', 'icon' => 'bi-bar-chart', 'accent' => 'indigo', 'hint' => null])

<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/50">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950">{{ $value }}</p>
            @if ($hint)
                <p class="mt-2 text-xs text-slate-400">{{ $hint }}</p>
            @endif
        </div>
        <span class="flex size-11 items-center justify-center rounded-xl bg-{{ $accent }}-50 text-{{ $accent }}-600">
            <i class="bi {{ $icon }} text-xl"></i>
        </span>
    </div>
</article>
