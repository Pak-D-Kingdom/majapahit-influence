<header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur-md sm:px-6 lg:px-8 shadow-xs">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" type="button" class="rounded-xl p-2.5 text-[#071d49] hover:bg-slate-100 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4] lg:hidden" aria-label="Buka Menu Navigasi">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Workspace / Superadmin</p>
            <h1 class="text-lg sm:text-xl font-extrabold text-[#071d49] font-heading tracking-tight">@yield('page-title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <x-dashboard.notification-link route="superadmin.notifications.index" />

        <div class="hidden h-8 w-px bg-slate-200 sm:block"></div>

        <div class="flex items-center gap-3">
            <div class="flex size-9 items-center justify-center rounded-xl bg-gradient-to-tr from-[#0b64d4] to-[#1698f6] text-xs font-bold text-white shadow-xs font-heading">
                SA
            </div>
            <div class="hidden sm:block">
                <p class="text-sm font-bold text-[#071d49] font-heading leading-tight">{{ auth()->user()->name ?? 'Superadmin' }}</p>
                <p class="text-[11px] font-medium text-slate-500">Admin Agensi</p>
            </div>
        </div>
    </div>
</header>
