<header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-kerajaan-dark/10 bg-white/90 px-4 backdrop-blur-md sm:px-6 lg:px-8 shadow-xs">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" type="button" class="rounded-xl p-2.5 text-kerajaan-dark hover:bg-kerajaan-sand transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-kerajaan-orange lg:hidden" aria-label="Buka Menu Navigasi">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Workspace / Superadmin</p>
            <h1 class="text-lg sm:text-xl font-extrabold text-kerajaan-dark font-heading tracking-tight">@yield('page-title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <x-dashboard.notification-link route="superadmin.notifications.index" :count="$unreadNotificationCount ?? 0"/>

        <div class="hidden h-8 w-px bg-kerajaan-dark/10 sm:block"></div>

        <div class="flex items-center gap-3">
            <div class="flex size-9 items-center justify-center rounded-xl bg-gradient-to-tr from-kerajaan-orange to-kerajaan-red text-xs font-bold text-white shadow-xs font-heading">
                SA
            </div>
            <div class="hidden sm:block">
                <p class="text-sm font-bold text-kerajaan-dark font-heading leading-tight">{{ auth()->user()->name ?? 'Superadmin' }}</p>
                <p class="text-[11px] font-medium text-kerajaan-muted">Admin Agensi</p>
            </div>
        </div>
    </div>
</header>
