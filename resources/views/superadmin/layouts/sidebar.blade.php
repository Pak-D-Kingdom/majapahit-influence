<aside id="dashboard-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-800 bg-slate-950 text-slate-300 transition-transform lg:static lg:translate-x-0">
    <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
        <div class="flex size-10 items-center justify-center rounded-xl bg-amber-400 font-extrabold text-slate-950">MI</div>
        <div><p class="text-sm font-bold tracking-[0.16em] text-white">MAJAPAHIT</p><p class="text-[10px] font-semibold tracking-[0.25em] text-amber-300">INFLUENCE</p></div>
    </div>
    <div class="flex-1 overflow-y-auto px-4 py-6">
        <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Workspace</p>
        <nav class="space-y-1">
            @php $items = [['superadmin.dashboard','Dashboard','bi-grid-1x2-fill'], ['superadmin.kol.index','Data KOL','bi-people-fill'], ['superadmin.registrations.index','Pendaftaran','bi-person-plus-fill'], ['superadmin.brands.index','Brand & Klien','bi-building'], ['superadmin.campaigns.index','Campaign','bi-megaphone-fill'], ['superadmin.endorsements.index','Endorsement','bi-clipboard-check'], ['superadmin.commissions.index','Komisi','bi-wallet2']]; @endphp
            @foreach ($items as [$route, $label, $icon])
                <a href="{{ Route::has($route) ? route($route) : '#' }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($route) ? 'bg-amber-400 font-semibold text-slate-950' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="bi {{ $icon }} text-base"></i><span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>
        <p class="mb-3 mt-8 px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">System</p>
        <nav class="space-y-1">
            @foreach ([['superadmin.notifications.index','Notifikasi','bi-bell-fill'], ['superadmin.audit.index','Audit Trail','bi-clock-history'], ['superadmin.reports.index','Laporan','bi-file-earmark-bar-graph-fill'], ['superadmin.settings.index','Pengaturan','bi-sliders2-vertical'], ['superadmin.users.index','Kelola User','bi-person-gear']] as [$route, $label, $icon])
                <a href="{{ Route::has($route) ? route($route) : '#' }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 transition hover:bg-white/5 hover:text-white"><i class="bi {{ $icon }} text-base"></i><span>{{ $label }}</span></a>
            @endforeach
        </nav>
    </div>
    <div class="border-t border-white/10 p-4"><div class="rounded-xl bg-white/5 p-3"><p class="text-xs font-semibold text-white">Superadmin</p><p class="mt-1 truncate text-xs text-slate-500">Operasional agensi</p></div></div>
</aside>
