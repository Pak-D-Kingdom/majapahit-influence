@extends('layouts.app')

@section('content')

<div class="creator-bank-page">

    {{-- =========================================
        HERO CREATOR BANK
    ========================================== --}}
    <section class="creator-bank-hero">

        <div class="container">

            <div class="creator-bank-hero-content">

                <div class="section-label">
                    <span></span>
                    CREATOR BANK
                </div>

                <h1>
                    Temukan Creator
                    <span>yang tepat.</span>
                </h1>

                <p>
                    Jelajahi creator yang tergabung dalam ekosistem KERAJAAN
                    dan temukan partner yang sesuai dengan kebutuhan brand
                    dan produk Anda.
                </p>

                <div class="creator-bank-actions">

                    <a href="{{ url('/') }}#cara-bergabung"
                       class="btn-primary">
                        Gabung sebagai Creator
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
        TOP 10 CREATOR
    ========================================== --}}
    <section class="creator-bank-section">

        <div class="container">

            <div class="creator-bank-heading">

                <div>
                    <div class="section-label">
                        <span></span>
                        CREATOR TERATAS
                    </div>

                    <h2>
                        Top 10
                        <span>Creator.</span>
                    </h2>
                </div>

                <p>
                    Creator pilihan dalam ekosistem KERAJAAN
                    dengan berbagai niche dan karakter audience.
                </p>

            </div>


            {{-- SEARCH & FILTER --}}

            <div class="creator-filter">

                <div class="creator-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        placeholder="Cari creator..."
                    >

                </div>


                <select>
                    <option value="">Semua Kategori</option>
                    <option value="fnb">F&B</option>
                    <option value="beauty">Beauty</option>
                    <option value="fashion">Fashion</option>
                    <option value="lifestyle">Lifestyle</option>
                    <option value="home">Home & Living</option>
                </select>

            </div>


            {{-- =========================================
                CREATOR GRID
            ========================================== --}}

            <div class="creator-grid">


                {{-- CREATOR 01 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-01.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #01
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            F&B
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Food & Lifestyle Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                125K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                4.8% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 02 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-02.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #02
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            Beauty
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Beauty Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                98K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                5.2% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 03 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-03.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #03
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            Fashion
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Fashion Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                87K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                4.6% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 04 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-04.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #04
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            Lifestyle
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Lifestyle Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                76K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                4.4% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 05 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-05.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #05
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            F&B
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Food Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                69K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                4.2% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 06 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-06.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #06
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            Beauty
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Beauty Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                61K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                4.1% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 07 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-07.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #07
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            Fashion
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Fashion Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                57K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                3.9% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 08 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-08.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #08
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            Lifestyle
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Lifestyle Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                52K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                3.8% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 09 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-09.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #09
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            Home & Living
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Home & Living Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                48K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                3.7% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>


                {{-- CREATOR 10 --}}

                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ asset('assets/landing/images/creator/creator-10.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #10
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            F&B
                        </span>

                        <h3>
                            Creator Name
                        </h3>

                        <p>
                            Food Creator
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                43K Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                3.5% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
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

    <section class="creator-bank-cta">

        <div class="container">

            <div class="creator-bank-cta-inner">

                <div>

                    <div class="section-label">
                        <span></span>
                        JADI BAGIAN DARI KERAJAAN
                    </div>

                    <h2>
                        Kreativitasmu punya
                        <span>tempat di sini.</span>
                    </h2>

                    <p>
                        Bergabung bersama creator lainnya
                        dan buka lebih banyak peluang kolaborasi.
                    </p>

                </div>

                <a href="{{ url('/') }}#cara-bergabung"
                   class="btn-primary">

                    Gabung sebagai Creator
                    <i class="bi bi-arrow-up-right"></i>

                </a>

            </div>

        </div>

    </section>

</div>

@endsection