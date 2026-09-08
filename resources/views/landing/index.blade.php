@extends('layouts.app')

@section('title', 'Majapahit Influence | Platform Kolaborasi Brand & Influencer Terdepan')

@push('styles')
    <style>
        html {
            scroll-behavior: smooth;
        }

        .landing-demo-frame {
            background: #080302;
            box-shadow: 0 32px 80px rgba(25, 9, 6, 0.34);
        }

        .landing-demo-frame::after {
            position: absolute;
            inset: 0;
            pointer-events: none;
            content: '';
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.08), transparent 15%, transparent 84%, rgba(0, 0, 0, 0.24));
        }

        .landing-feature-preview::after {
            position: absolute;
            inset: 0;
            pointer-events: none;
            content: '';
            background: linear-gradient(180deg, transparent 62%, rgba(25, 9, 6, 0.18));
        }

        .landing-feature-preview iframe {
            width: 357.15%;
            height: 357.15%;
            pointer-events: none;
            transform: scale(0.28);
            transform-origin: top left;
        }

        summary::-webkit-details-marker {
            display: none;
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ================================
        TOP NAVBAR (Dual Audience & Catalog)
    ================================= --}}
    <header class="site-header sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100" id="siteHeader">
        <div class="container mx-auto px-4 lg:px-8 py-3.5 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-xl bg-white p-0.5 flex items-center justify-center shadow-md shadow-[#d57028]/20 group-hover:scale-105 transition-transform overflow-hidden border border-[#d57028]/20">
                    <img src="{{ asset('assets/Logo/majapahit.png') }}" alt="Majapahit Influence Logo" class="size-full object-contain">
                </div>
                <div class="leading-tight">
                    <span class="block text-[11px] font-bold tracking-widest text-[#d57028] font-heading">MAJAPAHIT</span>
                    <strong class="block text-base font-extrabold text-[#421b13] tracking-tight font-heading">INFLUENCE</strong>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden 2xl:flex items-center gap-7 text-sm font-semibold text-[#765f58]">
                <a href="#demo-produk" class="hover:text-[#d57028] transition-colors focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none rounded-lg px-1">Demo Produk</a>
                <a href="#solusi-brand" class="hover:text-[#d57028] transition-colors focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none rounded-lg px-1">Solusi Brand</a>
                <a href="#maklon" class="hover:text-[#d57028] transition-colors focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none rounded-lg px-1">Layanan Maklon</a>
                <a href="#kreator" class="hover:text-[#d57028] transition-colors focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none rounded-lg px-1">Untuk Kreator</a>
                <a href="{{ route('catalog.index') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#f7eee8] text-[#b86021] font-bold border border-[#d57028]/25 hover:bg-[#d57028]/15 transition-all font-heading focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none">
                    <i class="bi bi-shop"></i> Katalog E-Commerce
                </a>
            </nav>

            {{-- Navbar Actions --}}
            <div class="hidden 2xl:flex items-center gap-3">
                @auth
                    @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                        <a href="{{ route('superadmin.dashboard') }}" class="px-4 py-2 rounded-xl bg-[#d57028] hover:bg-[#b86021] text-white font-bold text-sm shadow-xs transition-all font-heading focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none">
                            <i class="bi bi-speedometer2 mr-1"></i> Dashboard Admin
                        </a>
                    @elseif (auth()->user()->isKol())
                        <a href="{{ route('kol.dashboard') }}" class="px-4 py-2 rounded-xl bg-[#d57028] hover:bg-[#b86021] text-white font-bold text-sm shadow-xs transition-all font-heading focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none">
                            <i class="bi bi-speedometer2 mr-1"></i> Dashboard KOL
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 text-sm font-semibold text-[#d5282d] hover:bg-[#d5282d]/10 rounded-xl transition-all focus-visible:ring-2 focus-visible:ring-[#d5282d] focus-visible:outline-none">
                            <i class="bi bi-box-arrow-right mr-1"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-[#421b13] hover:text-[#d57028] transition-colors font-heading focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none rounded-lg">
                        Masuk
                    </a>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('brand.register') }}" class="px-4 py-2 rounded-xl bg-[#421b13] hover:bg-[#190906] text-white font-bold text-xs uppercase tracking-wider shadow-xs hover:shadow transition-all font-heading focus-visible:ring-2 focus-visible:ring-[#421b13] focus-visible:outline-none">
                            <i class="bi bi-building mr-1"></i> Daftar Brand
                        </a>
                        <a href="{{ route('registration.create') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] hover:from-[#b86021] hover:to-[#934510] text-white font-bold text-xs uppercase tracking-wider shadow-xs hover:shadow-[#d57028]/20 transition-all font-heading focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none">
                            <i class="bi bi-person-plus mr-1"></i> Join as KOL
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Mobile Menu Button (Accessible 44x44px target) --}}
            <button class="2xl:hidden min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl text-[#421b13] hover:text-[#d57028] hover:bg-[#f7eee8] text-2xl transition-colors focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none" id="mobileMenuToggle" type="button" aria-label="Buka Menu Navigasi" aria-expanded="false" aria-controls="mobileNav">
                <i class="bi bi-list"></i>
            </button>
        </div>

        {{-- Mobile Nav Drawer --}}
        <div class="hidden 2xl:hidden px-4 pt-2 pb-6 bg-white border-b border-gray-200" id="mobileNav">
            <div class="flex flex-col gap-3 text-sm font-semibold text-gray-700">
                <a href="#demo-produk" class="py-2 border-b border-gray-100">Demo Produk</a>
                <a href="#solusi-brand" class="py-2 border-b border-gray-100">Solusi Brand</a>
                <a href="#maklon" class="py-2 border-b border-gray-100">Layanan Maklon</a>
                <a href="#kreator" class="py-2 border-b border-gray-100">Untuk Kreator</a>
                <a href="#faq" class="py-2 border-b border-gray-100">FAQ</a>
                <a href="{{ route('catalog.index') }}" class="py-2 text-amber-700 font-bold flex items-center gap-2">
                    <i class="bi bi-shop"></i> Katalog E-Commerce
                </a>
                <div class="pt-3 flex flex-col gap-2">
                    @auth
                        @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                            <a href="{{ route('superadmin.dashboard') }}" class="w-full py-2.5 text-center bg-amber-600 text-white font-bold rounded-xl">Dashboard Admin</a>
                        @else
                            <a href="{{ route('kol.dashboard') }}" class="w-full py-2.5 text-center bg-amber-600 text-white font-bold rounded-xl">Dashboard KOL</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 text-center text-red-600 font-bold border border-red-200 rounded-xl">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('brand.register') }}" class="w-full py-2.5 text-center bg-gray-900 text-white font-bold rounded-xl">
                            <i class="bi bi-building mr-1"></i> Daftar Brand
                        </a>
                        <a href="{{ route('registration.create') }}" class="w-full py-2.5 text-center bg-amber-600 text-white font-bold rounded-xl">
                            <i class="bi bi-person-plus mr-1"></i> Join as KOL
                        </a>
                        <a href="{{ route('login') }}" class="w-full py-2 text-center text-gray-700 font-bold">Masuk ke Akun</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- ================================
            HERO SECTION (Dual Proposition)
        ================================= --}}
        <section class="relative bg-gradient-to-b from-amber-50/60 via-white to-white py-16 lg:py-24 overflow-hidden" id="home">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    
                    {{-- Left Content --}}
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#f7eee8] text-[#b86021] text-xs font-bold tracking-wide uppercase border border-[#d57028]/20 font-heading">
                            <span class="w-2 h-2 rounded-full bg-[#d57028] animate-pulse"></span>
                            Ekosistem Influencer & Maklon Pak De Group
                        </div>

                        <h1 class="text-4xl lg:text-5xl xl:text-6xl font-black text-[#421b13] leading-[1.15] tracking-tight font-heading">
                            Percepat Pertumbuhan <span class="text-[#d57028]">Brand</span> Anda & Maksimalkan <span class="text-[#d5282d]">Cuan Kreator</span>.
                        </h1>

                        <p class="text-base lg:text-lg text-[#765f58] leading-relaxed max-w-2xl">
                            Majapahit Influence adalah platform all-in-one yang menghubungkan Brand dengan ribuan KOL & Affiliate terverifikasi. Dari produksi custom (maklon), bank konten promosi siap pakai, hingga penjualan e-commerce bergaransi komisi transparan.
                        </p>

                        {{-- Persona-First Primary Action Buttons --}}
                        <div class="space-y-3 pt-2">
                            <div class="flex flex-wrap items-center gap-3.5">
                                <a href="{{ route('brand.register') }}" class="px-6 py-3.5 rounded-2xl bg-[#421b13] hover:bg-[#190906] text-white font-extrabold text-sm shadow-md shadow-[#421b13]/10 flex items-center gap-2 transition-all hover:scale-105 font-heading focus-visible:ring-2 focus-visible:ring-[#421b13] focus-visible:outline-none">
                                    <i class="bi bi-rocket-takeoff-fill text-[#fec200] text-lg"></i>
                                    <span>Saya Brand: Mulai Promosi</span>
                                </a>

                                <a href="{{ route('registration.create') }}" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-[#d57028] to-[#b86021] hover:from-[#b86021] hover:to-[#934510] text-white font-extrabold text-sm shadow-md shadow-[#d57028]/20 flex items-center gap-2 transition-all hover:scale-105 font-heading focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none">
                                    <i class="bi bi-camera-reels-fill text-lg"></i>
                                    <span>Saya Kreator: Join KOL</span>
                                </a>

                                <a href="#demo-produk" class="px-5 py-3.5 rounded-2xl bg-white hover:bg-[#f7eee8] text-[#421b13] font-extrabold text-sm border border-[#421b13]/15 flex items-center gap-2 transition-all hover:-translate-y-0.5 font-heading focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:outline-none">
                                    <i class="bi bi-play-circle-fill text-[#d57028] text-lg"></i>
                                    <span>Tonton Demo</span>
                                </a>
                            </div>

                            <div class="flex items-center gap-2 text-xs font-semibold text-[#765f58] pt-1">
                                <span>Atau ingin lihat produk siap jual?</span>
                                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 text-[#d57028] hover:text-[#934510] font-bold hover:underline focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-[#d57028] rounded">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                    <span>Jelajahi Katalog E-Commerce & Bank Konten &rarr;</span>
                                </a>
                            </div>
                        </div>

                        {{-- Key Stats Highlight --}}
                        <div class="grid grid-cols-3 gap-4 pt-6 border-t border-gray-100 max-w-xl">
                            <div>
                                <strong class="block text-2xl lg:text-3xl font-black text-gray-900">5.000+</strong>
                                <span class="text-xs font-semibold text-gray-500">KOL & Affiliate Aktif</span>
                            </div>
                            <div>
                                <strong class="block text-2xl lg:text-3xl font-black text-amber-600">40%</strong>
                                <span class="text-xs font-semibold text-gray-500">Komisi Tetap Transparan</span>
                            </div>
                            <div>
                                <strong class="block text-2xl lg:text-3xl font-black text-gray-900">100+</strong>
                                <span class="text-xs font-semibold text-gray-500">Brand & Produk Maklon</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Interactive Visual Card --}}
                    <div class="lg:col-span-5 relative">
                        <div class="absolute -inset-4 bg-gradient-to-tr from-amber-400/20 to-red-400/20 rounded-3xl blur-2xl -z-10"></div>
                        
                        <div class="bg-white rounded-3xl p-6 shadow-2xl border border-gray-100 space-y-5">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold uppercase tracking-wider text-amber-700">Katalog E-Commerce</div>
                                        <div class="text-sm font-extrabold text-gray-900">Evermos-Style Marketplace</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-extrabold border border-green-200">
                                    <i class="bi bi-shield-check"></i> 40% Locked
                                </span>
                            </div>

                            {{-- Sample Featured Product Card Preview --}}
                            <div class="bg-[#fbf7f4] rounded-2xl p-4 border border-[#421b13]/10 flex gap-4 items-center">
                                <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=200&auto=format&fit=crop&q=80" alt="GlowUp Niacinamide 10% Serum" loading="lazy" class="w-20 h-20 rounded-xl object-cover shadow-xs">
                                <div class="space-y-1 flex-1 min-w-0">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#f7eee8] text-[#b86021] border border-[#d57028]/20 font-heading">Beauty & Skincare</span>
                                    <h4 class="text-xs font-bold text-[#421b13] truncate font-heading">GlowUp Niacinamide 10% Serum</h4>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-xs font-extrabold text-[#421b13]">Rp 120.000</span>
                                        <span class="text-xs font-black text-[#b86021] bg-[#f7eee8] px-2 py-0.5 rounded border border-[#d57028]/25 font-heading">+Rp 48.000 (40%)</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Bank Konten Feature Pill --}}
                            <div class="p-3.5 bg-gradient-to-r from-[#d57028] to-[#b86021] rounded-2xl text-white flex items-center justify-between shadow-xs">
                                <div class="flex items-center gap-2.5">
                                    <i class="bi bi-folder2-open text-2xl"></i>
                                    <div>
                                        <strong class="block text-xs font-bold">Bank Konten Brand Siap Unduh</strong>
                                        <span class="text-[11px] text-amber-100">Foto 4K, B-roll Video, & Script Copywriting</span>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right-circle-fill text-xl"></i>
                            </div>

                            {{-- 2 Promotion Pathways Quick Badges --}}
                            <div class="grid grid-cols-2 gap-2 text-center text-xs font-bold pt-1">
                                <div class="p-2.5 bg-gray-100 rounded-xl text-gray-800">
                                    <i class="bi bi-person-check-fill text-amber-600 mr-1"></i> Jalur Direct KOL
                                </div>
                                <div class="p-2.5 bg-gray-100 rounded-xl text-gray-800">
                                    <i class="bi bi-globe2 text-red-600 mr-1"></i> Jalur Marketplace
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ================================
            DEMO PRODUK (Cinematic Product Tour)
        ================================= --}}
        <section class="relative overflow-hidden bg-[#190906] py-20 text-white lg:py-28" id="demo-produk">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_10%_20%,rgba(213,112,40,0.3),transparent_28%),radial-gradient(circle_at_90%_80%,rgba(213,40,45,0.18),transparent_30%)]"></div>
            <div class="container relative mx-auto px-4 lg:px-8">
                <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-16">
                    <div class="lg:col-span-4">
                        <h2 class="max-w-md font-heading text-3xl font-black leading-tight tracking-tight sm:text-4xl">
                            Lihat bagaimana satu produk bergerak dari ide hingga jadi peluang.
                        </h2>
                        <p class="mt-5 max-w-md text-base leading-relaxed text-[#ddc5ba]">
                            Tur visual ini memperlihatkan alur Majapahit Influence: dari pengembangan brand, persiapan aset promosi, sampai katalog yang siap dipilih kreator.
                        </p>
                        <div class="mt-8 space-y-4 border-t border-white/15 pt-6 text-sm">
                            <div class="flex gap-3">
                                <i class="bi bi-building-check text-lg text-[#fec200]"></i>
                                <span class="text-[#f5e6df]">Brand dan kebutuhan promosi terhubung dalam satu alur.</span>
                            </div>
                            <div class="flex gap-3">
                                <i class="bi bi-folder2-open text-lg text-[#fec200]"></i>
                                <span class="text-[#f5e6df]">Aset siap pakai membuat kreator bisa bergerak lebih cepat.</span>
                            </div>
                            <div class="flex gap-3">
                                <i class="bi bi-graph-up-arrow text-lg text-[#fec200]"></i>
                                <span class="text-[#f5e6df]">Katalog dan komisi memberi jalur yang jelas untuk berjualan.</span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-8">
                        <div class="landing-demo-frame relative aspect-video overflow-hidden rounded-2xl">
                            <iframe
                                class="size-full"
                                src="{{ asset('motion-ui/index.html') }}"
                                title="Demo visual Majapahit Influence"
                                loading="lazy"
                                allow="autoplay"
                            ></iframe>
                        </div>
                        <p class="mt-4 flex items-center gap-2 text-xs font-semibold text-[#c69d8d]">
                            <i class="bi bi-play-fill text-[#fec200]"></i>
                            Demo visual interaktif Majapahit Influence
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================================
            SECTION SOLUSI BRAND (Mengapa Brand Harus Memilih Majapahit)
        ================================= --}}
        <section class="py-20 bg-white" id="solusi-brand">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="max-w-3xl mx-auto text-center space-y-4 mb-16">
                    <span class="px-3.5 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-bold uppercase tracking-wider">
                        Solusi Pemasaran Brand Terintegrasi
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight">
                        Mengapa Brand Anda Harus Bermitra dengan <span class="text-amber-600">Majapahit Agency</span>?
                    </h2>
                    <p class="text-gray-600 text-base">
                        Kami menjembatani brand Anda dengan ribuan kreator bertarget tanpa repot mengelola kontrak satu per satu, dengan sistem komisi yang adil dan transparan.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    {{-- Reason 1 --}}
                    <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-amber-200 transition-all space-y-4 group">
                        <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-110 transition-transform">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Jaringan 5.000+ KOL Terverifikasi</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Akses ke ribuan kreator di berbagai niche (Beauty, F&B, Fashion, Herbal, Gadget) dengan engagement rate riil dan pengikut organik.
                        </p>
                    </div>

                    {{-- Reason 2 --}}
                    <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-amber-200 transition-all space-y-4 group">
                        <div class="w-14 h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-110 transition-transform">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Komisi 40% Terkunci & Bebas Curang</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Persentase komisi produk Anda dikunci langsung di sistem backend. Tidak ada mark-up sepihak, memastikan transparansi 100% antara Brand, Agency, dan Kreator.
                        </p>
                    </div>

                    {{-- Reason 3 --}}
                    <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-amber-200 transition-all space-y-4 group">
                        <div class="w-14 h-14 rounded-2xl bg-gray-900 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-110 transition-transform">
                            <i class="bi bi-collection-play-fill"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Fitur Bank Konten Terstruktur</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Cukup upload materi foto, b-roll, dan copywriting produk sekali ke Bank Konten. Semua influencer dapat langsung mengambil materi promosi secara mandiri.
                        </p>
                    </div>
                </div>

                {{-- 2 PROMOTION PATHWAYS EXPLAINED --}}
                <div class="mt-16 bg-gradient-to-r from-amber-500/10 via-amber-100/30 to-red-500/10 rounded-3xl p-8 lg:p-12 border border-amber-200">
                    <div class="text-center max-w-2xl mx-auto mb-10">
                        <h3 class="text-2xl font-black text-gray-900">2 Pilihan Jalur Promosi untuk Brand Anda</h3>
                        <p class="text-sm text-gray-600 mt-2">Pilih metode pemasaran yang paling sesuai dengan kebutuhan strategi brand Anda.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        {{-- Pathway A --}}
                        <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-amber-100 text-amber-900 font-extrabold text-xs">
                                <i class="bi bi-hand-index-thumb-fill"></i> JALUR A
                            </div>
                            <h4 class="text-xl font-bold text-gray-900">Jalur Direct Selection (Pilih Influencer Spesifik)</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Brand bebas memilih dan menugaskan influencer tertentu dari direktori talent Majapahit sesuai kriteria persona, tier (Nano, Micro, Macro), dan niche yang diinginkan.
                            </p>
                            <ul class="text-xs font-semibold text-gray-700 space-y-2 pt-2">
                                <li class="flex items-center gap-2"><i class="bi bi-check2-circle text-green-600 text-base"></i> Penargetan profil influencer yang presisi</li>
                                <li class="flex items-center gap-2"><i class="bi bi-check2-circle text-green-600 text-base"></i> Brief konten & jadwal tayang yang dikustomisasi</li>
                            </ul>
                        </div>

                        {{-- Pathway B --}}
                        <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-red-100 text-red-900 font-extrabold text-xs">
                                <i class="bi bi-lightning-charge-fill"></i> JALUR B
                            </div>
                            <h4 class="text-xl font-bold text-gray-900">Jalur Marketplace Otomatis (Evermos-Style)</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Produk Anda otomatis di-listing di katalog e-commerce kami. Ribuan creator & affiliate dapat langsung memilih produk, mengunduh bahan dari Bank Konten, dan mempromosikannya.
                            </p>
                            <ul class="text-xs font-semibold text-gray-700 space-y-2 pt-2">
                                <li class="flex items-center gap-2"><i class="bi bi-check2-circle text-green-600 text-base"></i> Promosi masif tanpa batas kuota influencer</li>
                                <li class="flex items-center gap-2"><i class="bi bi-check2-circle text-green-600 text-base"></i> Pembayaran komisi hanya saat terjadi konversi/penjualan</li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center mt-8">
                        <a href="{{ route('brand.register') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-gray-900 hover:bg-black text-white font-extrabold text-sm shadow-xl hover:scale-105 transition-all">
                            <i class="bi bi-building-add"></i> Daftarkan Brand Anda Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================================
            SECTION LAYANAN MAKLON (Pak De Group)
        ================================= --}}
        <section class="py-20 bg-gray-900 text-white relative overflow-hidden" id="maklon">
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
            
            <div class="container mx-auto px-4 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    
                    <div class="lg:col-span-6 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/20 text-amber-400 text-xs font-bold tracking-wide uppercase border border-amber-500/30">
                            <i class="bi bi-gear-wide-connected"></i> LAYANAN MAKLON TERPADU
                        </div>

                        <h2 class="text-3xl lg:text-4xl xl:text-5xl font-black leading-tight">
                            Ingin Punya Brand Sendiri? Kami Buatkan Produknya & Jualkan Lewat <span class="text-amber-400">Ribuan Influencer</span>.
                        </h2>

                        <p class="text-gray-300 text-base leading-relaxed">
                            <strong>Maklon</strong> adalah layanan manufaktur kontrak di mana Pak De Group meracik, memproduksi, dan mengurus perizinan legalitas (BPOM, Halal, HKI) produk impian Anda dari nol: mulai dari formulasi skincare, suplemen herbal, hingga makanan ringan.
                        </p>

                        <div class="space-y-3 pt-2">
                            <div class="flex items-start gap-3 bg-gray-800/80 p-4 rounded-2xl border border-gray-700">
                                <i class="bi bi-check-circle-fill text-[#fec200] text-xl flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="block text-sm font-bold text-white font-heading">Formulasi & Legalitas Lengkap</strong>
                                    <span class="text-xs text-gray-400">Pabrik berstandar CPKB/CPPOB dengan jaminan izin edar resmi BPOM & Halal MUI.</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 bg-gray-800/80 p-4 rounded-2xl border border-gray-700">
                                <i class="bi bi-check-circle-fill text-[#fec200] text-xl flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="block text-sm font-bold text-white font-heading">Distribusi Langsung ke Jaringan KOL</strong>
                                    <span class="text-xs text-gray-400">Begitu produk selesai diproduksi, produk langsung masuk ke Bank Konten & dipromosikan jaringan kreator Majapahit.</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <a href="{{ route('brand.register') }}?need=maklon" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-[#d57028] to-[#b86021] hover:from-[#b86021] hover:to-[#934510] text-white font-extrabold text-sm inline-flex items-center gap-2 shadow-lg shadow-[#d57028]/25 transition-all hover:scale-105 font-heading focus-visible:ring-2 focus-visible:ring-white">
                                <i class="bi bi-chat-dots-fill text-[#fec200]"></i>
                                <span>Konsultasi Maklon Produk Sekarang</span>
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-6 grid grid-cols-2 gap-4">
                        <div class="bg-gray-800 p-6 rounded-3xl border border-gray-700 space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-bold">
                                01
                            </div>
                            <h4 class="text-base font-bold text-white">Riset & Formulasi</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Pengembangan sampel formula unik sesuai tren pasar terkini.</p>
                        </div>

                        <div class="bg-gray-800 p-6 rounded-3xl border border-gray-700 space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-bold">
                                02
                            </div>
                            <h4 class="text-base font-bold text-white">Legalitas & Desain</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Pendaftaran BPOM, Halal, merk HKI, dan desain packaging elegan.</p>
                        </div>

                        <div class="bg-gray-800 p-6 rounded-3xl border border-gray-700 space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-bold">
                                03
                            </div>
                            <h4 class="text-base font-bold text-white">Produksi Massal</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Produksi higienis dengan kontrol mutu (QC) standar industri.</p>
                        </div>

                        <div class="bg-gray-800 p-6 rounded-3xl border border-gray-700 space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl font-bold">
                                04
                            </div>
                            <h4 class="text-base font-bold text-white">Peluncuran & Viral</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Promosi serentak lewat jaringan KOL & live streaming Majapahit.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ================================
            SECTION EVERMOS-STYLE CATALOG PREVIEW
        ================================= --}}
        <section class="py-20 bg-amber-50/40" id="katalog-preview">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                    <div>
                        <span class="px-3.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider">
                            Katalog Produk E-Commerce (Evermos-Style)
                        </span>
                        <h2 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight mt-2">
                            Pilih Produk, Unduh Bahan, & Dapatkan <span class="text-amber-600">Komisi 40%</span>
                        </h2>
                    </div>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-amber-700 hover:text-amber-800 hover:underline">
                        Lihat Semua Produk di Katalog <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Product Grid Preview --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($featuredProducts ?? [] as $prod)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all overflow-hidden flex flex-col group">
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                <img src="{{ $prod->image_path ?: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=500&auto=format&fit=crop&q=80' }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-white/90 backdrop-blur-sm text-[11px] font-extrabold text-gray-800 shadow-sm">
                                    {{ $prod->category->name ?? 'Umum' }}
                                </span>
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-green-600 text-white text-[11px] font-black shadow-sm">
                                    Komisi {{ number_format($prod->locked_commission_percent, 0) }}%
                                </span>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div>
                                    <span class="text-xs font-semibold text-gray-500 block truncate">{{ $prod->brand->name ?? 'Brand Partner' }}</span>
                                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-amber-600 transition-colors line-clamp-2 mt-1">
                                        {{ $prod->name }}
                                    </h3>
                                </div>

                                <div class="pt-3 border-t border-gray-100 space-y-2">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-xs text-gray-500 font-medium">Harga Produk</span>
                                        <span class="text-sm font-black text-gray-900">{{ $prod->formatted_price }}</span>
                                    </div>
                                    <div class="flex items-baseline justify-between bg-amber-50 p-2 rounded-xl text-amber-900 font-extrabold text-xs">
                                        <span>Cuan Kamu:</span>
                                        <span class="text-amber-700 font-black">+{{ $prod->formatted_commission }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <a href="{{ route('catalog.show', $prod->slug) }}" class="py-2 text-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('catalog.content-bank', $prod->slug) }}" class="py-2 text-center rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm transition-colors flex items-center justify-center gap-1">
                                        <i class="bi bi-download"></i> Bank Konten
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 p-8 text-center bg-white rounded-3xl border border-gray-200">
                            <p class="text-gray-500">Belum ada produk unggulan di katalog.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ================================
            FEATURE SCREENSHOTS (Live Product Views)
        ================================= --}}
        <section class="bg-[#fff9f4] py-20 lg:py-28" id="fitur">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="font-heading text-3xl font-black tracking-tight text-[#421b13] sm:text-4xl">
                        Kenali fitur utamanya sebelum Anda mulai.
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-[#765f58]">
                        Tiga pratinjau langsung dari halaman Majapahit Influence, agar alurnya bisa dipahami dalam sekali lihat.
                    </p>
                </div>

                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    <a href="{{ route('catalog.index') }}" class="group block rounded-2xl bg-white p-3 shadow-[0_18px_45px_rgba(66,27,19,0.11)] transition-transform duration-300 hover:-translate-y-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                        <div class="landing-feature-preview relative aspect-video overflow-hidden rounded-xl bg-[#f7eee8]">
                            <iframe src="{{ route('catalog.index') }}" title="Pratinjau fitur katalog produk" loading="lazy" tabindex="-1" aria-hidden="true"></iframe>
                        </div>
                        <div class="px-2 pb-2 pt-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-heading text-lg font-extrabold text-[#421b13]">Katalog siap jual</h3>
                                <i class="bi bi-arrow-up-right text-lg text-[#d57028] transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5"></i>
                            </div>
                            <p class="mt-2 text-sm leading-relaxed text-[#765f58]">Temukan produk, harga, dan informasi komisi dalam satu halaman yang ringkas.</p>
                        </div>
                    </a>

                    <a href="{{ route('brand.register') }}" class="group block rounded-2xl bg-white p-3 shadow-[0_18px_45px_rgba(66,27,19,0.11)] transition-transform duration-300 hover:-translate-y-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                        <div class="landing-feature-preview relative aspect-video overflow-hidden rounded-xl bg-[#f7eee8]">
                            <iframe src="{{ route('brand.register') }}" title="Pratinjau pendaftaran brand" loading="lazy" tabindex="-1" aria-hidden="true"></iframe>
                        </div>
                        <div class="px-2 pb-2 pt-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-heading text-lg font-extrabold text-[#421b13]">Onboarding brand</h3>
                                <i class="bi bi-arrow-up-right text-lg text-[#d57028] transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5"></i>
                            </div>
                            <p class="mt-2 text-sm leading-relaxed text-[#765f58]">Mulai kemitraan dan arahkan kebutuhan promosi lewat formulir yang terstruktur.</p>
                        </div>
                    </a>

                    <a href="{{ route('registration.create') }}" class="group block rounded-2xl bg-white p-3 shadow-[0_18px_45px_rgba(66,27,19,0.11)] transition-transform duration-300 hover:-translate-y-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                        <div class="landing-feature-preview relative aspect-video overflow-hidden rounded-xl bg-[#f7eee8]">
                            <iframe src="{{ route('registration.create') }}" title="Pratinjau pendaftaran kreator" loading="lazy" tabindex="-1" aria-hidden="true"></iframe>
                        </div>
                        <div class="px-2 pb-2 pt-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-heading text-lg font-extrabold text-[#421b13]">Pendaftaran kreator</h3>
                                <i class="bi bi-arrow-up-right text-lg text-[#d57028] transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5"></i>
                            </div>
                            <p class="mt-2 text-sm leading-relaxed text-[#765f58]">Perkenalkan profil Anda dan ambil langkah pertama untuk masuk ke ekosistem KOL.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        {{-- ================================
            SECTION UNTUK KREATOR / KOL
        ================================= --}}
        <section class="py-20 bg-white" id="kreator">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="max-w-3xl mx-auto text-center space-y-4 mb-16">
                    <span class="px-3.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider">
                        Keuntungan Eksklusif Kreator
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight">
                        Waktunya Pengaruhmu Menghasilkan <span class="text-amber-600">Pendapatan Nyata</span>
                    </h2>
                    <p class="text-gray-600 text-base">
                        Bergabunglah dengan ekosistem Majapahit Influence dan nikmati kemudahan mempromosikan produk-produk viral dengan dukungan materi konten terlengkap.
                    </p>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-900">Komisi Tinggi 40%</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">Komisi persentase terkunci di setiap produk, tanpa potongan tersembunyi.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-900">Free Sample Produk</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">KOL terpilih berhak mendapatkan sampel produk gratis untuk dicoba dan direview.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-file-earmark-play"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-900">Akses Bank Konten</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">Dapatkan video footage B-roll, foto resolusi 4K, dan copywriting siap upload.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-900">Pencairan Dana Cepat</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">Tarik saldo komisi kapan saja langsung ke rekening bank atau e-wallet Anda.</p>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('registration.create') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-black text-sm shadow-xl shadow-amber-600/20 hover:scale-105 transition-all">
                        <i class="bi bi-person-plus-fill"></i> Daftar Jadi KOL Sekarang
                    </a>
                </div>
            </div>
        </section>

        {{-- ================================
            FAQ
        ================================= --}}
        <section class="bg-white py-20 lg:py-28" id="faq">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid gap-10 lg:grid-cols-12 lg:gap-16">
                    <div class="lg:col-span-4">
                        <h2 class="font-heading text-3xl font-black tracking-tight text-[#421b13] sm:text-4xl">
                            Pertanyaan yang biasanya muncul sebelum memulai.
                        </h2>
                        <p class="mt-4 max-w-md text-base leading-relaxed text-[#765f58]">
                            Kami merangkum hal yang paling sering ditanyakan Brand dan Kreator tentang cara kerja ekosistem Majapahit Influence.
                        </p>
                        <a href="{{ route('brand.register') }}" class="mt-7 inline-flex items-center gap-2 font-heading text-sm font-extrabold text-[#b86021] underline decoration-[#d57028]/35 underline-offset-4 transition-colors hover:text-[#934510] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                            Mulai sebagai Brand <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="divide-y divide-[#421b13]/10 lg:col-span-8">
                        <details class="group py-5" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-heading text-lg font-extrabold text-[#421b13] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                                Bagaimana Brand memulai kolaborasi?
                                <i class="bi bi-plus-lg shrink-0 text-[#d57028] transition-transform duration-200 group-open:rotate-45"></i>
                            </summary>
                            <p class="max-w-2xl pt-3 text-sm leading-relaxed text-[#765f58]">Brand dapat memulai dari halaman pendaftaran. Setelah itu, informasi kebutuhan promosi dan pilihan layanan menjadi dasar untuk menentukan langkah berikutnya.</p>
                        </details>

                        <details class="group py-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-heading text-lg font-extrabold text-[#421b13] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                                Apa perbedaan jalur Direct Selection dan Marketplace?
                                <i class="bi bi-plus-lg shrink-0 text-[#d57028] transition-transform duration-200 group-open:rotate-45"></i>
                            </summary>
                            <p class="max-w-2xl pt-3 text-sm leading-relaxed text-[#765f58]">Direct Selection cocok ketika Brand ingin memilih kreator tertentu. Jalur Marketplace menempatkan produk di katalog agar kreator dan affiliate dapat memilih produk yang ingin mereka promosikan.</p>
                        </details>

                        <details class="group py-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-heading text-lg font-extrabold text-[#421b13] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                                Untuk apa Bank Konten digunakan?
                                <i class="bi bi-plus-lg shrink-0 text-[#d57028] transition-transform duration-200 group-open:rotate-45"></i>
                            </summary>
                            <p class="max-w-2xl pt-3 text-sm leading-relaxed text-[#765f58]">Bank Konten menyimpan bahan promosi seperti foto, video B-roll, dan copywriting. Kreator dapat menggunakan aset yang disediakan Brand agar materi promosi tetap konsisten.</p>
                        </details>

                        <details class="group py-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-heading text-lg font-extrabold text-[#421b13] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                                Bisakah saya membuat produk melalui layanan maklon?
                                <i class="bi bi-plus-lg shrink-0 text-[#d57028] transition-transform duration-200 group-open:rotate-45"></i>
                            </summary>
                            <p class="max-w-2xl pt-3 text-sm leading-relaxed text-[#765f58]">Bisa. Layanan maklon membantu proses dari formulasi dan legalitas hingga produksi. Setelah siap, produk dapat masuk ke ekosistem promosi Majapahit Influence.</p>
                        </details>

                        <details class="group py-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-heading text-lg font-extrabold text-[#421b13] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#d57028] focus-visible:ring-offset-4">
                                Bagaimana Kreator menemukan produk untuk dipromosikan?
                                <i class="bi bi-plus-lg shrink-0 text-[#d57028] transition-transform duration-200 group-open:rotate-45"></i>
                            </summary>
                            <p class="max-w-2xl pt-3 text-sm leading-relaxed text-[#765f58]">Kreator dapat menjelajahi katalog untuk melihat produk yang tersedia, informasi komisi, serta akses ke bahan promosi yang berkaitan dengan produk tersebut.</p>
                        </details>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================================
            FOOTER
        ================================= --}}
        <footer class="bg-gray-950 text-white pt-16 pb-12 border-t border-gray-800">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-gray-800">
                    
                    {{-- Col 1: Brand & About --}}
                    <div class="space-y-4 md:col-span-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white p-0.5 flex items-center justify-center shadow-sm overflow-hidden">
                                <img src="{{ asset('assets/Logo/majapahit.png') }}" alt="Majapahit Influence Logo" class="size-full object-contain">
                            </div>
                            <span class="text-lg font-black tracking-tight text-white">MAJAPAHIT INFLUENCE</span>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Ekosistem pemasaran influencer dan solusi manufaktur maklon terpadu dari Pak De Group.
                        </p>
                    </div>

                    {{-- Col 2: Solusi Brand --}}
                    <div class="space-y-3">
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-400">Untuk Brand</h4>
                        <ul class="space-y-2 text-xs text-gray-400 font-medium">
                            <li><a href="{{ route('brand.register') }}" class="hover:text-white transition-colors">Daftar Kemitraan Brand</a></li>
                            <li><a href="#maklon" class="hover:text-white transition-colors">Layanan Maklon Produk</a></li>
                            <li><a href="#solusi-brand" class="hover:text-white transition-colors">2 Jalur Promosi</a></li>
                            <li><a href="#faq" class="hover:text-white transition-colors">Pertanyaan Umum</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login Portal</a></li>
                        </ul>
                    </div>

                    {{-- Col 3: Untuk Kreator --}}
                    <div class="space-y-3">
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-400">Untuk Kreator</h4>
                        <ul class="space-y-2 text-xs text-gray-400 font-medium">
                            <li><a href="{{ route('registration.create') }}" class="hover:text-white transition-colors">Join as KOL / Creator</a></li>
                            <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Katalog E-Commerce</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Dashboard KOL</a></li>
                        </ul>
                    </div>

                    {{-- Col 4: Kontak & Kantor --}}
                    <div class="space-y-3">
                        <h4 class="text-xs font-black uppercase tracking-wider text-[#fec200] font-heading">Hubungi Kami</h4>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            Pak De Group HQ: Surabaya & Mojokerto, Jawa Timur.<br>
                            WhatsApp: +62 812-3456-7890<br>
                            Email: halo@majapahit.com
                        </p>
                    </div>

                </div>

                <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-400 gap-4">
                    <p>&copy; {{ date('Y') }} Majapahit Influence | Pak De Group. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="{{ url('/') }}#solusi-brand" class="hover:text-white transition-colors">Kebijakan Brand</a>
                        <a href="{{ url('/') }}#kreator" class="hover:text-white transition-colors">Ketentuan Kreator</a>
                        <a href="{{ url('/') }}#faq" class="hover:text-white transition-colors">FAQ</a>
                    </div>
                </div>
            </div>
        </footer>
    </main>

@endsection
