@extends('layouts.app')

@section('title', 'Brand Bank — KERAJAAN Influence')

@section('content')

@include('partials.landing-header')

<div class="brand-bank-page">

    {{-- =========================================
        HERO BRAND BANK
    ========================================== --}}
    <section class="brand-bank-hero">

        <div class="container">

            <div class="brand-bank-hero-content">

                <span class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    BRAND BANK
                </span>

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

                    <a href="{{ route('brand.register') }}" class="btn-primary btn-large">
                        Gabung sebagai Brand
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================
        TOP 10 BRAND (2 BARIS)
    ========================================== --}}
    <section class="brand-bank-section">

        <div class="container">

            <div class="brand-bank-heading">

                <div>



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
                        id="brandSearchInput"
                        placeholder="Cari brand..."
                        autocomplete="off"
                    >
                </div>

                <select id="brandCategorySelect">
                    <option value="">Semua Kategori</option>
                    <option value="fnb">F&B</option>
                    <option value="beauty">Beauty</option>
                    <option value="fashion">Fashion</option>
                    <option value="lifestyle">Lifestyle</option>
                    <option value="home">Home & Living</option>
                </select>

            </div>


            {{-- =========================================
                BRAND GRID (2 BARIS x 5 KOLOM)
            ========================================== --}}
            <div class="brand-grid" id="brandGrid">

                @forelse($brands as $index => $brand)
                @php
                    $industryName = $brand->industry ?? 'Brand';
                    $industrySlug = Str::slug($industryName);
                @endphp
                <div class="brand-bank-card" data-name="{{ strtolower($brand->name) }}" data-category="{{ strtolower($industryName) }}" data-slug="{{ $industrySlug }}">

                    <div class="brand-card-image">

                        <img
                            src="{{ $brand->logo_path ? Storage::url($brand->logo_path) : asset('assets/landing/images/brand/brand-0' . (($index % 10) + 1) . '.jpg') }}"
                            alt="{{ $brand->name }}"
                            loading="lazy"
                        >

                        <span class="brand-rank">
                            #{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>

                    <div class="brand-card-body">

                        <span class="brand-category">
                            {{ $industryName }}
                        </span>

                        <h3>
                            {{ $brand->name }}
                        </h3>

                        <p>
                            {{ $brand->notes ? Str::limit($brand->notes, 50) : $industryName . ' Brand' }}
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

                    </div>

                </div>
                @empty
                <div class="col-12 text-center" style="grid-column: 1 / -1; padding: 3rem 0;">
                    <p style="color: #64748b;">Belum ada brand yang bergabung.</p>
                </div>
                @endforelse

            </div>

            <div id="noBrandFound" style="display: none; grid-column: 1 / -1; text-align: center; padding: 3rem 0;">
                <p style="color: #64748b; font-size: 15px;">Tidak ditemukan brand yang sesuai dengan pencarian.</p>
            </div>

        </div>

    </section>


    {{-- =========================================
        CTA
    ========================================== --}}
    <section class="brand-bank-cta">

        <div class="container">

            <div class="brand-bank-cta-inner">


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

        </div>

    </section>

</div>

@include('partials.landing-footer')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('brandSearchInput');
    const categorySelect = document.getElementById('brandCategorySelect');
    const cards = document.querySelectorAll('.brand-bank-card');
    const noResults = document.getElementById('noBrandFound');

    function filterBrands() {
        const query = (searchInput?.value || '').toLowerCase().trim();
        const selectedCat = (categorySelect?.value || '').toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const category = card.getAttribute('data-category') || '';
            const slug = card.getAttribute('data-slug') || '';

            const matchesQuery = !query || name.includes(query) || category.includes(query);
            const matchesCategory = !selectedCat || category.includes(selectedCat) || slug.includes(selectedCat);

            if (matchesQuery && matchesCategory) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noResults) {
            noResults.style.display = visibleCount === 0 && cards.length > 0 ? 'block' : 'none';
        }
    }

    searchInput?.addEventListener('input', filterBrands);
    categorySelect?.addEventListener('change', filterBrands);
});
</script>
@endpush
