<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Superadmin') | KERAJAAN</title>
    
    <link rel="icon" type="image/png" href="{{ asset('assets/landing/images/logo/kerajaanlogov1.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&family=Plus+Jakarta+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kerajaan: {
                            blue: '#0b64d4',
                            sky: '#1698f6',
                            soft: '#78a5d6',
                            navy: '#0c3685',
                            dark: '#071d49',
                        },
                        kerajaan: {
                            orange: '#0b64d4',
                            red: '#1698f6',
                            yellow: '#78a5d6',
                            dark: '#0c3685',
                            brown: '#0b64d4',
                            cream: '#f8fafc',
                            sand: '#f1f5f9',
                            muted: '#64748b',
                        }
                    },
                    fontFamily: {
                        sans: ['"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        heading: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-sans, .font-body { font-family: 'DM Sans', sans-serif; }
        .btn-kerajaan-primary, .btn-kerajaan-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #0b64d4, #1698f6);
            box-shadow: 0 4px 14px rgba(11, 100, 212, 0.25);
            transition: all 0.2s ease;
        }
        .btn-kerajaan-primary:hover, .btn-kerajaan-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(11, 100, 212, 0.35);
            filter: brightness(1.05);
        }
        .btn-kerajaan-secondary, .btn-kerajaan-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0c3685;
            background-color: #ffffff;
            border: 1px solid rgba(12, 54, 133, 0.18);
            transition: all 0.2s ease;
        }
        .btn-kerajaan-secondary:hover, .btn-kerajaan-secondary:hover {
            background-color: #f8fafc;
            border-color: rgba(11, 100, 212, 0.4);
            color: #0b64d4;
        }
        .kerajaan-card, .kerajaan-card {
            background-color: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 1rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('styles')
</head>
<body class="min-h-screen bg-[#f8fafc] font-sans text-slate-800 antialiased selection:bg-[#0b64d4]/20 selection:text-[#0c3685]">
    <div class="min-h-screen lg:flex">
        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-[#071d49]/50 backdrop-blur-xs transition-opacity lg:hidden" aria-hidden="true"></div>
        @include('superadmin.layouts.sidebar')
        <div class="min-w-0 flex-1 flex flex-col min-h-screen bg-[#f8fafc]">
            @include('superadmin.layouts.navbar')
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="mx-auto max-w-[1600px]">
                    @if (session('success'))
                        <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-xs" role="alert">
                            <i class="bi bi-check-circle-fill text-base text-emerald-600"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-xs" role="alert">
                            <i class="bi bi-exclamation-octagon-fill text-base text-rose-600"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('dashboard-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (toggle && sidebar && overlay) {
                const openSidebar = () => {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
                };
                const closeSidebar = () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
                };
                toggle.addEventListener('click', openSidebar);
                overlay.addEventListener('click', closeSidebar);
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                        closeSidebar();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
