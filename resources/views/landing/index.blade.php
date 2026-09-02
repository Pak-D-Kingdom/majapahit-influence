@extends('layouts.app')

@section('title', 'Majapahit Agency — Kembangkan Pengaruhmu, Buka Peluangmu')
@section('content')

    {{-- ================================
        NAVBAR
    ================================= --}}
    <header class="site-header" id="siteHeader">

        <div class="container navbar">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="brand">
                <div class="brand-mark">
                    MA
                </div>

                <div class="brand-text">
                    <span>MAJAPAHIT</span>
                    <strong>AGENCY</strong>
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
                    Gabung sebagai KOL
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
                    Gabung sebagai KOL
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
                        Majapahit Agency adalah program KOL dari Pak De Group
                        yang membuka kesempatan bagi para kreator untuk
                        berkolaborasi dengan produk-produk kami dan berbagai
                        brand menarik yang bergabung bersama kami.
                    </p>


                    <div class="hero-actions">

                        <a href="#" class="btn-primary btn-large">

                            Gabung sebagai KOL

                            <i class="bi bi-arrow-up-right"></i>

                        </a>


                        <a href="#tentang" class="btn-secondary btn-large">

                            Kenali Kami

                            <i class="bi bi-arrow-down"></i>

                        </a>

                    </div>


                    <div class="hero-trust">

                        <div class="trust-avatars">

                            <span>MA</span>
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
                            <small>Gabung bersama kami</small>
                        </div>

                    </div>


                    {{-- Decorative Circle --}}
                    <div class="hero-circle">
                        <span>MA</span>
                    </div>

                </div>

            </div>

        </section>


        {{-- ================================
    TENTANG KAMA
================================= --}}
        <section class="about-section company-section" id="tentang">

            <div class="container">

                <div class="about-header">

                    <div class="section-label">
                        <span></span>
                        TENTANG KAMA
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
                            hadirnya Majapahit Agency sebagai wadah
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

                            Majapahit Agency hadir untuk mempertemukan
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


                {{-- MAJAPAHIT AGENCY --}}
                <div class="program-intro" id="program">

                    <div class="program-label">

                        <span>02</span>

                        <small>
                            MAJAPAHIT AGENCY
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

        {{-- ================================
    KENAPA GABUNG?
================================= --}}
        <section class="benefits-section" id="benefits">

            <div class="container">

                {{-- Section Header --}}
                <div class="benefits-header">

                    <div class="section-label">
                        <span></span>
                        KENAPA GABUNG?
                    </div>

                    <div class="benefits-heading">

                        <h2>
                            Lebih dari sekadar
                            <span>promosi produk.</span>
                        </h2>

                        <p>
                            Majapahit Agency hadir untuk memberikan ruang bagi
                            kreator untuk berkembang, membangun koneksi, dan membuka
                            lebih banyak peluang kolaborasi.
                        </p>

                    </div>

                </div>


                {{-- Benefits Grid --}}
                <div class="benefits-grid">

                    {{-- Benefit 01 --}}
                    <div class="benefit-card">

                        <div class="benefit-number">
                            01
                        </div>

                        <div class="benefit-icon">
                            <i class="bi bi-megaphone"></i>
                        </div>

                        <div class="benefit-content">

                            <h3>
                                Kesempatan Berkolaborasi
                            </h3>

                            <p>
                                Dapatkan kesempatan untuk berkolaborasi
                                dalam berbagai aktivitas bersama produk
                                dan brand yang tergabung dalam jaringan kami.
                            </p>

                        </div>

                        <div class="benefit-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>

                    </div>


                    {{-- Benefit 02 --}}
                    <div class="benefit-card">

                        <div class="benefit-number">
                            02
                        </div>

                        <div class="benefit-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div class="benefit-content">

                            <h3>
                                Kenal Produk Lebih Dekat
                            </h3>

                            <p>
                                Kenali berbagai produk dari Pak De Group
                                dan brand partner yang memiliki potensi
                                untuk kamu ceritakan kepada audiensmu.
                            </p>

                        </div>

                        <div class="benefit-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>

                    </div>


                    {{-- Benefit 03 --}}
                    <div class="benefit-card">

                        <div class="benefit-number">
                            03
                        </div>

                        <div class="benefit-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <div class="benefit-content">

                            <h3>
                                Bangun Networking
                            </h3>

                            <p>
                                Terhubung dengan ekosistem kreator dan
                                peluang kolaborasi yang terus berkembang
                                bersama Majapahit Agency.
                            </p>

                        </div>

                        <div class="benefit-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>

                    </div>


                    {{-- Benefit 04 --}}
                    <div class="benefit-card">

                        <div class="benefit-number">
                            04
                        </div>

                        <div class="benefit-icon">
                            <i class="bi bi-stars"></i>
                        </div>

                        <div class="benefit-content">

                            <h3>
                                Buka Peluang Baru
                            </h3>

                            <p>
                                Jadikan kreativitas dan pengaruhmu sebagai
                                jalan untuk menemukan berbagai peluang
                                baru bersama brand yang tepat.
                            </p>

                        </div>

                        <div class="benefit-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ================================
            PRODUK & BRAND / MATRA
        ================================= --}}
        <section class="partners-section" id="mitra">

            <div class="container">

                <div class="partners-header">

                    <div class="section-label">
                        <span></span>
                        PRODUK & BRAND
                    </div>

                    <div class="partners-heading">
                        <h2>
                            Kolaborasi dimulai dari
                            <span>produk yang tepat.</span>
                        </h2>

                        <p>
                            Majapahit Agency membuka ruang kolaborasi bersama
                            produk-produk Pak De Group dan brand yang bergabung
                            dalam jaringan kami.
                        </p>
                    </div>

                </div>

                <div class="partners-intro">

                    <div class="partners-main-card">
                        <div class="partners-card-number">01</div>

                        <div class="partners-card-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div>
                            <small>PRODUK UTAMA</small>
                            <h3>Produk Pak De Group</h3>
                            <p>
                                Kenali berbagai produk yang hadir dari Pak De Group
                                dan temukan peluang untuk menceritakannya kepada
                                audiensmu.
                            </p>
                        </div>
                    </div>

                    <div class="partners-network-card">
                        <div class="partners-card-number">02</div>

                        <div class="partners-card-icon">
                            <i class="bi bi-buildings"></i>
                        </div>

                        <div>
                            <small>JARINGAN KOLABORASI</small>
                            <h3>Brand Partner</h3>
                            <p>
                                Seiring berkembangnya jaringan, berbagai brand
                                partner dapat membuka peluang campaign dan
                                kolaborasi baru bagi para kreator.
                            </p>
                        </div>
                    </div>

                </div>

                <div class="partners-note">
                    <div class="partners-note-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div>
                        <strong>Terus berkembang bersama kami.</strong>
                        <span>
                            Daftar brand dan produk akan terus bertambah seiring
                            berkembangnya ekosistem Majapahit Agency.
                        </span>
                    </div>
                </div>

            </div>

        </section>

        {{-- ================================
            CARA BERGABUNG
        ================================= --}}
        <section class="join-section" id="cara-bergabung">

            <div class="container">

                {{-- Section Header --}}
                <div class="join-header">

                    <div class="section-label">
                        <span></span>
                        CARA BERGABUNG
                    </div>

                    <div class="join-heading">

                        <h2>
                            Mulai perjalananmu
                            <span>bersama kami.</span>
                        </h2>

                        <p>
                            Bergabung dengan Majapahit Agency cukup dengan
                            beberapa langkah sederhana. Daftarkan dirimu,
                            tunggu proses review, dan buka peluang kolaborasi
                            bersama berbagai brand.
                        </p>

                    </div>

                </div>


                {{-- Steps --}}
                <div class="join-steps">

                    {{-- Step 01 --}}
                    <div class="join-step">

                        <div class="join-step-top">
                            <span class="join-step-number">01</span>

                            <div class="join-step-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                        </div>

                        <div class="join-step-content">

                            <h3>Daftar sebagai KOL</h3>

                            <p>
                                Lengkapi data diri, platform sosial media,
                                niche, followers, rate card, dan informasi
                                lainnya melalui formulir pendaftaran.
                            </p>

                        </div>

                    </div>


                    {{-- Step 02 --}}
                    <div class="join-step">

                        <div class="join-step-top">
                            <span class="join-step-number">02</span>

                            <div class="join-step-icon">
                                <i class="bi bi-search"></i>
                            </div>
                        </div>

                        <div class="join-step-content">

                            <h3>Proses Review</h3>

                            <p>
                                Tim Majapahit Agency akan meninjau profil
                                dan informasi yang kamu kirimkan sebelum
                                menentukan status pendaftaranmu.
                            </p>

                        </div>

                    </div>


                    {{-- Step 03 --}}
                    <div class="join-step">

                        <div class="join-step-top">
                            <span class="join-step-number">03</span>

                            <div class="join-step-icon">
                                <i class="bi bi-patch-check"></i>
                            </div>
                        </div>

                        <div class="join-step-content">

                            <h3>Diterima sebagai KOL</h3>

                            <p>
                                Setelah pendaftaran disetujui, kamu akan
                                mendapatkan akses sebagai KOL dan dapat
                                mulai mengikuti ekosistem Majapahit Agency.
                            </p>

                        </div>

                    </div>


                    {{-- Step 04 --}}
                    <div class="join-step">

                        <div class="join-step-top">
                            <span class="join-step-number">04</span>

                            <div class="join-step-icon">
                                <i class="bi bi-stars"></i>
                            </div>
                        </div>

                        <div class="join-step-content">

                            <h3>Mulai Berkolaborasi</h3>

                            <p>
                                Dapatkan kesempatan mengikuti campaign,
                                endorsement, dan berbagai peluang kolaborasi
                                yang sesuai dengan profilmu.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Bottom Note --}}
                <div class="join-note">

                    <div class="join-note-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <strong>
                            Pendaftaran bukan berarti langsung mendapatkan campaign.
                        </strong>

                        <span>
                            Setiap peluang kolaborasi akan disesuaikan dengan
                            kebutuhan campaign dan profil KOL.
                        </span>
                    </div>

                </div>

            </div>

        </section>

        {{-- ================================
            CTA GABUNG SEBAGAI KOL
        ================================= --}}
        <section class="cta-section" id="gabung">

            <div class="cta-decoration cta-decoration-one"></div>
            <div class="cta-decoration cta-decoration-two"></div>

            <div class="container">

                <div class="cta-content">

                    <div class="section-label cta-label">
                        <span></span>
                        SIAP BERGABUNG?
                    </div>

                    <h2>
                        Siap membuka
                        <span>peluangmu?</span>
                    </h2>

                    <p>
                        Jadilah bagian dari Majapahit Agency dan buka kesempatan
                        untuk berkembang bersama berbagai peluang kolaborasi.
                    </p>

                    <div class="cta-actions">

                        <a href="#cara-bergabung" class="btn-primary btn-large">
                            Gabung sebagai KOL
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a href="#home" class="btn-secondary btn-large">
                            Kembali ke Atas
                            <i class="bi bi-arrow-up"></i>
                        </a>

                    </div>

                    <small class="cta-note">
                        Pendaftaran terbuka untuk kreator dan influencer yang
                        ingin berkembang bersama ekosistem kami.
                    </small>

                </div>

            </div>

        </section>

    </main>

@endsection
