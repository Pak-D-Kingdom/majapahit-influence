<header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-md transition sm:px-6 lg:px-8 shadow-xs">
    <div class="flex items-center gap-3">
        <button id="brand-sidebar-toggle" type="button" class="rounded-xl p-2 text-slate-500 hover:bg-blue-50/60 hover:text-[#0c3685] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4] lg:hidden" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="brand-dashboard-sidebar">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[#0b64d4] font-heading">
                <span>PORTAL BRAND</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500">KERAJAAN ECOSYSTEM</span>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight text-[#0c3685] font-heading">@yield('page-title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-3 pl-2">
            <div class="hidden sm:block text-right">
                <p class="text-xs font-bold text-[#0c3685] font-heading leading-tight">{{ auth()->user()->brand->name ?? auth()->user()->name ?? 'Brand Partner' }}</p>
                <p class="text-[11px] text-slate-500 leading-tight">Brand Partner</p>
            </div>
            <div class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#0b64d4] to-[#1698f6] text-sm font-bold text-white shadow-sm shadow-blue-500/25 font-heading">
                {{ strtoupper(substr(auth()->user()->brand->name ?? auth()->user()->name ?? 'B', 0, 2)) }}
            </div>
        </div>
    </div>
</header>
