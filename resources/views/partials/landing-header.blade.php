@props(['active' => null])

@php
    $isCatalog = $active === 'catalog' || request()->routeIs('catalog.*');
    $isHome = $active === 'home' || (request()->is('/') && ! $isCatalog);
@endphp

<header class="site-header" id="siteHeader">
    <div class="container navbar">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="brand">
            <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3-nobg.png') }}" alt="KERAJAAN" class="brand-logo">
        </a>

        {{-- Desktop Navigation --}}
        <nav class="desktop-nav">
            <a href="{{ url('/') }}#home" class="nav-link {{ $isHome ? 'active' : '' }}" data-nav="home">
                Beranda
            </a>
            <a href="{{ url('/') }}#tentang" class="nav-link" data-nav="tentang">
                Tentang Kami
            </a>
            <a href="{{ url('/') }}#roles" class="nav-link" data-nav="roles">
                Ekosistem
            </a>
            <a href="{{ url('/') }}#mitra" class="nav-link" data-nav="mitra">
                Produk & Brand
            </a>
            <a href="{{ route('catalog.index') }}" class="nav-link-ecommerce {{ $isCatalog ? 'active' : '' }}" data-nav="catalog">
                <i class="bi bi-bag-check-fill"></i>
                <span>E-Commerce</span>
            </a>
        </nav>

        {{-- Navbar Actions (2 Buttons: Masuk & Gabung Sekarang) --}}
        <div class="navbar-actions">
            @auth
                <a href="{{ auth()->user()->getDashboardUrl() }}" class="btn-dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn-join-primary">
                    <span>Gabung Sekarang</span>
                    <i class="bi bi-arrow-right-short" style="font-size: 1.1rem; line-height: 1;"></i>
                </a>
            @endauth
        </div>

        {{-- Mobile Menu Button --}}
        <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-label="Open navigation">
            <i class="bi bi-list"></i>
        </button>
    </div>

    {{-- Mobile Navigation --}}
    <div class="mobile-nav" id="mobileNav">
        <a href="{{ url('/') }}#home" class="mobile-nav-link {{ $isHome ? 'active' : '' }}" data-nav="home">Beranda</a>
        <a href="{{ url('/') }}#tentang" class="mobile-nav-link" data-nav="tentang">Tentang Kami</a>
        <a href="{{ url('/') }}#roles" class="mobile-nav-link" data-nav="roles">Ekosistem</a>
        <a href="{{ url('/') }}#mitra" class="mobile-nav-link" data-nav="mitra">Produk & Brand</a>
        <a href="{{ route('catalog.index') }}" class="mobile-nav-link mobile-nav-ecommerce {{ $isCatalog ? 'active' : '' }}" data-nav="catalog">
            <i class="bi bi-bag-check-fill"></i>
            <span>E-Commerce</span>
        </a>

        <div class="mobile-nav-actions">
            @auth
                <a href="{{ auth()->user()->getDashboardUrl() }}" class="btn-primary">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn-primary">
                    Gabung Sekarang
                </a>
            @endauth
        </div>
    </div>
</header>
