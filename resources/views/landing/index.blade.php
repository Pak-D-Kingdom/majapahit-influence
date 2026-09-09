@extends('layouts.app')

@section('title', 'Kerajaan — Connect, Create, Grow')
@section('content')

    {{-- ================================
        NAVBAR
    ================================= --}}
    <header class="site-header" id="siteHeader">

        <div class="container navbar">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="brand">
                <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3.png') }}" alt="KERAJAAN" class="brand-logo">
            </a>


            {{-- Desktop Navigation --}}
            <nav class="desktop-nav">

                <a href="#home" class="nav-link active">
                    Beranda
                </a>

                <a href="#tentang" class="nav-link">
                    Tentang Kami
                </a>

                <a href="#roles" class="nav-link">
                    Ekosistem
                </a>

                <a href="#mitra" class="nav-link">
                    Produk & Brand
                </a>
                <a href="#" class="nav-link nav-ecommerce">
                    E-Commerce
                </a>

            </nav>

            {{-- Navbar Actions --}}
            <div class="navbar-actions">

                <a href="#" class="btn-login">
                    Masuk
                </a>

                <a href="#cara-bergabung" class="nav-join-creator">
                    Gabung sebagai Creator
                    <i class="bi bi-arrow-up-right"></i>
                </a>

                <a href="#cara-bergabung" class="nav-join-brand">
                    Gabung sebagai Brand
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

            <a href="#program">Ekosistem</a>

            <a href="#mitra">Produk & Brand</a>
            <a href="#" class="nav-ecommerce">
                E-Commerce
            </a>

            <div class="mobile-nav-actions">

                <a href="#" class="btn-login">
                    Masuk
                </a>

                <a href="#cara-bergabung" class="btn-primary">
                    Gabung sebagai Creator
                </a>

                <a href="#cara-bergabung" class="mobile-brand-link">
                    Gabung sebagai Brand
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

                        CREATOR-POWERED COMMERCE

                    </div>


                    <h1>
                        Connect.
                        <span>Create.</span>
                        Grow.
                    </h1>


                    <p class="hero-description">
                        Kerajaan adalah ekosistem Creator-Powered Commerce
                        yang menghubungkan creator, brand, produk, content,
                        dan peluang kolaborasi untuk tumbuh bersama.
                    </p>

                    <div class="hero-actions">

                        <a href="#cara-bergabung" class="btn-primary btn-large">
                            Gabung sebagai Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a href="#cara-bergabung" class="btn-secondary btn-large">
                            Gabung sebagai Brand
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                    <a href="#tentang" class="hero-discover-link">
                        Kenali KERAJAAN
                        <i class="bi bi-arrow-down"></i>
                    </a>

                    <div class="hero-trust">

                        <div class="trust-avatars">

                            <span>MA</span>
                            <span>PD</span>
                            <span>+</span>

                        </div>

                        <div>

                            <strong>
                                Satu ekosistem, banyak peluang
                            </strong>

                            <small>
                                Creator, produk, content, dan commerce
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
                                Creator Commerce
                            </small>

                            <strong>
                                CREATOR × PRODUCT
                            </strong>

                            <span>
                                <i class="bi bi-stars"></i>
                                Create • Grow
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
                            <strong>Produk & Brand</strong>

                        </div>

                        <span class="campaign-arrow">
                            <i class="bi bi-arrow-up-right"></i>
                        </span>

                    </div>


                    {{-- Floating KOL Badge --}}
                    <div class="kol-badge">

                        <i class="bi bi-camera-fill"></i>

                        <div>
                            <strong>Creator</strong>
                            <small>Grow bersama kami</small>
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
    TENTANG KERAJAAN
