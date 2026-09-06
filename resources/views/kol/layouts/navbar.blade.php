<header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-[#421b13]/8 bg-[#fff9f4]/90 px-4 backdrop-blur-md transition sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button id="kol-sidebar-toggle" type="button" class="rounded-xl p-2 text-[#765f58] hover:bg-[#f7eee8] hover:text-[#421b13] transition lg:hidden" aria-label="Buka menu">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[#d57028] font-heading">
                <span>PORTAL KOL</span>
                <span class="text-[#421b13]/30">•</span>
                <span class="text-[#765f58]">MAJAPAHIT INFLUENCE</span>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight text-[#421b13] font-heading">@yield('page-title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <x-dashboard.notification-link route="kol.notifications.index" :count="$unreadNotificationCount ?? 0" />

        <div class="flex items-center gap-3 pl-2 border-l border-[#421b13]/10">
            <div class="hidden sm:block text-right">
                <p class="text-xs font-bold text-[#421b13] font-heading leading-tight">{{ auth()->user()->name ?? 'KOL Creator' }}</p>
                <p class="text-[11px] text-[#765f58] leading-tight">Creator Partner</p>
            </div>
            <div class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#d57028] to-[#d5282d] text-sm font-bold text-white shadow-sm shadow-[#d57028]/25 font-heading">
                {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 2)) }}
            </div>
        </div>
    </div>
</header>
