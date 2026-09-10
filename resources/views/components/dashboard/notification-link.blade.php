@props(['route', 'count' => 0, 'label' => 'Notifikasi'])

<a href="{{ route($route) }}" aria-label="{{ $label }}{{ $count > 0 ? ': '.$count.' belum dibaca' : '' }}" {{ $attributes->merge(['class' => 'relative flex size-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs transition hover:border-blue-300 hover:bg-blue-50/40 hover:text-[#0b64d4] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]']) }}>
    <i class="bi bi-bell text-lg"></i>
    @if ($count > 0)
        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-r from-rose-500 to-red-600 px-1 text-center text-[10px] font-bold leading-none text-white ring-2 ring-white shadow-xs">
            {{ $count > 9 ? '9+' : $count }}
        </span>
    @endif
</a>
