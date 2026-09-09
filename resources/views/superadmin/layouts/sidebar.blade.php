{{-- ============================================================
     SIDEBAR SUPERADMIN
============================================================ --}}

<aside class="sa-sidebar" id="sa-sidebar">

    {{-- ========================================================
         BRAND / LOGO
    ========================================================= --}}
    <div class="sa-brand">
        <div class="sa-brand-mark">M</div>

        <div>
            <strong>MAJAPAHIT</strong>
            <span>INFLUENCE</span>
        </div>
    </div>


    {{-- ========================================================
         NAVIGASI SUPERADMIN
    ========================================================= --}}
    <nav class="sa-nav" aria-label="Navigasi Superadmin">

        {{-- ====================================================
             DASHBOARD
        ===================================================== --}}
        <a href="{{ url('/superadmin') }}" data-nav="dashboard">
            ⌂
            <span>Dashboard</span>
        </a>


        {{-- ====================================================
             KOL
        ===================================================== --}}
        <a href="{{ url('/superadmin/kol') }}" data-nav="kol">
            ♙
            <span>KOL</span>
        </a>


        {{-- ====================================================
             PENDAFTARAN KOL
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/registrations') }}"
            data-nav="registrations"
        >
            ✎
            <span>Pendaftaran KOL</span>
            <b>8</b>
        </a>


        {{-- ====================================================
             BRAND
        ===================================================== --}}
        <a href="{{ url('/superadmin/brands') }}" data-nav="brands">
            ▣
            <span>Brand</span>
        </a>


        {{-- ====================================================
             CAMPAIGN
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/campaigns') }}"
            data-nav="campaigns"
        >
            ◈
            <span>Campaign</span>
        </a>


        {{-- ====================================================
             KOMISI
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/commissions') }}"
            data-nav="commissions"
        >
            ◉
            <span>Komisi</span>
            <b>4</b>
        </a>


        {{-- ====================================================
             NOTIFIKASI
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/notifications') }}"
            data-nav="notifications"
        >
            ♧
            <span>Notifikasi</span>
            <b>5</b>
        </a>


        {{-- ====================================================
             AUDIT TRAIL
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/audit-trail') }}"
            data-nav="audit-trail"
        >
            ⌁
            <span>Audit Trail</span>
        </a>


        {{-- ====================================================
             PEMBATAS NAVIGASI
        ===================================================== --}}
        <div class="sa-nav-divider"></div>


        {{-- ====================================================
             KELOLA USER
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/users') }}"
            data-nav="users"
        >
            ♙
            <span>Kelola User</span>
        </a>


        {{-- ====================================================
             PENGATURAN
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/settings') }}"
            data-nav="settings"
        >
            ⚙
            <span>Pengaturan</span>
        </a>


        {{-- ====================================================
             LAPORAN
        ===================================================== --}}
        <a
            href="{{ url('/superadmin/reports') }}"
            data-nav="reports"
        >
            ▤
            <span>Laporan</span>
        </a>

            <a
        href="{{ route('superadmin.logout') }}"
        class="sa-sidebar-logout"
    >
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout</span>
    </a>

    </nav>
    
</aside>
