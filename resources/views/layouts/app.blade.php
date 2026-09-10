<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'kerajaan Influence')
    </title>

    <meta
        name="description"
        content="kerajaan Influence: Connect, Create, and Grow with Brands."
    >

    <link rel="icon" type="image/png" href="{{ asset('assets/landing/images/logo/kerajaanlogov1.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- Tailwind CSS CDN & Custom Tokens --}}
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
                        },
                    },
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">

    @stack('styles')
</head>

<body>

    @yield('content')

    <script src="{{ asset('assets/js/landing.js') }}"></script>

    @stack('scripts')

</body>
</html>