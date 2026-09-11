@extends('layouts.app')

@section('content')

@include('partials.landing-header')

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

                    <a href="{{ route('registration.create') }}"
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

                @forelse($creators as $index => $creator)
                <div class="creator-card">

                    <div class="creator-card-image">

                        <img
                            src="{{ $creator->photo_path ? Storage::url($creator->photo_path) : asset('assets/landing/images/creator/creator-0' . (($index % 10) + 1) . '.jpg') }}"
                            alt="Creator">

                        <span class="creator-rank">
                            #{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>

                    <div class="creator-card-body">

                        <span class="creator-category">
                            {{ $creator->niches->first()?->name ?? 'General' }}
                        </span>

                        <h3>
                            {{ $creator->user->name }}
                        </h3>

                        <p>
                            {{ $creator->bio ? Str::limit($creator->bio, 50) : ($creator->niches->first()?->name ?? 'Creator') . ' Creator' }}
                        </p>

                        <div class="creator-meta">

                            <span>
                                <i class="bi bi-people"></i>
                                @php
                                    $followers = $creator->socialMedia->sum('followers_count');
                                    $formattedFollowers = $followers >= 1000 ? number_format($followers / 1000, 1) . 'K' : $followers;
                                @endphp
                                {{ $formattedFollowers }} Followers
                            </span>

                            <span>
                                <i class="bi bi-graph-up-arrow"></i>
                                @php
                                    $engRate = $creator->socialMedia->avg('engagement_rate') ?? 0;
                                @endphp
                                {{ number_format($engRate, 1) }}% Engagement
                            </span>

                        </div>

                        <a href="#"
                           class="creator-card-link">
                            Lihat Creator
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                </div>
                @empty
                <div class="col-12 text-center" style="grid-column: 1 / -1; padding: 3rem 0;">
                    <p style="color: #64748b;">Belum ada creator yang bergabung.</p>
                </div>
                @endforelse

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

                <a href="{{ route('registration.create') }}"
                   class="btn-primary">

                    Gabung sebagai Creator
                    <i class="bi bi-arrow-up-right"></i>

                </a>

            </div>

        </div>

    </section>

</div>

@include('partials.landing-footer')

@endsection