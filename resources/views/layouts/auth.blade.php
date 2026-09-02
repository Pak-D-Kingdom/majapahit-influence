<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Majapahit Influence')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Tailwind & CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js for interactivity --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        
        .premium-shadow {
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.05);
        }
        
        /* Step transition animations */
        .step-enter { opacity: 0; transform: translateY(10px); }
        .step-enter-active { opacity: 1; transform: translateY(0); transition: all 0.4s ease-out; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex">
    
    {{-- Left Side: Branding / Visual --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/3 relative bg-zinc-900 overflow-hidden flex-col justify-between p-12 text-white">
        <!-- Background Pattern / Image -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-600/20 to-red-900/40 mix-blend-multiply z-10"></div>
            <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Background" class="w-full h-full object-cover opacity-40">
        </div>

        <div class="relative z-20">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center font-bold shadow-lg shadow-orange-500/30">
                    MI
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-300">Majapahit</span>
                    <span class="text-sm font-bold tracking-wider">INFLUENCE</span>
                </div>
            </a>
        </div>

        <div class="relative z-20 mt-20">
            @yield('left_content')
        </div>

        <div class="relative z-20 mt-10 text-sm text-gray-400 font-medium">
            &copy; {{ date('Y') }} Majapahit Influence. All rights reserved.
        </div>
    </div>

    {{-- Right Side: Form --}}
    <div class="w-full lg:w-7/12 xl:w-2/3 flex flex-col justify-center min-h-screen bg-[#FDFDFC]">
        <div class="w-full max-w-3xl mx-auto px-6 py-12 lg:px-12 relative">
            
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-10">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center font-bold text-white shadow-lg shadow-orange-500/30">
                    MI
                </div>
                <div class="flex flex-col text-gray-900">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-500">Majapahit</span>
                    <span class="text-sm font-bold tracking-wider">INFLUENCE</span>
                </div>
            </div>

            @yield('content')

        </div>
    </div>

</body>
</html>
