<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard KOL') | Majapahit Influence</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo/majapahit.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-[#fff9f4] font-sans text-[#421b13] antialiased selection:bg-[#d57028]/20 selection:text-[#d5282d]">
    <div class="min-h-screen lg:flex">
        {{-- Mobile Sidebar Overlay --}}
        <div id="kol-sidebar-overlay" class="fixed inset-0 z-30 hidden bg-[#421b13]/40 backdrop-blur-xs transition-opacity lg:hidden"></div>

        {{-- Sidebar --}}
        @include('kol.layouts.sidebar')

        {{-- Main Area --}}
        <div class="min-w-0 flex-1 flex flex-col min-h-screen bg-[#fff9f4]">
            @include('kol.layouts.navbar')

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="mx-auto max-w-[1400px]">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
