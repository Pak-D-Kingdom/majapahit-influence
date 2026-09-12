{{-- =========================================
    INTERACTIVE PRODUCT DEMO SECTION
    "HOW IT WORKS" — MAJAPAHIT INFLUENCE
========================================== --}}
<section class="demo-section" id="how-it-works">
    <div class="demo-ambient-glow demo-glow-1" aria-hidden="true"></div>
    <div class="demo-ambient-glow demo-glow-2" aria-hidden="true"></div>

    <div class="container">
        {{-- Section Header --}}
        <div class="demo-header text-center">
            <div class="section-label demo-label justify-center">
                <span></span>
                HOW IT WORKS
            </div>

            <h2 class="demo-heading">
                See How Majapahit Influence
                <span class="demo-heading-highlight">Works.</span>
            </h2>

            <p class="demo-subheading">
                Explore the platform and discover how brands and creators connect, collaborate, and manage campaigns.
            </p>

            <div class="demo-actions-bar">
                <button type="button" class="btn-watch-demo" id="btnOpenDemoModal" aria-haspopup="dialog" aria-controls="demoVideoModal">
                    <span class="play-icon-glow">
                        <i class="bi bi-play-fill"></i>
                    </span>
                    <span class="btn-watch-text">Watch Demo</span>
                    <span class="btn-watch-badge">30s Video</span>
                </button>
            </div>
        </div>

        {{-- Step Navigation Indicator Tabs --}}
        <div class="demo-nav-wrapper">
            <div class="demo-steps-nav" id="demoStepsNav" role="tablist" aria-label="Interactive Demo Steps">
                <button type="button" class="demo-step-btn active" data-step="0" role="tab" aria-selected="true" aria-controls="demoSlide0" id="stepTab0">
                    <div class="step-badge-wrap">
                        <span class="step-num">01</span>
                    </div>
                    <div class="step-text">
                        <strong class="step-title">Discover</strong>
                        <span class="step-desc">Temukan KOL & Creator</span>
                    </div>
                    <div class="step-progress-track">
                        <div class="step-progress-fill"></div>
                    </div>
                </button>

                <button type="button" class="demo-step-btn" data-step="1" role="tab" aria-selected="false" aria-controls="demoSlide1" id="stepTab1">
                    <div class="step-badge-wrap">
                        <span class="step-num">02</span>
                    </div>
                    <div class="step-text">
                        <strong class="step-title">Explore</strong>
                        <span class="step-desc">Analisis Profil & Metrik</span>
                    </div>
                    <div class="step-progress-track">
                        <div class="step-progress-fill"></div>
                    </div>
                </button>

                <button type="button" class="demo-step-btn" data-step="2" role="tab" aria-selected="false" aria-controls="demoSlide2" id="stepTab2">
                    <div class="step-badge-wrap">
                        <span class="step-num">03</span>
                    </div>
                    <div class="step-text">
                        <strong class="step-title">Campaign</strong>
                        <span class="step-desc">Buat Brief & Deliverables</span>
                    </div>
                    <div class="step-progress-track">
                        <div class="step-progress-fill"></div>
                    </div>
                </button>

                <button type="button" class="demo-step-btn" data-step="3" role="tab" aria-selected="false" aria-controls="demoSlide3" id="stepTab3">
                    <div class="step-badge-wrap">
                        <span class="step-num">04</span>
                    </div>
                    <div class="step-text">
                        <strong class="step-title">Collaborate</strong>
                        <span class="step-desc">Eksekusi & Review Konten</span>
                    </div>
                    <div class="step-progress-track">
                        <div class="step-progress-fill"></div>
                    </div>
                </button>

                <button type="button" class="demo-step-btn" data-step="4" role="tab" aria-selected="false" aria-controls="demoSlide4" id="stepTab4">
                    <div class="step-badge-wrap">
                        <span class="step-num">05</span>
                    </div>
                    <div class="step-text">
                        <strong class="step-title">Track</strong>
                        <span class="step-desc">Pantau Metrik & ROI</span>
                    </div>
                    <div class="step-progress-track">
                        <div class="step-progress-fill"></div>
                    </div>
                </button>
            </div>
        </div>

        {{-- Browser & Dashboard Mockup Showcase --}}
        <div class="demo-showcase-wrap" id="demoShowcaseWrap">
            <div class="demo-browser-frame" id="demoBrowserFrame">
                {{-- Browser Window Topbar / Chrome --}}
                <div class="browser-chrome">
                    <div class="browser-dots">
                        <span class="b-dot b-dot-red"></span>
                        <span class="b-dot b-dot-yellow"></span>
                        <span class="b-dot b-dot-green"></span>
                    </div>

                    <div class="browser-url-bar">
                        <i class="bi bi-shield-lock-fill text-[#1698f6]"></i>
                        <span class="browser-url-text" id="browserUrlText">https://app.majapahit.id/explore/creators</span>
                    </div>

                    <div class="browser-meta-status">
                        <span class="meta-dot"></span>
                        <span class="meta-text">INTERACTIVE DEMO</span>
                    </div>
                </div>

                {{-- Browser Viewport Containing 5 Live Slides --}}
                <div class="browser-viewport" id="browserViewport">

                    {{-- SLIDE 01: DISCOVER --}}
                    <div class="mockup-slide active" id="demoSlide0" role="tabpanel" aria-labelledby="stepTab0">
                        <div class="slide-inner">
                            {{-- Slide Internal Header / Search Bar --}}
                            <div class="slide-top-filter">
                                <div class="mock-search-box" id="step1SearchTarget">
                                    <i class="bi bi-search"></i>
                                    <span class="mock-search-placeholder">Cari creator kuliner, fashion, beauty, lifestyle...</span>
                                    <span class="mock-search-tag">Filter Aktif</span>
                                </div>
                                <div class="mock-category-pills">
                                    <span class="mock-pill active">Semua (1.2K)</span>
                                    <span class="mock-pill">F&B Kuliner</span>
                                    <span class="mock-pill">Beauty & Care</span>
                                    <span class="mock-pill">Fashion</span>
                                    <span class="mock-pill">Tech</span>
                                </div>
                            </div>

                            {{-- Creator Cards Grid --}}
                            <div class="slide-creators-grid">
                                {{-- Card 1: Primary Target --}}
                                <div class="mock-creator-card card-featured" id="step1CreatorTarget">
                                    <div class="mock-card-media">
                                        <img src="{{ asset('assets/landing/images/creator/creator-01.jpg') }}" alt="Sarah Amelia" class="mock-card-img" loading="lazy">
                                        <span class="mock-rank-badge">#01 Top Rated</span>
                                        <span class="mock-match-badge">98% Match</span>
                                    </div>
                                    <div class="mock-card-content">
                                        <div class="mock-creator-title">
                                            <h4>Sarah Amelia</h4>
                                            <i class="bi bi-patch-check-fill text-[#0b64d4]"></i>
                                        </div>
                                        <span class="mock-handle">@sarah.amelia • F&B Foodies</span>
                                        <div class="mock-stats-row">
                                            <div>
                                                <strong>142.5K</strong>
                                                <small>Followers</small>
                                            </div>
                                            <div>
                                                <strong>4.9%</strong>
                                                <small>Engagement</small>
                                            </div>
                                            <div>
                                                <strong>Surabaya</strong>
                                                <small>Lokasi</small>
                                            </div>
                                        </div>
                                        <button type="button" class="mock-btn-action mock-btn-primary" id="step1ViewProfileBtn">
                                            <span>Lihat Profil</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Card 2 --}}
                                <div class="mock-creator-card">
                                    <div class="mock-card-media">
                                        <img src="{{ asset('assets/landing/images/creator/creator-02.jpg') }}" alt="Dimas Pratama" class="mock-card-img" loading="lazy">
                                        <span class="mock-rank-badge">#02 Trending</span>
                                    </div>
                                    <div class="mock-card-content">
                                        <div class="mock-creator-title">
                                            <h4>Dimas Pratama</h4>
                                            <i class="bi bi-patch-check-fill text-[#0b64d4]"></i>
                                        </div>
                                        <span class="mock-handle">@dimas_tech • Tech & Gadget</span>
                                        <div class="mock-stats-row">
                                            <div>
                                                <strong>280K</strong>
                                                <small>Followers</small>
                                            </div>
                                            <div>
                                                <strong>5.4%</strong>
                                                <small>Engagement</small>
                                            </div>
                                            <div>
                                                <strong>Jakarta</strong>
                                                <small>Lokasi</small>
                                            </div>
                                        </div>
                                        <button type="button" class="mock-btn-action mock-btn-secondary">
                                            <span>Lihat Profil</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Card 3 --}}
                                <div class="mock-creator-card hide-mobile">
                                    <div class="mock-card-media">
                                        <img src="{{ asset('assets/landing/images/creator/creator-03.jpg') }}" alt="Clara Sinta" class="mock-card-img" loading="lazy">
                                        <span class="mock-rank-badge">#03 Rising</span>
                                    </div>
                                    <div class="mock-card-content">
                                        <div class="mock-creator-title">
                                            <h4>Clara Sinta</h4>
                                            <i class="bi bi-patch-check-fill text-[#0b64d4]"></i>
                                        </div>
                                        <span class="mock-handle">@clarasinta • Beauty & Care</span>
                                        <div class="mock-stats-row">
                                            <div>
                                                <strong>95K</strong>
                                                <small>Followers</small>
                                            </div>
                                            <div>
                                                <strong>6.2%</strong>
                                                <small>Engagement</small>
                                            </div>
                                            <div>
                                                <strong>Bandung</strong>
                                                <small>Lokasi</small>
                                            </div>
                                        </div>
                                        <button type="button" class="mock-btn-action mock-btn-secondary">
                                            <span>Lihat Profil</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SLIDE 02: EXPLORE --}}
                    <div class="mockup-slide" id="demoSlide1" role="tabpanel" aria-labelledby="stepTab1">
                        <div class="slide-inner">
                            <div class="mock-profile-header">
                                <div class="mock-profile-top">
                                    <div class="mock-profile-avatar-wrap">
                                        <img src="{{ asset('assets/landing/images/creator/creator-01.jpg') }}" alt="Sarah Amelia" class="mock-profile-avatar" loading="lazy">
                                        <span class="mock-verified-icon">
                                            <i class="bi bi-patch-check-fill"></i>
                                        </span>
                                    </div>
                                    <div class="mock-profile-info">
                                        <div class="mock-profile-name-row">
                                            <h3>Sarah Amelia</h3>
                                            <span class="mock-cat-badge">F&B & Lifestyle Creator</span>
                                            <span class="mock-rate-badge"><i class="bi bi-star-fill"></i> 4.9 (38 Reviews)</span>
                                        </div>
                                        <p class="mock-profile-bio">
                                            Food reviewer & visual storyteller berbasis di Surabaya. Menghubungkan cita rasa autentik produk UMKM & Brand ternama kepada audiens loyal.
                                        </p>
                                        <div class="mock-social-badges">
                                            <span class="mock-social-chip"><i class="bi bi-instagram"></i> 84K IG</span>
                                            <span class="mock-social-chip"><i class="bi bi-tiktok"></i> 58.5K TikTok</span>
                                            <span class="mock-social-chip"><i class="bi bi-youtube"></i> 12K YT</span>
                                        </div>
                                    </div>
                                    <div class="mock-profile-cta-wrap">
                                        <button type="button" class="mock-btn-action mock-btn-primary mock-btn-lg" id="step2InviteBtn">
                                            <i class="bi bi-send-fill"></i>
                                            <span>Ajak Kolaborasi</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Metrics Overview Bar --}}
                                <div class="mock-metrics-row">
                                    <div class="metric-card">
                                        <span class="metric-val">142.5K</span>
                                        <span class="metric-label">Total Followers</span>
                                        <small class="metric-trend text-emerald-600"><i class="bi bi-arrow-up"></i> +12% bln ini</small>
                                    </div>
                                    <div class="metric-card">
                                        <span class="metric-val">4.92%</span>
                                        <span class="metric-label">Avg Engagement</span>
                                        <small class="metric-trend text-[#0b64d4]">2.4x rata-rata industri</small>
                                    </div>
                                    <div class="metric-card">
                                        <span class="metric-val">38</span>
                                        <span class="metric-label">Kampanye Selesai</span>
                                        <small class="metric-trend text-emerald-600">100% on-time</small>
                                    </div>
                                    <div class="metric-card hide-mobile">
                                        <span class="metric-val">65.8K</span>
                                        <span class="metric-label">Rata-rata Views</span>
                                        <small class="metric-trend text-[#1698f6]">Reels & TikTok</small>
                                    </div>
                                </div>

                                {{-- Recent Portfolio Grid Preview --}}
                                <div class="mock-portfolio-preview">
                                    <div class="portfolio-section-title">
                                        <span>Portofolio Konten Terbaru</span>
                                        <small>3 Video Teratas</small>
                                    </div>
                                    <div class="portfolio-cards-row">
                                        <div class="portfolio-mini-card">
                                            <img src="{{ asset('assets/landing/images/brand/brand-01.jpg') }}" alt="Portfolio 1" class="portfolio-thumb" loading="lazy">
                                            <span class="play-overlay"><i class="bi bi-play-fill"></i></span>
                                            <div class="portfolio-info">
                                                <strong>Pak De Coffee Launch</strong>
                                                <small><i class="bi bi-eye"></i> 89.2K Views • 8.4K Likes</small>
                                            </div>
                                        </div>
                                        <div class="portfolio-mini-card">
                                            <img src="{{ asset('assets/landing/images/brand/brand-02.jpg') }}" alt="Portfolio 2" class="portfolio-thumb" loading="lazy">
                                            <span class="play-overlay"><i class="bi bi-play-fill"></i></span>
                                            <div class="portfolio-info">
                                                <strong>Snack Lokal Review</strong>
                                                <small><i class="bi bi-eye"></i> 54.1K Views • 5.1K Likes</small>
                                            </div>
                                        </div>
                                        <div class="portfolio-mini-card hide-mobile">
                                            <img src="{{ asset('assets/landing/images/brand/brand-03.jpg') }}" alt="Portfolio 3" class="portfolio-thumb" loading="lazy">
                                            <span class="play-overlay"><i class="bi bi-play-fill"></i></span>
                                            <div class="portfolio-info">
                                                <strong>Kuliner Pedas Challenge</strong>
                                                <small><i class="bi bi-eye"></i> 68.3K Views • 6.7K Likes</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SLIDE 03: CAMPAIGN --}}
                    <div class="mockup-slide" id="demoSlide2" role="tabpanel" aria-labelledby="stepTab2">
                        <div class="slide-inner">
                            <div class="mock-campaign-builder">
                                <div class="campaign-builder-header">
                                    <div>
                                        <span class="builder-eyebrow">CAMPAIGN SETUP</span>
                                        <h3>Buat Campaign & Brief Kolaborasi</h3>
                                    </div>
                                    <span class="campaign-status-pill">
                                        <i class="bi bi-pen-fill"></i> Mode Pembuatan
                                    </span>
                                </div>

                                <div class="campaign-form-grid">
                                    {{-- Left: Brief Details --}}
                                    <div class="campaign-form-card">
                                        <div class="form-row">
                                            <label class="form-label">Judul Campaign</label>
                                            <div class="mock-input-field">
                                                <strong>Peluncuran Varian Baru Pak De Coffee</strong>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="form-label">Tujuan Campaign & Key Message</label>
                                            <div class="mock-input-textarea">
                                                <span>Fokus pada rasa gula aren autentik dan promo grand launching Buy 1 Get 1 untuk audiens Surabaya & sekitarnya.</span>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label class="form-label">Deliverables yang Diminta</label>
                                            <div class="deliverable-tags">
                                                <span class="deliverable-tag checked"><i class="bi bi-check2"></i> 1x Dedicated Instagram Reel</span>
                                                <span class="deliverable-tag checked"><i class="bi bi-check2"></i> 2x Instagram Story (Link)</span>
                                                <span class="deliverable-tag checked"><i class="bi bi-check2"></i> 1x TikTok Video</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right: Budget & Timeline --}}
                                    <div class="campaign-summary-card">
                                        <div class="summary-header">
                                            <span>Target & Alokasi Anggaran</span>
                                            <strong class="text-[#0b64d4]">Rp 15.000.000</strong>
                                        </div>

                                        <div class="summary-stat-list">
                                            <div class="summary-stat-item">
                                                <span>Kebutuhan Kreator</span>
                                                <strong>5 KOL (F&B Niche)</strong>
                                            </div>
                                            <div class="summary-stat-item">
                                                <span>Target Jangkauan Est.</span>
                                                <strong>500.000+ Impresi</strong>
                                            </div>
                                            <div class="summary-stat-item">
                                                <span>Periode Kampanye</span>
                                                <strong>15 Sep – 30 Sep 2026</strong>
                                            </div>
                                        </div>

                                        <div class="brand-owner-chip">
                                            <img src="{{ asset('assets/landing/images/brand/brand-01.jpg') }}" alt="Brand" class="brand-chip-img" loading="lazy">
                                            <div>
                                                <strong>Pak De Group Official</strong>
                                                <small>Brand Terverifikasi</small>
                                            </div>
                                        </div>

                                        <button type="button" class="mock-btn-action mock-btn-primary w-full" id="step3PublishBtn">
                                            <i class="bi bi-rocket-takeoff-fill"></i>
                                            <span>Publish & Undang Creator</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SLIDE 04: COLLABORATE --}}
                    <div class="mockup-slide" id="demoSlide3" role="tabpanel" aria-labelledby="stepTab3">
                        <div class="slide-inner">
                            <div class="mock-collab-workspace">
                                <div class="collab-topbar">
                                    <div>
                                        <span class="collab-id">ID: #COL-2026-089</span>
                                        <h4>Pak De Coffee × Sarah Amelia</h4>
                                    </div>
                                    <div class="collab-stage-badge">
                                        <span class="stage-pulse"></span>
                                        <span>Tahap 3: Review Draft Konten</span>
                                    </div>
                                </div>

                                {{-- Workflow Step Progress Tracker --}}
                                <div class="collab-timeline-bar">
                                    <div class="timeline-step completed">
                                        <span class="tl-icon"><i class="bi bi-check-lg"></i></span>
                                        <span class="tl-text">1. Brief Disepakati</span>
                                    </div>
                                    <div class="timeline-step completed">
                                        <span class="tl-icon"><i class="bi bi-check-lg"></i></span>
                                        <span class="tl-text">2. Produk Terkirim</span>
                                    </div>
                                    <div class="timeline-step active">
                                        <span class="tl-icon"><i class="bi bi-camera-reels-fill"></i></span>
                                        <span class="tl-text">3. Review Draft</span>
                                    </div>
                                    <div class="timeline-step">
                                        <span class="tl-icon">4</span>
                                        <span class="tl-text">4. Publikasi & Payout</span>
                                    </div>
                                </div>

                                {{-- Active Draft Submission Card --}}
                                <div class="collab-draft-box">
                                    <div class="draft-preview-side">
                                        <div class="video-draft-mock">
                                            <img src="{{ asset('assets/landing/images/brand/brand-01.jpg') }}" alt="Draft Content" class="draft-thumb" loading="lazy">
                                            <span class="draft-play-btn"><i class="bi bi-play-fill"></i></span>
                                            <span class="draft-duration">00:58</span>
                                        </div>
                                    </div>
                                    <div class="draft-details-side">
                                        <div class="draft-meta-head">
                                            <div class="draft-uploader">
                                                <img src="{{ asset('assets/landing/images/creator/creator-01.jpg') }}" alt="Sarah" class="uploader-avatar" loading="lazy">
                                                <div>
                                                    <strong>Draft Video Reels v1.mp4</strong>
                                                    <small>Diunggah oleh @sarah.amelia • 2 jam lalu</small>
                                                </div>
                                            </div>
                                            <span class="draft-ready-badge">Menunggu Review Brand</span>
                                        </div>

                                        <p class="draft-notes">
                                            "Halo tim Pak De, draft reels review menu baru sudah selesai! Tone visual hangat, hook 3 detik pertama sangat engaging, dan promo BOGO sudah disertakan jelas."
                                        </p>

                                        <div class="draft-actions-row">
                                            <button type="button" class="mock-btn-action mock-btn-success" id="step4ApproveBtn">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Setujui Draft (Approve)</span>
                                            </button>
                                            <button type="button" class="mock-btn-action mock-btn-secondary">
                                                <i class="bi bi-chat-dots"></i>
                                                <span>Minta Revisi Halus</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SLIDE 05: TRACK --}}
                    <div class="mockup-slide" id="demoSlide4" role="tabpanel" aria-labelledby="stepTab4">
                        <div class="slide-inner">
                            <div class="mock-track-dashboard">
                                <div class="track-header">
                                    <div>
                                        <span class="track-eyebrow">LIVE PERFORMANCE ANALYTICS</span>
                                        <h3>Kampanye Pak De Coffee — Overview</h3>
                                    </div>
                                    <div class="track-status-pill">
                                        <span class="live-dot-green"></span>
                                        <span>Aktif Berjalan (Hari ke-7)</span>
                                    </div>
                                </div>

                                {{-- Top 4 KPI Metrics --}}
                                <div class="track-kpis-grid">
                                    <div class="kpi-card">
                                        <span class="kpi-label">Total Impresi</span>
                                        <strong class="kpi-num">1.248.500</strong>
                                        <small class="kpi-badge positive"><i class="bi bi-arrow-up-right"></i> +38% vs target</small>
                                    </div>
                                    <div class="kpi-card">
                                        <span class="kpi-label">Jangkauan Unik</span>
                                        <strong class="kpi-num">892.400</strong>
                                        <small class="kpi-subtext">Akun terpapar konten</small>
                                    </div>
                                    <div class="kpi-card">
                                        <span class="kpi-label">Total Interaksi</span>
                                        <strong class="kpi-num">96.350</strong>
                                        <small class="kpi-badge neutral">Engagement 5.2%</small>
                                    </div>
                                    <div class="kpi-card highlight-kpi">
                                        <span class="kpi-label">Konversi & Nilai ROI</span>
                                        <strong class="kpi-num text-[#0b64d4]">3.8x ROI</strong>
                                        <small class="kpi-subtext">Rp 57.000.000 Est. Sales</small>
                                    </div>
                                </div>

                                {{-- Performance Growth SVG Graph & Top Creator Table --}}
                                <div class="track-analytics-split">
                                    {{-- Chart Visual --}}
                                    <div class="analytics-chart-box">
                                        <div class="chart-box-header">
                                            <span>Tren Jangkauan 7 Hari Terakhir</span>
                                            <span class="chart-legend"><i class="bi bi-circle-fill text-[#0b64d4]"></i> Impresi Harian</span>
                                        </div>
                                        <div class="svg-chart-wrap">
                                            <svg viewBox="0 0 500 160" class="performance-chart-svg" preserveAspectRatio="none">
                                                <defs>
                                                    <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                                        <stop offset="0%" stop-color="#0b64d4" stop-opacity="0.35"/>
                                                        <stop offset="100%" stop-color="#0b64d4" stop-opacity="0.0"/>
                                                    </linearGradient>
                                                </defs>
                                                <!-- Grid Lines -->
                                                <line x1="0" y1="40" x2="500" y2="40" stroke="rgba(11, 100, 212, 0.08)" stroke-dasharray="4"/>
                                                <line x1="0" y1="80" x2="500" y2="80" stroke="rgba(11, 100, 212, 0.08)" stroke-dasharray="4"/>
                                                <line x1="0" y1="120" x2="500" y2="120" stroke="rgba(11, 100, 212, 0.08)" stroke-dasharray="4"/>
                                                <!-- Gradient Area -->
                                                <path d="M 0,150 L 0,120 Q 80,95 160,85 T 320,45 T 450,20 L 500,15 L 500,150 Z" fill="url(#chartGrad)"/>
                                                <!-- Smooth Line -->
                                                <path d="M 0,120 Q 80,95 160,85 T 320,45 T 450,20 L 500,15" fill="none" stroke="#0b64d4" stroke-width="3.5" stroke-linecap="round"/>
                                                <!-- Highlight Data Points -->
                                                <circle cx="160" cy="85" r="5" fill="#1698f6" stroke="#ffffff" stroke-width="2"/>
                                                <circle cx="320" cy="45" r="5" fill="#1698f6" stroke="#ffffff" stroke-width="2"/>
                                                <circle cx="450" cy="20" r="6" fill="#0b64d4" stroke="#ffffff" stroke-width="2"/>
                                            </svg>
                                        </div>
                                        <div class="chart-x-labels">
                                            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                                        </div>
                                    </div>

                                    {{-- Creator Ranking Breakdown --}}
                                    <div class="analytics-creators-box">
                                        <div class="chart-box-header">
                                            <span>Top Kontributor</span>
                                            <small>Performa per KOL</small>
                                        </div>
                                        <div class="top-kol-list">
                                            <div class="kol-stat-row">
                                                <img src="{{ asset('assets/landing/images/creator/creator-01.jpg') }}" alt="Sarah" class="kol-row-avatar" loading="lazy">
                                                <div class="kol-row-info">
                                                    <strong>Sarah Amelia</strong>
                                                    <small>48.2K Impresi • 6.2% ER</small>
                                                </div>
                                                <span class="kol-row-status text-emerald-600"><i class="bi bi-check-circle"></i> Selesai</span>
                                            </div>
                                            <div class="kol-stat-row">
                                                <img src="{{ asset('assets/landing/images/creator/creator-02.jpg') }}" alt="Dimas" class="kol-row-avatar" loading="lazy">
                                                <div class="kol-row-info">
                                                    <strong>Dimas Pratama</strong>
                                                    <small>41.5K Impresi • 5.8% ER</small>
                                                </div>
                                                <span class="kol-row-status text-emerald-600"><i class="bi bi-check-circle"></i> Selesai</span>
                                            </div>
                                            <div class="kol-stat-row">
                                                <img src="{{ asset('assets/landing/images/creator/creator-03.jpg') }}" alt="Clara" class="kol-row-avatar" loading="lazy">
                                                <div class="kol-row-info">
                                                    <strong>Clara Sinta</strong>
                                                    <small>32.9K Impresi • 5.1% ER</small>
                                                </div>
                                                <span class="kol-row-status text-[#1698f6]"><i class="bi bi-hourglass-split"></i> Aktif</span>
                                            </div>
                                        </div>
                                        <button type="button" class="mock-btn-action mock-btn-secondary w-full" id="step5DownloadBtn">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                            <span>Unduh Laporan ROI (PDF)</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Virtual Animated Cursor (Simulated interactive SaaS guide) --}}
                    <div class="mockup-virtual-cursor" id="mockupVirtualCursor" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="cursor-svg">
                            <path d="M5.5 3.21V20.8c0 .45.54.67.85.35l4.33-4.42 3.12 6.74c.14.3.47.43.76.29l2.4-1.11c.29-.13.41-.47.28-.77l-3.1-6.7 5.86-.39c.45-.03.66-.58.33-.89L6.34 2.86c-.32-.31-.84-.09-.84.35z" fill="#0b64d4" stroke="#ffffff" stroke-width="1.5"/>
                        </svg>
                        <span class="cursor-label" id="cursorActionLabel">Memilih Creator...</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================
    VIDEO DEMO MODAL / LIGHTBOX
