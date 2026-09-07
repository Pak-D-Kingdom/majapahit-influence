<aside id="kol-dashboard-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-[#421b13]/30 bg-[#240e09] text-white/80 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
    {{-- Brand Header --}}
    <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#d57028] to-[#d5282d] text-sm font-extrabold text-white font-heading shadow-md shadow-[#d57028]/30 group-hover:scale-105 transition">
                MI
            </div>
            <div class="flex flex-col leading-tight">
                <span class="font-heading text-[10px] font-bold tracking-[2px] text-[#fec200]">MAJAPAHIT</span>
                <strong class="font-heading text-[13px] font-extrabold tracking-[1.5px] text-white">INFLUENCE</strong>
            </div>
        </a>
    </div>

    {{-- Nav Links --}}
    <div class="flex-1 overflow-y-auto px-4 py-6">
        <div class="mb-3 flex items-center justify-between px-3">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#d57028]">Menu KOL</p>
            <span class="inline-flex items-center gap-1 rounded-full bg-[#d57028]/20 px-2 py-0.5 text-[10px] font-medium text-[#fec200]">
                <span class="size-1.5 rounded-full bg-[#fec200] animate-pulse motion-reduce:animate-none"></span>
                KOL Portal
            </span>
        </div>

        <nav class="space-y-1.5" aria-label="Navigasi Utama KOL">
            @php
                $menuItems = [
                    ['route' => 'kol.dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill'],
                    ['route' => 'catalog.index', 'label' => 'Katalog & Bank Konten', 'icon' => 'bi-shop'],
                    ['route' => 'kol.endorsements.index', 'label' => 'Endorsement', 'icon' => 'bi-megaphone-fill'],
                    ['route' => 'kol.commissions.index', 'label' => 'Komisi Saya', 'icon' => 'bi-wallet2'],
                    ['route' => 'kol.profile.show', 'label' => 'Profil Saya', 'icon' => 'bi-person-circle'],
                    ['route' => 'kol.notifications.index', 'label' => 'Notifikasi', 'icon' => 'bi-bell-fill'],
                ];
            @endphp

            @foreach ($menuItems as $item)
                @php
                    $isActive = request()->routeIs($item['route'].'*');
                    $url = Route::has($item['route']) ? route($item['route']) : '#';
                @endphp
                <a href="{{ $url }}"
                   class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#fec200] focus-visible:ring-offset-2 focus-visible:ring-offset-[#240e09] {{ $isActive ? 'bg-gradient-to-r from-[#d57028] to-[#d5282d] text-white font-semibold shadow-md shadow-[#d57028]/25' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi {{ $item['icon'] }} text-base {{ $isActive ? 'text-white' : 'text-[#d57028] group-hover:text-white' }}" aria-hidden="true"></i>
                        <span class="font-body">{{ $item['label'] }}</span>
                    </div>
                    @if ($isActive)
                        <i class="bi bi-chevron-right text-xs text-white/80" aria-hidden="true"></i>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Footer Box --}}
    <div class="border-t border-white/10 p-4">
        <div class="rounded-xl border border-white/5 bg-[#31140d]/80 p-3.5 backdrop-blur-xs">
            <div class="flex items-center gap-3">
                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#d57028]/20 text-[#fec200]">
                    <i class="bi bi-stars text-lg" aria-hidden="true"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-white font-heading">Program KOL Pak De</p>
                    <p class="mt-0.5 truncate text-[11px] text-white/70">Tumbuh bersama brand</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3 pt-2.5 border-t border-white/5">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-white/5 py-1.5 text-xs font-medium text-white/70 hover:bg-rose-500/20 hover:text-rose-300 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    Keluar Akun
                </button>
            </form>
        </div>
    </div>
</aside>
