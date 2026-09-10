<aside id="dashboard-sidebar" class="fixed inset-y-0 left-0 z-40 flex h-screen w-72 -translate-x-full flex-col border-r border-[#421b13]/40 bg-[#190906] text-[#f7eee8]/80 transition-transform lg:sticky lg:top-0 lg:h-screen lg:w-72 lg:shrink-0 lg:translate-x-0">
    <div class="flex h-20 items-center gap-3 border-b border-white/8 px-6">
        <div class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-tr from-[#d57028] via-[#d5282d] to-[#fec200] font-heading font-extrabold text-white shadow-md shadow-[#d57028]/20">
            MI
        </div>
        <div class="leading-tight">
            <p class="font-heading text-sm font-extrabold tracking-[0.18em] text-[#fec200]">MAJAPAHIT</p>
            <p class="text-[10px] font-semibold tracking-[0.25em] text-white/80">INFLUENCE</p>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-6">
        <p class="mb-3 px-3 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#fec200]/70 font-heading">Workspace</p>
        <nav class="space-y-1" aria-label="Menu Utama">
            @php
                $workspaceItems = [
                    ['superadmin.dashboard', 'Dashboard', 'bi-grid-1x2-fill'], 
                    ['superadmin.registrations.index', 'Pendaftaran KOL', 'bi-person-plus-fill'], 
                    ['superadmin.brand-registrations.index', 'Pendaftaran Brand', 'bi-building-add'],
                    ['superadmin.kol.index', 'Database KOL', 'bi-people-fill'], 
                    ['superadmin.brands.index', 'Brand & Klien', 'bi-building'], 
                    ['superadmin.product-verifications.index', 'Verifikasi Produk', 'bi-check2-square'],
                    ['superadmin.products.index', 'Katalog Produk', 'bi-shop'],
                    ['superadmin.campaigns.index', 'Campaign', 'bi-megaphone-fill'], 
                    ['superadmin.endorsements.index', 'Endorsement', 'bi-clipboard-check'], 
                    ['superadmin.commissions.index', 'Komisi & Pencairan', 'bi-wallet2'],
                ];
            @endphp
            @foreach ($workspaceItems as [$route, $label, $icon])
                @php
                    $isActive = request()->routeIs($route, $route.'.*', str_replace('.index', '', $route).'.*');
                @endphp
                <a href="{{ Route::has($route) ? route($route) : '#' }}" 
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#fec200] {{ $isActive ? 'bg-gradient-to-r from-[#d57028] to-[#b86021] font-semibold text-white shadow-md shadow-[#d57028]/25' : 'text-[#f7eee8]/75 hover:bg-white/6 hover:text-white' }}">
                    <i class="bi {{ $icon }} text-base {{ $isActive ? 'text-white' : 'text-[#d57028]' }}"></i>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>

        <p class="mb-3 mt-8 px-3 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#fec200]/70 font-heading">Sistem</p>
        <nav class="space-y-1" aria-label="Menu Sistem">
            @php
                $systemItems = [
                    ['superadmin.reports.index', 'Laporan & Analisis', 'bi-file-earmark-bar-graph-fill'],
                    ['superadmin.notifications.index', 'Pusat Notifikasi', 'bi-bell-fill'],
                    ['superadmin.audit.index', 'Audit Trail Log', 'bi-clock-history']
                ];
            @endphp
            @foreach ($systemItems as [$route, $label, $icon])
                @if (Route::has($route))
                    @php
                        $isActive = request()->routeIs($route, $route.'.*', str_replace('.index', '', $route).'.*');
                    @endphp
                    <a href="{{ route($route) }}" 
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#fec200] {{ $isActive ? 'bg-gradient-to-r from-[#d57028] to-[#b86021] font-semibold text-white shadow-md shadow-[#d57028]/25' : 'text-[#f7eee8]/75 hover:bg-white/6 hover:text-white' }}">
                        <i class="bi {{ $icon }} text-base {{ $isActive ? 'text-white' : 'text-[#d57028]' }}"></i>
                        <span>{{ $label }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    {{-- Footer Box --}}
    <div class="border-t border-white/10 p-4">
        <div class="rounded-xl border border-white/5 bg-[#31140d]/80 p-3.5 backdrop-blur-xs">
            <div class="flex items-center gap-3">
                <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-tr from-[#d57028] to-[#d5282d] text-xs font-bold text-white font-heading shadow-xs">
                    SA
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-white font-heading">{{ auth()->user()->name ?? 'Superadmin' }}</p>
                    <p class="mt-0.5 truncate text-[11px] text-white/70">{{ auth()->user()->email ?? 'admin@majapahit.com' }}</p>
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
