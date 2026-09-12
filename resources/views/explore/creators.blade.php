@extends('layouts.app')

@section('title', 'Creator Bank — KERAJAAN Influence')

@section('content')

@include('partials.landing-header')

<div class="creator-bank-page">

    {{-- =========================================
        HERO CREATOR BANK
    ========================================== --}}
    <section class="creator-bank-hero">

        <div class="container">

            <div class="creator-bank-hero-content">

                <span class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    CREATOR BANK
                </span>

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

                    <a href="{{ route('registration.create') }}" class="btn-primary btn-large">
                        Gabung sebagai Creator
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================
        TOP 10 CREATOR (2 BARIS)
    ========================================== --}}
    <section class="creator-bank-section">

        <div class="container">

            <div class="creator-bank-heading">

                <div>


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
                        id="creatorSearchInput"
                        placeholder="Cari creator..."
                        autocomplete="off"
                    >
                </div>

                <select id="creatorCategorySelect">
                    <option value="">Semua Kategori</option>
                    <option value="fnb">F&B</option>
                    <option value="beauty">Beauty</option>
                    <option value="fashion">Fashion</option>
                    <option value="lifestyle">Lifestyle</option>
                    <option value="home">Home & Living</option>
                </select>

            </div>


            {{-- =========================================
                CREATOR GRID (2 BARIS x 5 KOLOM)
            ========================================== --}}
            <div class="creator-grid" id="creatorGrid">

                @forelse($creators as $index => $creator)
                @php
                    $nicheName = $creator->niches->first()?->name ?? 'General';
                    $nicheSlug = Str::slug($nicheName);
                    $followers = $creator->socialMedia->sum('followers_count');
                    $formattedFollowers = $followers >= 1000 ? number_format($followers / 1000, 1) . 'K' : $followers;
                    $engRate = $creator->socialMedia->avg('engagement_rate') ?? 0;
                @endphp
                <div class="creator-bank-card" data-name="{{ strtolower($creator->user->name) }}" data-category="{{ strtolower($nicheName) }}" data-slug="{{ $nicheSlug }}">

                    <div class="creator-card-image">

                        <img
                            src="{{ $creator->photo_path ? Storage::url($creator->photo_path) : asset('assets/landing/images/creator/creator-0' . (($index % 10) + 1) . '.jpg') }}"
                            alt="{{ $creator->user->name }}"
                            loading="lazy"
                        >

                        <span class="creator-rank">
                            #{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            {{ $nicheName }}
                        </span>

                        <h3>
                            {{ $creator->user->name }}
                        </h3>

                        <p>
                            {{ $creator->bio ? Str::limit($creator->bio, 50) : $nicheName . ' Creator' }}
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                {{ $formattedFollowers }} Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                {{ number_format($engRate, 1) }}% Engagement
                            </span>

                        </div>

                    </div>

                </div>
                @empty
                <div class="col-12 text-center" style="grid-column: 1 / -1; padding: 3rem 0;">
                    <p style="color: #64748b;">Belum ada creator yang bergabung.</p>
                </div>
                @endforelse

            </div>

            <div id="noCreatorFound" style="display: none; grid-column: 1 / -1; text-align: center; padding: 3rem 0;">
                <p style="color: #64748b; font-size: 15px;">Tidak ditemukan creator yang sesuai dengan pencarian.</p>
            </div>

        </div>

    </section>


    {{-- =========================================
        CTA
    ========================================== --}}
    <section class="creator-bank-cta">

        <div class="container">

            <div class="creator-bank-cta-inner">


                <h2>
                    Kreativitasmu punya
                    <span>tempat di sini.</span>
                </h2>

                <p>
                    Bergabung bersama creator lainnya
                    dan buka lebih banyak peluang kolaborasi.
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
    const searchInput = document.getElementById('creatorSearchInput');
    const categorySelect = document.getElementById('creatorCategorySelect');
    const cards = document.querySelectorAll('.creator-bank-card');
    const noResults = document.getElementById('noCreatorFound');

    function filterCreators() {
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

    searchInput?.addEventListener('input', filterCreators);
    categorySelect?.addEventListener('change', filterCreators);
});
</script>
@endpush