================================= --}}
        <section class="about-section company-section" id="tentang">

            <div class="container">

                <div class="about-header">

                    <div class="section-label">
                        <span></span>
                        TENTANG KERAJAAN
                    </div>

                    <div>

                        <h2>
                            Membangun ekosistem
                            <span>untuk tumbuh bersama.</span>
                        </h2>

                    </div>

                </div>


                <div class="company-intro">

                    {{-- LEFT --}}
                    <div class="company-identity">

                        <div class="company-logo-card">

                            <div class="company-logo-mark">
                                MA
                            </div>

                            <div>
                                <small>
                                    CREATOR COMMERCE
                                </small>

                                <strong>
                                    KERAJAAN
                                </strong>
                            </div>

                        </div>


                        <div class="company-number">
                            <span>01</span>
                            <p>
                                Connect, create,
                                grow together.
                            </p>
                        </div>

                    </div>


                    {{-- RIGHT --}}
                    <div class="company-content">

                        <p class="company-lead">
                            KERAJAAN adalah ekosistem
                            Creator-Powered Commerce yang mempertemukan
                            creator, produk, brand, dan audiens dalam
                            satu ruang kolaborasi.
                        </p>



                        <p>
                            Kami mempertemukan creator dengan berbagai
                            produk dan brand yang memiliki potensi untuk
                            tumbuh bersama. Di sisi lain, kami membantu
                            brand menemukan ruang kolaborasi yang tepat
                            untuk memperkenalkan produknya melalui creator.
                        </p>



                        <p>
                            Berawal dari produk-produk Pak De Group, KERAJAAN
                            terus membuka ruang bagi berbagai brand dan mitra
                            produk untuk memperkenalkan produknya kepada
                            audience melalui kolaborasi yang lebih relevan,
                            kreatif, dan bernilai.
                        </p>


                        <div class="company-highlight">

                            <div class="highlight-icon">
                                <i class="bi bi-stars"></i>
                            </div>

                            <div>
                                <strong>
                                    Connect. Create. Grow.
                                </strong>

                                <span>
                                    Creator membawa kreativitas. Brand membawa produk.
                                    KERAJAAN mempertemukan keduanya.
                                </span>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- DIVIDER --}}
                <div class="company-divider"></div>

            </div>

        </section>

        {{-- ================================
    CREATOR × BRAND
================================= --}}
        <section class="benefits-section ecosystem-roles-section" id="roles">

            <div class="container">

                {{-- Section Header --}}
                <div class="benefits-header">

                    <div class="section-label">
                        <span></span>
                        CREATOR × BRAND
                    </div>

                    <div class="benefits-heading">

                        <h2>
                            Satu ekosistem,
                            <span>dua sisi kolaborasi.</span>
                        </h2>

                        <p>
                            Creator membawa kreativitas dan pengaruh.
                            Brand membawa produk dan peluang.
                            KERAJAAN mempertemukan keduanya dalam satu
                            ruang kolaborasi untuk tumbuh bersama.
                        </p>

                    </div>

                </div>


                {{-- Creator & Brand Cards --}}
                <div class="role-cards-grid">

                    {{-- ================================
                CREATOR
            ================================= --}}
                    <div class="role-card role-card-creator">

                        <div class="role-card-top">

                            <div class="role-number">
                                01
                            </div>

                            <div class="role-icon">
                                <i class="bi bi-person-video3"></i>
                            </div>

                        </div>


                        <div class="role-card-content">

                            <small>
                                UNTUK CREATOR
                            </small>

                            <h3>
                                Temukan produk.
                                <span>Ciptakan peluang.</span>
                            </h3>

                            <p>
                                Temukan berbagai produk dan brand yang relevan
                                dengan karakter serta audiensmu. Pilih peluang
                                yang sesuai, ciptakan content dengan caramu,
                                dan berkembang bersama ekosistem KERAJAAN.
                            </p>

                        </div>


                        <div class="role-benefits">

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Temukan produk & brand</span>
                            </div>

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Pilih peluang yang relevan</span>
                            </div>

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Ciptakan content dengan caramu</span>
                            </div>

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Buka peluang penghasilan</span>
                            </div>

                        </div>


                        <a href="#" class="role-card-link">
                            Gabung sebagai Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>


                    {{-- ================================
                BRAND
            ================================= --}}
                    <div class="role-card role-card-brand">

                        <div class="role-card-top">

                            <div class="role-number">
                                02
                            </div>

                            <div class="role-icon">
                                <i class="bi bi-building"></i>
                            </div>

                        </div>


                        <div class="role-card-content">

                            <small>
                                UNTUK BRAND
                            </small>

                            <h3>
                                Punya produk.
                                <span>Temukan creator.</span>
                            </h3>

                            <p>
                                Bawa produkmu lebih dekat kepada audience
                                melalui creator yang tepat. Bangun kolaborasi,
                                ciptakan content yang relevan, dan buka peluang
                                pertumbuhan bersama KERAJAAN.
                            </p>

                        </div>


                        <div class="role-benefits">

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Daftarkan brand & produk</span>
                            </div>

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Temukan creator yang relevan</span>
                            </div>

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Bangun content & campaign</span>
                            </div>

                            <div>
                                <i class="bi bi-check2"></i>
                                <span>Perluas jangkauan produk</span>
                            </div>

                        </div>


                        <a href="#" class="role-card-link">
                            Gabung sebagai Brand
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- Bottom Statement --}}
                <div class="roles-statement">

                    <div class="roles-statement-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div>
                        <strong>
                            Satu produk. Satu creator. Satu peluang baru.
                        </strong>

                        <span>
                            Ketika produk yang tepat bertemu creator yang tepat,
                            kolaborasi dapat menjadi sesuatu yang lebih berarti.
                        </span>
                    </div>

                </div>

            </div>

        </section>


        {{-- ================================
    PRODUK & BRAND
================================= --}}
        <section class="partners-section" id="mitra">

            <div class="container">

                {{-- Section Header --}}
                <div class="partners-header">

                    <div class="section-label">
                        <span></span>
                        PRODUK, BRAND, & INFLUENCER
                    </div>

                    <div class="partners-heading">

                        <h2>
                            Temukan produk yang
                            <span>siap bertumbuh.</span>
                        </h2>

                        <p>
                            Dari produk Pak De Group hingga berbagai brand
                            yang bergabung bersama KERAJAAN.
                            Setiap produk membuka ruang untuk cerita,
                            content, dan peluang kolaborasi baru.
                        </p>

                    </div>

                </div>


                {{-- Category Preview --}}
                <div class="product-category-preview">

                    <span class="category-label">
                        EXPLORE BY CATEGORY
                    </span>

                    <div class="category-list">

                        <span><a href="{{ url('/explore/food-beverage') }}">
                                F&B
                            </a></span>
                        <span> <a href="{{ url('/explore/beauty') }}">
                                Beauty
                            </a></span>
                        <span> <a href="{{ url('/explore/fashion') }}">
                                Fashion
                            </a></span>
                        <span><a href="{{ url('/explore/lifestyle') }}">
                                Lifestyle
                            </a></span>
                        <span> <a href="{{ url('/explore/home-living') }}">
                                Home & Living
                            </a></span>
                        <span><a href="{{ url('/explore') }}">
                                More
                            </a></span>

                    </div>

                </div>


                {{-- Product / Brand Cards --}}
                <div class="partners-intro">

                    {{-- Produk Pak De Group --}}
                    <div class="partners-main-card">

                        <div class="partners-card-number">
                            01
                        </div>

                        <div class="partners-card-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div class="partners-card-content">

                            <small>
                                PRODUK DALAM EKOSISTEM
                            </small>

                            <h3>
                                Produk Pak De Group
                            </h3>

                            <p>
                                Kenali berbagai produk dari Pak De Group
                                yang dapat menjadi bagian dari perjalanan
                                content dan kolaborasi para creator.
                            </p>

                            <a href="{{ url('/explore/products') }}" class="partners-card-link">
                                Lihat Produk Kami
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </div>


                    {{-- Brand Partner --}}
                    <div class="partners-network-card">

                        <div class="partners-card-number">
                            02
                        </div>

                        <div class="partners-card-icon">
                            <i class="bi bi-buildings"></i>
                        </div>

                        <div class="partners-card-content">

                            <small>
                                OPEN FOR BRAND
                            </small>

                            <h3>
                                Brand Partner
                            </h3>

                            <p>
                                Punya produk yang ingin dikenal lebih luas?
                                Bergabung bersama KERAJAAN dan buka peluang
                                kolaborasi dengan creator yang relevan.
                            </p>

                            <a href="{{ url('/explore/brands') }}" class="partners-card-link">
                                Lihat Brand Partner Kami
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </div>

                    {{-- Influencer Partner --}}
                    <div class="partners-network-card">

                        <div class="partners-card-number">
                            03
                        </div>

                        <div class="partners-card-icon">
                            <i class="bi bi-buildings"></i>
                        </div>

                        <div class="partners-card-content">

                            <small>
                                OPEN FOR INFLUENCER
                            </small>

                            <h3>
                                Influencer Partner
                            </h3>

                            <p>
                                Punya bakat dan keinginan untuk bisa dikenal lebih luas?
                                Bergabung bersama KERAJAAN dan buka peluang
                                kolaborasi dengan brand dan produk yang relevan.
                            </p>

                            <a href="{{ url('/explore/creators') }}" class="partners-card-link">
                                Lihat Creator Kami
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Bottom Statement --}}
                <div class="partners-note">

                    <div class="partners-note-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div>

                        <strong>
                            Produk yang tepat bertemu creator yang tepat.
                        </strong>

                        <span>
                            Kami terus membuka ruang bagi produk dan brand
                            baru untuk menjadi bagian dari ekosistem KERAJAAN.
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

                {{-- Header --}}
                <div class="join-header">

                    <div class="section-label">
                        <span></span>
                        CARA BERGABUNG
                    </div>

                    <div class="join-heading">

                        <h2>
                            Pilih peranmu.
                            <span>Mulai dari sini.</span>
                        </h2>

                        <p>
                            Setiap bagian dari ekosistem KERAJAAN memiliki
                            perannya sendiri. Pilih bagaimana kamu ingin
                            tumbuh bersama kami.
                        </p>

                    </div>

                </div>


                {{-- Role Selection --}}
                <div class="join-role-grid">

                    {{-- ================================
                CREATOR
            ================================= --}}
                    <div class="join-role-card join-role-creator">

                        <div class="join-role-top">

                            <div class="join-role-number">
                                01
                            </div>

                            <div class="join-role-icon">
                                <i class="bi bi-person-video3"></i>
                            </div>

                        </div>


                        <div class="join-role-content">

                            <small>
                                SAYA SEORANG CREATOR
                            </small>

                            <h3>
                                Jadikan kreativitasmu
                                <span>sebuah peluang.</span>
                            </h3>

                            <p>
                                Bergabung dengan ekosistem KERAJAAN,
                                temukan produk dan brand yang relevan,
                                lalu ciptakan content dengan caramu sendiri.
                            </p>

                        </div>


                        <div class="join-role-steps">

                            <div class="join-role-step">
                                <span>01</span>
                                <p>
                                    Daftarkan profil creator
                                </p>
                            </div>

                            <div class="join-role-step">
                                <span>02</span>
                                <p>
                                    Temukan peluang yang sesuai
                                </p>
                            </div>

                            <div class="join-role-step">
                                <span>03</span>
                                <p>
                                    Mulai berkolaborasi
                                </p>
                            </div>

                        </div>


                        <a href="#" class="join-role-link">
                            Gabung sebagai Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>


                    {{-- ================================
                BRAND
            ================================= --}}
                    <div class="join-role-card join-role-brand">

                        <div class="join-role-top">

                            <div class="join-role-number">
                                02
                            </div>

                            <div class="join-role-icon">
                                <i class="bi bi-building"></i>
                            </div>

                        </div>


                        <div class="join-role-content">

                            <small>
                                SAYA SEORANG BRAND
                            </small>

                            <h3>
                                Bawa produkmu.
                                <span>Temukan creator.</span>
                            </h3>

                            <p>
                                Daftarkan brand dan produkmu ke dalam
                                ekosistem KERAJAAN. Buka peluang untuk
                                terhubung dengan creator yang relevan
                                dengan target audience-mu.
                            </p>

                        </div>


                        <div class="join-role-steps">

                            <div class="join-role-step">
                                <span>01</span>
                                <p>
                                    Daftarkan brand & produk
                                </p>
                            </div>

                            <div class="join-role-step">
                                <span>02</span>
                                <p>
                                    Tentukan peluang kolaborasi
                                </p>
                            </div>

                            <div class="join-role-step">
                                <span>03</span>
                                <p>
                                    Terhubung dengan creator
                                </p>
                            </div>

                        </div>


                        <a href="#" class="join-role-link">
                            Gabung sebagai Brand
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- Bottom Note --}}
                <div class="join-note">

                    <div class="join-note-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div>

                        <strong>
                            Tidak perlu memilih antara kreativitas dan produk.
                        </strong>

                        <span>
                            Di KERAJAAN, keduanya bertemu untuk menciptakan
                            kolaborasi yang memiliki nilai.
                        </span>

                    </div>

                </div>

            </div>

        </section>

        {{-- ================================
    CTA AKHIR
================================= --}}
        <section class="cta-section" id="gabung">

            <div class="cta-decoration cta-decoration-one"></div>
            <div class="cta-decoration cta-decoration-two"></div>

            <div class="container">

                <div class="cta-content">

                    <div class="section-label cta-label">
                        <span></span>
                        JADI BAGIAN DARI EKOSISTEM
                    </div>


                    <h2>
                        Satu ekosistem.
                        <span>Banyak peluang.</span>
                    </h2>


                    <p>
                        Creator membawa kreativitas.
                        Brand membawa produk.
                        KERAJAAN mempertemukan keduanya untuk
                        menciptakan kolaborasi yang berarti.
                    </p>


                    <div class="cta-actions">

                        <a href="#cara-bergabung" class="btn-primary btn-large">
                            Gabung sebagai Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a href="#cara-bergabung" class="btn-secondary btn-large">
                            Gabung sebagai Brand
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>


                    <small class="cta-note">
                        Temukan peluang. Bangun kolaborasi.
                        Tumbuh bersama KERAJAAN.
                    </small>

                </div>

            </div>

        </section>


        {{-- ================================
    FAQ
================================ --}}
        <section class="faq-section" id="faq">

            <div class="container">

                <div class="faq-header">

                    <div class="section-label">
                        <span></span>
                        FAQ
                    </div>

                    <div class="faq-heading">

                        <h2>
                            Pertanyaan yang
                            <span>sering ditanyakan.</span>
                        </h2>

                        <p>
                            Temukan jawaban seputar KERAJAAN,
                            Creator, Brand, produk, dan peluang kolaborasi
                            dalam satu ekosistem.
                        </p>

                    </div>

                </div>


                <div class="faq-list">

                    <details class="faq-item">

                        <summary>
                            Apa itu KERAJAAN?
                            <i class="bi bi-plus"></i>
                        </summary>

                        <div class="faq-answer">
                            <p>
                                KERAJAAN adalah ekosistem Creator-Powered Commerce
                                yang mempertemukan creator, brand, produk, dan
                                peluang kolaborasi dalam satu ekosistem.
                            </p>
                        </div>

                    </details>


                    <details class="faq-item">

                        <summary>
                            Siapa saja yang bisa bergabung?
                            <i class="bi bi-plus"></i>
                        </summary>

                        <div class="faq-answer">
                            <p>
                                KERAJAAN terbuka bagi creator yang ingin menemukan
                                peluang kolaborasi serta brand yang ingin
                                memperkenalkan produk dan terhubung dengan creator
                                yang relevan.
                            </p>
                        </div>

                    </details>


                    <details class="faq-item">

                        <summary>
                            Apa keuntungan Brand bergabung dengan KERAJAAN?
                            <i class="bi bi-plus"></i>
                        </summary>

                        <div class="faq-answer">
                            <p>
                                Brand dapat memperkenalkan produk kepada audience
                                melalui creator, menemukan creator yang relevan,
                                serta membuka peluang kolaborasi dan campaign.
                            </p>
                        </div>

                    </details>


                    <details class="faq-item">

                        <summary>
                            Bagaimana Creator menemukan produk untuk dipromosikan?
                            <i class="bi bi-plus"></i>
                        </summary>

                        <div class="faq-answer">
                            <p>
                                Creator dapat menemukan berbagai produk dan brand
                                yang tersedia dalam ekosistem KERAJAAN sesuai
                                kategori dan peluang yang relevan dengan audience
                                mereka.
                            </p>
                        </div>

                    </details>


                    <details class="faq-item">

                        <summary>
                            Apakah Brand bisa mendaftarkan produknya?
                            <i class="bi bi-plus"></i>
                        </summary>

                        <div class="faq-answer">
                            <p>
                                Ya. Brand dapat mendaftarkan brand dan produknya
                                untuk menjadi bagian dari ekosistem KERAJAAN
                                dan membuka peluang kolaborasi dengan creator.
                            </p>
                        </div>

                    </details>


                    <details class="faq-item">

                        <summary>
                            Apakah KERAJAAN memiliki E-Commerce?
                            <i class="bi bi-plus"></i>
                        </summary>

                        <div class="faq-answer">
                            <p>
                                Ya. KERAJAAN menyediakan akses menuju platform
                                E-Commerce yang dikelola melalui website
                                E-Commerce terpisah.
                            </p>
                        </div>

                    </details>

                </div>

            </div>

        </section>

        {{-- ================================
    FOOTER
================================= --}}
        <footer class="site-footer">

            <div class="container">

                {{-- Footer Main --}}
                <div class="footer-main">

                    {{-- Brand --}}
                    <div class="footer-brand">

                        <a href="{{ url('/') }}" class="footer-logo">
                            <img src="{{ asset('assets/landing/images/logo/logokerajaannew.png') }}" alt="KERAJAAN">
                        </a>

                        <p>
                            Creator-powered commerce ecosystem yang
                            mempertemukan creator, brand, produk,
                            dan peluang kolaborasi.
                        </p>

                        <div class="footer-tagline">
                            Connect. Create. Grow.
                        </div>

                    </div>


                    {{-- Navigation --}}
                    <div class="footer-column">

                        <h4>
                            NAVIGASI
                        </h4>

                        <a href="#home">
                            Beranda
                        </a>

                        <a href="#tentang">
                            Tentang Kami
                        </a>

                        <a href="#roles">
                            Ekosistem
                        </a>

                        <a href="#mitra">
                            Produk & Brand
                        </a>

                        <a href="#cara-bergabung">
                            Cara Bergabung
                        </a>
                        <a href="#" class="footer-ecommerce">
                            E-Commerce
                        </a>

                    </div>


                    {{-- Join --}}
                    <div class="footer-column">

                        <h4>
                            BERGABUNG
                        </h4>

                        <a href="#cara-bergabung">
                            Gabung sebagai Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                        <a href="#cara-bergabung">
                            Gabung sebagai Brand
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>


                    {{-- Contact / Social --}}
                    <div class="footer-column">

                        <h4>
                            TERHUBUNG
                        </h4>

                        <a href="#" class="footer-social">
                            <i class="bi bi-instagram"></i>
                            Instagram
                        </a>

                        <a href="#" class="footer-social">
                            <i class="bi bi-tiktok"></i>
                            TikTok
                        </a>

                        <a href="#" class="footer-social">
                            <i class="bi bi-envelope"></i>
                            Email
                        </a>

                    </div>

                </div>


                {{-- Footer Bottom --}}
                <div class="footer-bottom">

                    <span>
                        © {{ date('Y') }} KERAJAAN.
                        All rights reserved.
                    </span>

                    <div class="footer-bottom-links">

                        <a href="#">
                            Privacy Policy
                        </a>

                        <a href="#">
                            Terms & Conditions
                        </a>

                    </div>

                </div>

            </div>

        </footer>

    </main>

@endsection
