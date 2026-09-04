<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->is('kol/dashboard*') ? 'active' : '' }}" href="/kol/dashboard">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('kol/endorsements*') ? 'active' : '' }}" href="{{ route('kol.endorsements.index') }}">
            <i class="bi bi-briefcase"></i> Tugas Endorsement
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('kol/earnings*') ? 'active' : '' }}" href="/kol/earnings">
            <i class="bi bi-cash-stack"></i> Riwayat Komisi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('kol/profile*') ? 'active' : '' }}" href="/kol/profile">
            <i class="bi bi-person-badge"></i> Profil & Rate Card
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('kol/notifications*') ? 'active' : '' }}" href="/kol/notifications">
            <i class="bi bi-bell"></i> Notifikasi
        </a>
    </li>
</ul>
