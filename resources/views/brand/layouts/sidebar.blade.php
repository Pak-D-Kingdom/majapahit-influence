<aside id="brand-dashboard-sidebar" class="fixed inset-y-0 left-0 z-40 flex h-screen w-72 -translate-x-full flex-col border-r border-white/10 bg-[#081d47] text-slate-300 transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen lg:w-72 lg:shrink-0 lg:translate-x-0">
    {{-- Brand Header --}}
    <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('assets/landing/images/logo/logokerajaannew.png') }}" alt="KERAJAAN" class="h-9 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform">
            <div class="leading-tight">
                <p class="font-heading text-sm font-extrabold tracking-[0.16em] text-white">KERAJAAN</p>
                <p class="text-[9px] font-bold tracking-[0.22em] text-[#1698f6] uppercase">BRAND PORTAL</p>
            </div>
        </a>
    </div>

    {{-- Nav Links --}}
    <div class="flex-1 overflow-y-auto px-4 py-6">
        <div class="mb-3 flex items-center justify-between px-3">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#78a5d6]">Menu Brand</p>
            <span class="inline-flex items-center gap-1 rounded-full bg-blue-500/20 px-2 py-0.5 text-[10px] font-medium text-[#1698f6]">
                <span class="size-1.5 rounded-full bg-[#1698f6] animate-pulse motion-reduce:animate-none"></span>
                Brand Partner
            </span>
        </div>

        <nav class="space-y-1.5" aria-label="Navigasi Utama Brand">
            @php
                $menuItems = [
                    ['route' => 'brand.dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill'],
                    ['route' => 'brand.products.index', 'label' => 'Katalog Produk', 'icon' => 'bi-box-seam-fill'],
                    ['route' => 'brand.campaigns.index', 'label' => 'Campaign', 'icon' => 'bi-megaphone-fill'],
                    ['route' => 'brand.endorsements.index', 'label' => 'Endorsement', 'icon' => 'bi-clipboard-check-fill'],
                ];
            @endphp

            @foreach ($menuItems as $item)
                @php
                    $isActive = request()->routeIs($item['route'].'*');
                    $url = Route::has($item['route']) ? route($item['route']) : '#';
                @endphp
                <a href="{{ $url }}"
                   class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1698f6] {{ $isActive ? 'bg-gradient-to-r from-[#0b64d4] to-[#1698f6] text-white font-semibold shadow-md shadow-blue-500/25' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi {{ $item['icon'] }} text-base {{ $isActive ? 'text-white' : 'text-[#78a5d6] group-hover:text-white' }}" aria-hidden="true"></i>
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
        <div class="rounded-xl border border-white/10 bg-white/5 p-3.5 backdrop-blur-xs">
            <div class="flex items-center gap-3">
                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#0b64d4] to-[#1698f6] text-xs font-bold text-white font-heading shadow-xs">
                    {{ strtoupper(substr(auth()->user()->brand->name ?? auth()->user()->name ?? 'B', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-white font-heading">{{ auth()->user()->brand->name ?? auth()->user()->name ?? 'Brand Partner' }}</p>
                    <p class="mt-0.5 truncate text-[11px] text-slate-400">{{ auth()->user()->email ?? 'brand@majapahit.com' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3 pt-2.5 border-t border-white/10">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-white/5 py-1.5 text-xs font-medium text-slate-300 hover:bg-rose-500/20 hover:text-rose-300 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 cursor-pointer">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    Keluar Akun
                </button>
            </form>
        </div>
    </div>
</aside>
