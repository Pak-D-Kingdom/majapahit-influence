@extends('layouts.app')

@section('title', 'Majapahit Influence — Kembangkan Pengaruhmu, Buka Peluangmu')
@section('content')

    {{-- ================================
        NAVBAR
    ================================= --}}
    <header class="site-header" id="siteHeader">

        <div class="container navbar">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="brand">
                <div class="brand-mark">
                    MI
                </div>

                <div class="brand-text">
                    <span>MAJAPAHIT</span>
                    <strong>INFLUENCE</strong>
                </div>
            </a>


            {{-- Desktop Navigation --}}
            <nav class="desktop-nav">

                <a href="#home" class="nav-link active">
                    Beranda
                </a>

                <a href="#tentang" class="nav-link">
                    Tentang Kami
                </a>

                <a href="#program" class="nav-link">
                    Program
                </a>

                <a href="#mitra" class="nav-link">
                    Mitra
                </a>

            </nav>

            {{-- Navbar Actions --}}
            <div class="navbar-actions">

                <a href="#" class="btn-login">
                    Masuk
                </a>

                <a href="#" class="btn-primary btn-small">
                    Join as KOL
                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>


            {{-- Mobile Menu Button --}}
            <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-label="Open navigation">
                <i class="bi bi-list"></i>
            </button>

        </div>


        {{-- Mobile Navigation --}}
        <div class="mobile-nav" id="mobileNav">

            <a href="#home">Beranda</a>

            <a href="#tentang">Tentang Kami</a>

            <a href="#program">Program</a>

            <a href="#mitra">Mitra</a>

            <div class="mobile-nav-actions">

                <a href="#" class="btn-login">
                    Masuk
                </a>

                <a href="#" class="btn-primary">
                    Join as KOL
                </a>

            </div>

        </div>

    </header>


    {{-- ================================
        HERO
    ================================= --}}
    <main>

        <section class="hero" id="home">

            <div class="hero-decoration hero-decoration-one"></div>
            <div class="hero-decoration hero-decoration-two"></div>

            <div class="container hero-container">

                {{-- Hero Content --}}
                <div class="hero-content">

                    <div class="eyebrow">

                        <span class="eyebrow-dot"></span>

                        PROGRAM KOL PAK DE GROUP

                    </div>


                    <h1>
                        Kembangkan
                        <span>Pengaruhmu.</span>
                        Buka Peluangmu.
                    </h1>


                    <p class="hero-description">
                        Majapahit Influence adalah program KOL dari Pak De Group
                        yang membuka kesempatan bagi para kreator untuk
                        berkolaborasi dengan produk-produk kami dan berbagai
                        brand menarik yang bergabung bersama kami.
                    </p>


                    <div class="hero-actions">

                        <a href="#" class="btn-primary btn-large">

                            Join as KOL

                            <i class="bi bi-arrow-up-right"></i>

                        </a>


                        <a href="#tentang" class="btn-secondary btn-large">

                            Kenali Kami

                            <i class="bi bi-arrow-down"></i>

                        </a>

                    </div>


                    <div class="hero-trust">

                        <div class="trust-avatars">

                            <span>MI</span>
                            <span>PD</span>
                            <span>+</span>

                        </div>

                        <div>

                            <strong>
                                Tumbuh bersama Pak De Group
                            </strong>

                            <small>
                                dan berbagai brand partner
                            </small>

                        </div>

                    </div>

                </div>

                {{-- Hero Visual --}}
                <div class="hero-visual">

                    <div class="hero-glow"></div>


                    {{-- Main Card --}}
                    <div class="creator-card">

                        <div class="creator-card-top">

                            <div class="creator-profile">

                                <div class="creator-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>

                                <div>
                                    <strong>Kreator</strong>
                                    <span>@namakamu</span>
                                </div>

                            </div>

                            <span class="verified">
                                <i class="bi bi-patch-check-fill"></i>
                            </span>

                        </div>


                        <div class="creator-stat">

                            <small>
                                Kolaborasi Kreator
                            </small>

                            <strong>
                                KOL × BRAND
                            </strong>

                            <span>
                                <i class="bi bi-stars"></i>
                                Peluang Baru
                            </span>

                        </div>


                        <div class="mini-chart collaboration-visual">

                            <div class="collab-item">
                                <i class="bi bi-person-video3"></i>
                                <span>Kreator</span>
                            </div>

                            <div class="collab-line">
                                <span></span>
                            </div>

                            <div class="collab-item">
                                <i class="bi bi-building"></i>
                                <span>Brand</span>
                            </div>

                        </div>

                    </div>


                    {{-- Floating Campaign Card --}}
                    <div class="campaign-card">

                        <div class="campaign-icon">
                            <i class="bi bi-stars"></i>
                        </div>

                        <div>

                            <small>Peluang Kolaborasi</small>
                            <strong>Produk Pak De Group</strong>

                        </div>

                        <span class="campaign-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </div>


                    {{-- Floating KOL Badge --}}
                    <div class="kol-badge">

                        <i class="bi bi-camera-fill"></i>

                        <div>
                            <strong>KOL</strong>
                            <small>Join our network</small>
                        </div>

                    </div>


                    {{-- Decorative Circle --}}
                    <div class="hero-circle">
                        <span>MI</span>
                    </div>

                </div>

            </div>

        </section>


        {{-- ================================
    TENTANG KAMI
================================= --}}
        <section class="about-section company-section" id="tentang">

            <div class="container">

                <div class="about-header">

                    <div class="section-label">
                        <span></span>
                        TENTANG KAMI
                    </div>

                    <div>

                        <h2>
                            Di balik setiap
                            <span>peluang kolaborasi.</span>
                        </h2>

                    </div>

                </div>


                <div class="company-intro">

                    {{-- LEFT --}}
                    <div class="company-identity">

                        <div class="company-logo-card">

                            <div class="company-logo-mark">
                                PD
                            </div>

                            <div>

                                <small>
                                    PERUSAHAAN DI BALIK
                                </small>

                                <strong>
                                    PAK DE GROUP
                                </strong>

                            </div>

                        </div>


                        <div class="company-number">
                            <span>01</span>
                            <p>
                                Membangun koneksi,
                                membuka peluang.
                            </p>
                        </div>

                    </div>


                    {{-- RIGHT --}}
                    <div class="company-content">

                        <p class="company-lead">

                            Pak De Group menjadi bagian di balik
                            hadirnya Majapahit Influence sebagai wadah
                            untuk membangun kolaborasi bersama para
                            kreator dan influencer.

                        </p>


                        <p>

                            Melalui berbagai produk yang kami kembangkan
                            dan jaringan brand yang terus bertumbuh,
                            kami membuka kesempatan bagi para kreator
                            untuk menjadi bagian dari perjalanan tersebut.

                        </p>


                        <p>

                            Majapahit Influence hadir untuk mempertemukan
                            kreativitas, audiens, produk, dan peluang
                            kolaborasi dalam satu ekosistem yang terus
                            berkembang.

                        </p>


                        <div class="company-highlight">

                            <div class="highlight-icon">
                                <i class="bi bi-stars"></i>
                            </div>

                            <div>

                                <strong>
                                    Dari Pak De Group untuk Para Kreator
                                </strong>

                                <span>
                                    Mulai bersama kami, berkembang bersama
                                    berbagai peluang yang ada.
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- DIVIDER --}}
                <div class="company-divider"></div>


                {{-- MAJAPAHIT INFLUENCE --}}
                <div class="program-intro" id="program">

                    <div class="program-label">

                        <span>02</span>

                        <small>
                            MAJAPAHIT INFLUENCE
                        </small>

                    </div>


                    <div class="program-content">

                        <h3>
                            Bukan sekadar menjadi
                            <span>influencer.</span>
                        </h3>

                        <p>
                            Jadilah bagian dari jaringan kreator yang
                            memiliki kesempatan untuk mengenal,
                            mempromosikan, dan berkolaborasi bersama
                            produk-produk Pak De Group serta brand-brand
                            menarik yang nantinya bergabung bersama kami.
                        </p>


                        <div class="program-points">

                            <div>

                                <span>01</span>

                                <strong>
                                    Kenali Produk
                                </strong>

                                <p>
                                    Berkenalan dengan berbagai produk
                                    yang hadir dari Pak De Group.
                                </p>

                            </div>


                            <div>

                                <span>02</span>

                                <strong>
                                    Bangun Kolaborasi
                                </strong>

                                <p>
                                    Dapatkan kesempatan untuk terlibat
                                    dalam berbagai aktivitas kolaborasi.
                                </p>

                            </div>


                            <div>

                                <span>03</span>

                                <strong>
                                    Buka Peluang
                                </strong>

                                <p>
                                    Berkesempatan terhubung dengan
                                    brand-brand menarik dalam jaringan.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>

@endsection
