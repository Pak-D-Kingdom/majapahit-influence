@extends('layouts.app')

@section('content')

@include('partials.landing-header')

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

                @forelse($brands as $index => $brand)
                <div class="brand-card">

                    <div class="brand-card-image">

                        <img
                            src="{{ $brand->logo_path ? Storage::url($brand->logo_path) : asset('assets/landing/images/brand/brand-0' . (($index % 10) + 1) . '.jpg') }}"
                            alt="Brand">

                        <span class="brand-rank">
                            #{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            {{ $brand->industry ?? 'Brand' }}
                        </span>

                        <h3>
                            {{ $brand->name }}
                        </h3>

                        <p>
                            {{ $brand->notes ? Str::limit($brand->notes, 50) : ($brand->industry ?? 'Brand') . ' Brand' }}
                        </p>

                        <div class="brand-meta">

                            <span>
                                <i class="bi bi-box-seam"></i>
                                {{ $brand->products_count ?? 0 }} Produk
                            </span>

                            <span>
                                <i class="bi bi-people"></i>
                                {{ $brand->campaigns_count ?? 0 }} Campaign
                            </span>

                        </div>

                        <a href="{{ route('catalog.index') }}"
                           class="brand-card-link">

                            Lihat Brand

                            <i class="bi bi-arrow-up-right"></i>

                        </a>

                    </div>

                </div>
                @empty
                <div class="col-12 text-center" style="grid-column: 1 / -1; padding: 3rem 0;">
                    <p style="color: #64748b;">Belum ada brand yang bergabung.</p>
                </div>
                @endforelse

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

@include('partials.landing-footer')

@endsection