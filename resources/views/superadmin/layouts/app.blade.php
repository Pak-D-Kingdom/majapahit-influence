<!doctype html>
<html lang="id">

<head>

    {{-- ============================================================
         META & INFORMASI HALAMAN
    ============================================================ --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Superadmin') — Majapahit Influence
    </title>


    {{-- ============================================================
         GOOGLE FONTS
    ============================================================ --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet"
    >


    {{-- ============================================================
         CSS SUPERADMIN
    ============================================================ --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/superadmin/css/superadmin.css') }}"
    >

</head>


<body>

    {{-- ============================================================
         SUPERADMIN SHELL
    ============================================================ --}}
    <div class="sa-shell">

        {{-- ========================================================
             SIDEBAR
        ========================================================= --}}
        @include('superadmin.layouts.sidebar')


        {{-- ========================================================
             MAIN CONTENT AREA
        ========================================================= --}}
        <div class="sa-main">

            {{-- ====================================================
                 NAVBAR / TOPBAR
            ===================================================== --}}
            @include('superadmin.layouts.navbar')


            {{-- ====================================================
                 KONTEN HALAMAN
            ===================================================== --}}
            <main class="sa-content">
                @yield('content')
            </main>

        </div>

    </div>


    {{-- ============================================================
         TOAST NOTIFICATION
    ============================================================ --}}
    <div
        id="sa-toast"
        class="sa-toast"
        aria-live="polite"
    ></div>


    {{-- ============================================================
         JAVASCRIPT SUPERADMIN
    ============================================================ --}}
    <script
        src="{{ asset('assets/superadmin/js/superadmin.js') }}"
    ></script>


    {{-- ============================================================
         STACK SCRIPTS TAMBAHAN
    ============================================================ --}}
    @stack('scripts')

</body>

</html>
