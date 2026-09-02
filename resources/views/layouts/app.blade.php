<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Majapahit Influence')
    </title>

    <meta
        name="description"
        content="Majapahit Influence — Connect, Create, and Grow with Brands."
    >

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

    {{-- Landing CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">

    @stack('styles')
</head>

<body>

    @yield('content')

    <script src="{{ asset('assets/js/landing.js') }}"></script>

    @stack('scripts')

</body>
</html>