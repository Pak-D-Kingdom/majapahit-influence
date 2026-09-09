{{-- ============================================================
     TOPBAR / HEADER SUPERADMIN
============================================================ --}}

<header class="sa-topbar">

    {{-- ========================================================
         TOMBOL MENU SIDEBAR
    ========================================================= --}}
    <button
        class="sa-icon-btn sa-menu-btn"
        data-sidebar-toggle
        aria-label="Buka menu"
    >
        ☰
    </button>


    {{-- ========================================================
         BREADCRUMB
    ========================================================= --}}
    <div class="sa-breadcrumb">
        @yield('eyebrow', 'Superadmin')
        <span>/</span>
        <strong>@yield('title', 'Dashboard')</strong>
    </div>


    {{-- ========================================================
         AKSI TOPBAR
    ========================================================= --}}
    <div class="sa-top-actions">

        {{-- ====================================================
             NOTIFIKASI
        ===================================================== --}}
        <button
            class="sa-icon-btn"
            title="Notifikasi"
            onclick="location.href='{{ url('/superadmin/notifications') }}'"
        >
            ♧
            <i>5</i>
        </button>


        {{-- ====================================================
             PROFIL USER
        ===================================================== --}}
        <div class="sa-user">

            <div class="sa-avatar">
                R
            </div>

            <div>
                <strong>Rina</strong>
                <small>Superadmin</small>
            </div>

        </div>

    </div>

</header>
