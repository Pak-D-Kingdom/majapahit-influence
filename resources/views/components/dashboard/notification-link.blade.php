@props(['route', 'count' => 0, 'label' => 'Notifikasi'])

<a href="{{ route($route) }}" aria-label="{{ $label }}{{ $count > 0 ? ': '.$count.' belum dibaca' : '' }}" {{ $attributes->merge(['class' => 'relative flex size-10 items-center justify-center rounded-xl border border-[#421b13]/8 bg-white text-[#765f58] shadow-xs transition hover:border-[#d57028]/30 hover:bg-[#f7eee8] hover:text-[#421b13]']) }}>
    <i class="bi bi-bell text-lg"></i>
    @if ($count > 0)
        <span class="absolute -right-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-r from-[#d57028] to-[#d5282d] px-1 text-center text-[10px] font-bold leading-none text-white ring-2 ring-white shadow-xs">
            {{ $count > 9 ? '9+' : $count }}
        </span>
    @endif
</a>