========================================== --}}
<div class="demo-video-modal" id="demoVideoModal" aria-hidden="true" role="dialog" aria-labelledby="demoModalTitle">
    <div class="modal-backdrop" id="demoModalBackdrop"></div>
    <div class="modal-card">
        <div class="modal-card-header">
            <div class="modal-title-wrap">
                <span class="modal-icon-badge">
                    <i class="bi bi-play-circle-fill"></i>
                </span>
                <div>
                    <h3 id="demoModalTitle" class="modal-title">Majapahit Influence — Platform Walkthrough</h3>
                    <small class="modal-subtitle">Cara Brand & Creator Terhubung dan Bertumbuh (30 Detik)</small>
                </div>
            </div>
            <button type="button" class="modal-close-btn" id="btnCloseDemoModal" aria-label="Tutup Video">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="modal-card-body">
            <div class="video-player-container">
                <div class="video-embed-wrapper">
                    {{-- Native video player with poster; if demo.mp4 exists it plays seamlessly --}}
                    <video id="demoVideoElement" class="demo-video-player" playsinline muted loop controls poster="{{ asset('assets/landing/images/brand/brand-01.jpg') }}">
                        <source src="{{ asset('assets/landing/videos/demo.mp4') }}" type="video/mp4">
                    </video>

                    {{-- High-tech cinematic interactive animated player when video is loaded or as fallback --}}
                    <div class="video-interactive-fallback" id="videoInteractiveFallback">
                        <div class="fallback-preview-overlay">
                            <div class="fallback-stage">
                                <div class="fallback-screen-display" id="fallbackScreenDisplay">
                                    <div class="fallback-step-content" id="fallbackStepContent">
                                        <div class="fallback-badge" id="fallbackStepBadge">STEP 01 OF 05</div>
                                        <h4 id="fallbackStepTitle">Discover & Filter Influencer</h4>
                                        <p id="fallbackStepDesc">Jelajahi lebih dari 500+ kreator terkurasi dengan filter kategori dan analitik keterlibatan langsung.</p>
                                    </div>
                                </div>
                                <div class="fallback-scrub-bar">
                                    <div class="fallback-scrub-fill" id="fallbackScrubFill"></div>
                                </div>
                                <div class="fallback-controls">
                                    <button type="button" class="fallback-play-btn" id="fallbackPlayToggle" aria-label="Play or Pause Walkthrough">
                                        <i class="bi bi-pause-fill" id="fallbackPlayIcon"></i>
                                    </button>
                                    <span class="fallback-time" id="fallbackTimeDisplay">00:08 / 00:30</span>
                                    <span class="fallback-hd-pill">Full HD 60fps</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
