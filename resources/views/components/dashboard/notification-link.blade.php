@props(['route', 'count' => null, 'label' => 'Notifikasi'])

@php
    if ($count === null && auth()->check()) {
        try {
            $count = auth()->user()->unreadNotifications()->count();
        } catch (\Throwable) {
            $count = 0;
        }
    }
    $count = (int) ($count ?? 0);
@endphp

<a href="{{ route($route) }}" aria-label="{{ $label }}{{ $count > 0 ? ': '.$count.' belum dibaca' : '' }}" {{ $attributes->merge(['class' => 'relative flex size-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs transition hover:border-blue-300 hover:bg-blue-50/40 hover:text-[#0b64d4] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]']) }}>
    <i class="bi bi-bell text-lg"></i>
    @if ($count > 0)
        <span class="absolute -top-1 -right-1 flex size-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
            <span class="relative inline-flex size-3 rounded-full bg-rose-600 border-2 border-white shadow-xs"></span>
        </span>
    @endif
</a>
