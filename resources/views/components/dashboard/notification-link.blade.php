@props(['route', 'count' => 0, 'label' => 'Notifikasi'])

<a href="{{ route($route) }}" aria-label="{{ $label }}{{ $count > 0 ? ': '.$count.' belum dibaca' : '' }}" {{ $attributes->merge(['class' => 'relative rounded-xl p-2.5 text-slate-500 transition hover:bg-slate-100']) }}>
    <i class="bi bi-bell text-lg"></i>
    @if ($count > 0)
        <span class="absolute -right-1 -top-1 min-w-5 rounded-full bg-amber-400 px-1.5 py-0.5 text-center text-[10px] font-bold leading-4 text-slate-950 ring-2 ring-white">{{ $count > 9 ? '9+' : $count }}</span>
    @endif
</a>
