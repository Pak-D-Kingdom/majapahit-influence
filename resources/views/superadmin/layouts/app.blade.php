<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Superadmin') — Majapahit Influence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="min-h-screen lg:flex">
        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-950/40 lg:hidden"></div>
        @include('superadmin.layouts.sidebar')
        <div class="min-w-0 flex-1">
            @include('superadmin.layouts.navbar')
            <main class="p-4 sm:p-6 lg:p-8">
                <div class="mx-auto max-w-[1600px]">@yield('content')</div>
            </main>
        </div>
    </div>
</body>
</html>
