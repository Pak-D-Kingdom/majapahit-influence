@extends('layouts.app')

@section('content')
    <section class="product-bank-page">

        {{-- HERO --}}
        <section class="product-bank-hero">
            <div class="container">

                <span class="product-bank-eyebrow">
                    PRODUCT BANK
                </span>

                <h1>
                    Temukan produk dari
                    <span>brand partner KERAJAAN.</span>
                </h1>

                <p>
                    Jelajahi berbagai produk dari brand partner
                    dan temukan peluang kolaborasi yang sesuai
                    dengan karakter audiensmu.
                </p>
                <a href="{{ url('/') }}#mitra" class="product-bank-back-link">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Produk, Brand, & Creator
                </a>

            </div>
        </section>


        {{-- FILTER --}}
        <section class="product-bank-content">

            <div class="container">

                <div class="product-bank-filter">

                    <button class="product-filter active">
                        Semua
                    </button>

                    <button class="product-filter">
                        Ayam Bakar Pak D
                    </button>

                    <button class="product-filter">
                        Seafood Jawara
                    </button>

                    <button class="product-filter">
                        Ceker Lidah Mertua
                    </button>

                    <button class="product-filter">
                        Donat Mbok De
                    </button>

                    <button class="product-filter">
                        Susu Mbok De
                    </button>

                </div>


                {{-- AYAM BAKAR PAK D --}}
                <div class="product-brand-section">

                    <div class="product-brand-heading">
                        <span>01</span>

                        <div>
                            <small>BRAND PARTNER</small>
                            <h2>Ayam Bakar Pak D</h2>
                        </div>
                    </div>

                    <div class="product-grid">

                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>AYAM BAKAR PAK D</small>
                                <h3>Ayam Bakar 1/2 Ekor</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>AYAM BAKAR PAK D</small>
                                <h3>Ayam Bakar 1/4</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>AYAM BAKAR PAK D</small>
                                <h3>Ayam Bakar 1/8</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>AYAM BAKAR PAK D</small>
                                <h3>Bebek Goreng</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>AYAM BAKAR PAK D</small>
                                <h3>Ayam Goreng</h3>
                            </div>
                        </article>

                    </div>

                </div>


                {{-- SEAFOOD JAWARA --}}
                <div class="product-brand-section">

                    <div class="product-brand-heading">
                        <span>02</span>

                        <div>
                            <small>BRAND PARTNER</small>
                            <h2>Seafood Jawara</h2>
                        </div>
                    </div>

                    <div class="product-grid">

                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>SEAFOOD JAWARA</small>
                                <h3>Udang</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>SEAFOOD JAWARA</small>
                                <h3>Kepiting</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>SEAFOOD JAWARA</small>
                                <h3>Kerang</h3>
                            </div>
                        </article>

                    </div>

                    <p class="product-note">
                        Tersedia dalam pilihan bumbu seperti asam manis,
                        lada hitam, dan pilihan lainnya.
                    </p>

                </div>


                {{-- CEKER LIDAH MERTUA --}}
                <div class="product-brand-section">

                    <div class="product-brand-heading">
                        <span>03</span>

                        <div>
                            <small>BRAND PARTNER</small>
                            <h2>Ceker Lidah Mertua</h2>
                        </div>
                    </div>

                    <div class="product-grid">

                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>CEKER LIDAH MERTUA</small>
                                <h3>Ceker Balado</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>CEKER LIDAH MERTUA</small>
                                <h3>Ceker Lunak Asam Manis</h3>
                            </div>
                        </article>


                        <article class="product-card">
                            <div class="product-card-image">
                                <span>F&B</span>
                            </div>

                            <div class="product-card-content">
                                <small>CEKER LIDAH MERTUA</small>
                                <h3>Ceker Lunak Ndower</h3>
                            </div>
                        </article>

                    </div>

                </div>


                {{-- DONAT MBOK DE --}}
                <div class="product-brand-section">

                    <div class="product-brand-heading">
                        <span>04</span>

                        <div>
                            <small>BRAND PARTNER</small>
                            <h2>Donat Mbok De</h2>
                        </div>
                    </div>


                    {{-- SWEET --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Sweet Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DONUT</span>
                                </div>

                                <div class="product-card-content">
                                    <small>BOBOLONI</small>
                                    <h3>Taro Milky Cloud</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DONUT</span>
                                </div>

                                <div class="product-card-content">
                                    <small>BOBOLONI</small>
                                    <h3>Matcha Milky</h3>
                                </div>
                            </article>

                        </div>

                    </div>


                    {{-- SAVORY --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Savory Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DONUT</span>
                                </div>

                                <div class="product-card-content">
                                    <small>DONAT MBOK DE</small>
                                    <h3>Mayo Floss Delight</h3>
                                </div>
                            </article>

                        </div>

                    </div>


                    {{-- CLASSIC --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Classic Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DONUT</span>
                                </div>

                                <div class="product-card-content">
                                    <small>DONAT MBOK DE</small>
                                    <h3>Chocolate</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DONUT</span>
                                </div>

                                <div class="product-card-content">
                                    <small>DONAT MBOK DE</small>
                                    <h3>Chocolate Peanut</h3>
                                </div>
                            </article>

                        </div>

                    </div>


                    {{-- SNOWY --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Snowy Dusting
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>POWDER</span>
                                </div>

                                <div class="product-card-content">
                                    <small>POWDERED SUGAR</small>
                                    <h3>Strawberry</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>POWDER</span>
                                </div>

                                <div class="product-card-content">
                                    <small>POWDERED SUGAR</small>
                                    <h3>Grape</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>POWDER</span>
                                </div>

                                <div class="product-card-content">
                                    <small>POWDERED SUGAR</small>
                                    <h3>Orange</h3>
                                </div>
                            </article>

                        </div>

                    </div>

                </div>


                {{-- SUSU MBOK DE --}}
                <div class="product-brand-section">

                    <div class="product-brand-heading">
                        <span>05</span>

                        <div>
                            <small>BRAND PARTNER</small>
                            <h2>Susu Mbok De</h2>
                        </div>
                    </div>


                    {{-- TEA --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Tea Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DRINK</span>
                                </div>

                                <div class="product-card-content">
                                    <small>TEA SERIES</small>
                                    <h3>Lychee Tea</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DRINK</span>
                                </div>

                                <div class="product-card-content">
                                    <small>TEA SERIES</small>
                                    <h3>Milk Tea</h3>
                                </div>
                            </article>

                        </div>

                    </div>


                    {{-- MILKY BOOSTER --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Milky Booster Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DRINK</span>
                                </div>

                                <div class="product-card-content">
                                    <small>MILKY BOOSTER</small>
                                    <h3>Original</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DRINK</span>
                                </div>

                                <div class="product-card-content">
                                    <small>MILKY BOOSTER</small>
                                    <h3>Ubee</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DRINK</span>
                                </div>

                                <div class="product-card-content">
                                    <small>MILKY BOOSTER</small>
                                    <h3>Matcha</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>DRINK</span>
                                </div>

                                <div class="product-card-content">
                                    <small>MILKY BOOSTER</small>
                                    <h3>Pistachio</h3>
                                </div>
                            </article>

                        </div>

                    </div>


                    {{-- COFFEE --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Coffee Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>COFFEE</span>
                                </div>

                                <div class="product-card-content">
                                    <small>COFFEE SERIES</small>
                                    <h3>Americano</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>COFFEE</span>
                                </div>

                                <div class="product-card-content">
                                    <small>COFFEE SERIES</small>
                                    <h3>Mocha Latte</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>COFFEE</span>
                                </div>

                                <div class="product-card-content">
                                    <small>COFFEE SERIES</small>
                                    <h3>Vanilla Latte</h3>
                                </div>
                            </article>

                        </div>

                    </div>


                    {{-- MATCHA --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Matcha Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>MATCHA</span>
                                </div>

                                <div class="product-card-content">
                                    <small>MATCHA SERIES</small>
                                    <h3>Matcha Berry</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>MATCHA</span>
                                </div>

                                <div class="product-card-content">
                                    <small>MATCHA SERIES</small>
                                    <h3>Matcha Choco</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>MATCHA</span>
                                </div>

                                <div class="product-card-content">
                                    <small>MATCHA SERIES</small>
                                    <h3>Matcha Latte</h3>
                                </div>
                            </article>

                        </div>

                    </div>


                    {{-- CHOCO --}}
                    <div class="product-series">

                        <div class="product-series-title">
                            Choco Series
                        </div>

                        <div class="product-grid">

                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>CHOCO</span>
                                </div>

                                <div class="product-card-content">
                                    <small>CHOCO SERIES</small>
                                    <h3>Choco Berry</h3>
                                </div>
                            </article>


                            <article class="product-card">
                                <div class="product-card-image">
                                    <span>CHOCO</span>
                                </div>

                                <div class="product-card-content">
                                    <small>CHOCO SERIES</small>
                                    <h3>Mocha Choco</h3>
                                </div>
                            </article>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </section>
@endsection

<script>
    // Selalu mulai halaman dari posisi paling atas
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    window.addEventListener('load', function() {
        window.scrollTo(0, 0);
    });

    window.addEventListener('pageshow', function() {
        window.scrollTo(0, 0);
    });
</script>
