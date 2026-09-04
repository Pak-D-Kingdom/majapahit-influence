<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->is('superadmin/dashboard*') ? 'active' : '' }}" href="/superadmin/dashboard">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('superadmin/brands*') ? 'active' : '' }}" href="{{ route('superadmin.brands.index') }}">
            <i class="bi bi-buildings"></i> Brand / Klien
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('superadmin/campaigns*') ? 'active' : '' }}" href="{{ route('superadmin.campaigns.index') }}">
            <i class="bi bi-megaphone"></i> Campaign
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('superadmin/kol*') ? 'active' : '' }}" href="/superadmin/kol">
            <i class="bi bi-people"></i> Manajemen KOL
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('superadmin/reports*') ? 'active' : '' }}" href="/superadmin/reports">
            <i class="bi bi-wallet2"></i> Komisi & Laporan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('superadmin/settings*') ? 'active' : '' }}" href="/superadmin/settings">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
    </li>
</ul>
