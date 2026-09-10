@extends('layouts.app')

@section('content')

<div class="brand-bank-page">

    {{-- =========================================
        HERO BRAND BANK
    ========================================== --}}
    <section class="brand-bank-hero">

        <div class="container">

            <div class="brand-bank-hero-content">

                <div class="section-label">
                    <span></span>
                    BRAND BANK
                </div>

                <h1>
                    Temukan Brand
                    <span>yang tepat.</span>
                </h1>

                <p>
                    Jelajahi brand yang tergabung dalam ekosistem KERAJAAN
                    dan temukan berbagai produk serta peluang kolaborasi
                    bersama creator.
                </p>

                <div class="brand-bank-actions">

                    <a href="{{ route('brand.register') }}"
                       class="btn-primary">

                        Gabung sebagai Brand
                        <i class="bi bi-arrow-up-right"></i>

                    </a>

                    <a href="{{ url('/') }}#roles"
                       class="btn-secondary">

                        Kembali ke Ekosistem

                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================
        TOP 10 BRAND
    ========================================== --}}
    <section class="brand-bank-section">

        <div class="container">

            <div class="brand-bank-heading">

                <div>

                    <div class="section-label">
                        <span></span>
                        BRAND TERATAS
                    </div>

                    <h2>
                        Top 10
                        <span>Brand.</span>
                    </h2>

                </div>

                <p>
                    Brand yang tergabung dalam ekosistem KERAJAAN
                    dari berbagai kategori produk dan industri.
                </p>

            </div>


            {{-- =========================================
                SEARCH & FILTER
            ========================================== --}}

            <div class="brand-filter">

                <div class="brand-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        placeholder="Cari brand..."
                    >

                </div>


                <select>

                    <option value="">
                        Semua Kategori
                    </option>

                    <option value="fnb">
                        F&B
                    </option>

                    <option value="beauty">
                        Beauty
                    </option>

                    <option value="fashion">
                        Fashion
                    </option>

                    <option value="lifestyle">
                        Lifestyle
                    </option>

                    <option value="home">
                        Home & Living
                    </option>

                </select>

            </div>


            {{-- =========================================
                BRAND GRID
            ========================================== --}}

            <div class="brand-grid">


                {{-- BRAND 01 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-01.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #01
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            F&B
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Food & Beverage Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                24 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                35 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 02 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-02.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #02
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            Beauty
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Beauty Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                18 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                29 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 03 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-03.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #03
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            Fashion
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Fashion Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                15 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                24 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 04 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-04.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #04
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            Lifestyle
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Lifestyle Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                13 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                21 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 05 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-05.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #05
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            F&B
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Food Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                11 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                19 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 06 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-06.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #06
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            Beauty
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Beauty Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                10 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                17 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 07 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-07.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #07
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            Fashion
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Fashion Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                9 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                15 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 08 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-08.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #08
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            Lifestyle
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Lifestyle Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                8 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                14 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 09 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-09.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #09
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            Home & Living
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Home & Living Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                7 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                12 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>


                {{-- BRAND 10 --}}

                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ asset('assets/landing/images/brand/brand-10.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #10
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            F&B
                        </span>

                        <h3>
                            Brand Name
                        </h3>

                        <p>
                            Food & Beverage Brand
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                6 Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                10 Creator
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================
        CTA
    ========================================== --}}

    <section class="brand-bank-cta">

        <div class="container">

            <div class="brand-bank-cta-inner">

                <div>

                    <div class="section-label">
                        <span></span>
                        JADI BAGIAN DARI KERAJAAN
                    </div>

                    <h2>
                        Punya produk?
                        <span>Bawa ke KERAJAAN.</span>
                    </h2>

                    <p>
                        Daftarkan brand dan produk Anda,
                        lalu buka peluang kolaborasi bersama creator
                        dalam ekosistem KERAJAAN.
                    </p>

                </div>

                <a href="{{ route('brand.register') }}"
                   class="btn-primary">

                    Gabung sebagai Brand
                    <i class="bi bi-arrow-up-right"></i>

                </a>

            </div>

        </div>

    </section>

</div>

@endsection