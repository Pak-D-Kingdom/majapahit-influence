<header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-[#421b13]/10 bg-white/90 px-4 backdrop-blur-md sm:px-6 lg:px-8 shadow-xs">
    <div class="flex items-center gap-3">
        <button id="sidebar-toggle" type="button" class="rounded-xl p-2.5 text-[#421b13] hover:bg-[#f7eee8] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] lg:hidden" aria-label="Buka Menu Navigasi">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Workspace / Superadmin</p>
            <h1 class="text-lg sm:text-xl font-extrabold text-[#421b13] font-heading tracking-tight">@yield('page-title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <x-dashboard.notification-link route="superadmin.notifications.index" :count="$unreadNotificationCount ?? 0"/>

        <div class="hidden h-8 w-px bg-[#421b13]/10 sm:block"></div>

        <div class="flex items-center gap-3">
            <div class="flex size-9 items-center justify-center rounded-xl bg-gradient-to-tr from-[#d57028] to-[#d5282d] text-xs font-bold text-white shadow-xs font-heading">
                SA
            </div>
            <div class="hidden sm:block">
                <p class="text-sm font-bold text-[#421b13] font-heading leading-tight">{{ auth()->user()->name ?? 'Superadmin' }}</p>
                <p class="text-[11px] font-medium text-[#765f58]">Admin Agensi</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200/80 bg-white px-3 py-2 text-xs font-bold text-rose-600 shadow-xs hover:bg-rose-50 hover:border-rose-300 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500">
                <i class="bi bi-box-arrow-right"></i>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </form>
    </div>
</header>
