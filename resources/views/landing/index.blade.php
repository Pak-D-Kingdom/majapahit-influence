@extends('layouts.app')

@section('title', 'Majapahit Influence — Platform Kolaborasi Brand & Influencer Terdepan')

@section('content')

    {{-- ================================
        TOP NAVBAR (Dual Audience & Catalog)
    ================================= --}}
    <header class="site-header sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100" id="siteHeader">
        <div class="container mx-auto px-4 lg:px-8 py-3.5 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-600 via-red-600 to-amber-500 flex items-center justify-center text-white font-black text-lg shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform">
                    MI
                </div>
                <div class="leading-tight">
                    <span class="block text-[11px] font-bold tracking-widest text-amber-700">MAJAPAHIT</span>
                    <strong class="block text-base font-extrabold text-gray-900 tracking-tight">INFLUENCE</strong>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold text-gray-600">
                <a href="#home" class="hover:text-amber-600 transition-colors">Beranda</a>
                <a href="#solusi-brand" class="hover:text-amber-600 transition-colors">Solusi Brand</a>
                <a href="#maklon" class="hover:text-amber-600 transition-colors">Layanan Maklon</a>
                <a href="#kreator" class="hover:text-amber-600 transition-colors">Untuk Kreator</a>
                <a href="{{ route('catalog.index') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-800 font-bold border border-amber-200/80 hover:bg-amber-100 transition-all">
                    <i class="bi bi-shop"></i> Katalog E-Commerce
                </a>
            </nav>

            {{-- Navbar Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                        <a href="{{ route('superadmin.dashboard') }}" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-sm transition-all">
                            <i class="bi bi-speedometer2 mr-1"></i> Dashboard Admin
                        </a>
                    @elseif (auth()->user()->isKol())
                        <a href="{{ route('kol.dashboard') }}" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-sm transition-all">
                            <i class="bi bi-speedometer2 mr-1"></i> Dashboard KOL
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-all">
                            <i class="bi bi-box-arrow-right mr-1"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-gray-700 hover:text-amber-600 transition-colors">
                        Masuk
                    </a>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('brand.register') }}" class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-black text-white font-bold text-xs uppercase tracking-wider shadow-sm hover:shadow transition-all">
                            <i class="bi bi-building mr-1"></i> Daftar Brand
                        </a>
                        <a href="{{ route('registration.create') }}" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm hover:shadow-amber-500/20 transition-all">
                            <i class="bi bi-person-plus mr-1"></i> Join as KOL
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <button class="lg:hidden p-2 text-gray-700 hover:text-amber-600 text-2xl" id="mobileMenuToggle" type="button">
                <i class="bi bi-list"></i>
            </button>
        </div>

        {{-- Mobile Nav Drawer --}}
        <div class="hidden lg:hidden px-4 pt-2 pb-6 bg-white border-b border-gray-200" id="mobileNav">
            <div class="flex flex-col gap-3 text-sm font-semibold text-gray-700">
                <a href="#home" class="py-2 border-b border-gray-100">Beranda</a>
                <a href="#solusi-brand" class="py-2 border-b border-gray-100">Solusi Brand</a>
                <a href="#maklon" class="py-2 border-b border-gray-100">Layanan Maklon</a>
                <a href="#kreator" class="py-2 border-b border-gray-100">Untuk Kreator</a>
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
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold tracking-wide uppercase">
                            <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                            Ekosistem Influencer & Maklon Pak De Group
                        </div>

                        <h1 class="text-4xl lg:text-5xl xl:text-6xl font-black text-gray-900 leading-[1.15] tracking-tight">
                            Percepat Pertumbuhan <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-red-600">Brand</span> Anda & Maksimalkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-amber-600">Cuan Kreator</span>.
                        </h1>

                        <p class="text-base lg:text-lg text-gray-600 leading-relaxed max-w-2xl">
                            Majapahit Influence adalah platform all-in-one yang menghubungkan Brand dengan ribuan KOL & Affiliate terverifikasi. Dari produksi custom (maklon), bank konten promosi siap pakai, hingga penjualan e-commerce bergaransi komisi transparan.
                        </p>

                        {{-- Dual Action Buttons --}}
                        <div class="flex flex-wrap gap-4 pt-2">
                            <a href="{{ route('brand.register') }}" class="px-6 py-3.5 rounded-2xl bg-gray-900 hover:bg-black text-white font-extrabold text-sm shadow-lg shadow-gray-900/10 flex items-center gap-2 transition-all hover:scale-105">
                                <i class="bi bi-rocket-takeoff-fill text-amber-400 text-lg"></i>
                                <span>Saya Brand — Mulai Promosi</span>
                            </a>

                            <a href="{{ route('registration.create') }}" class="px-6 py-3.5 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm shadow-lg shadow-amber-600/20 flex items-center gap-2 transition-all hover:scale-105">
                                <i class="bi bi-camera-reels-fill text-lg"></i>
                                <span>Saya Kreator — Join KOL</span>
                            </a>

                            <a href="{{ route('catalog.index') }}" class="px-5 py-3.5 rounded-2xl bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 font-bold text-sm flex items-center gap-2 shadow-sm transition-all">
                                <i class="bi bi-grid-3x3-gap-fill text-amber-600"></i>
                                <span>Katalog E-Commerce</span>
                            </a>
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
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 flex gap-4 items-center">
                                <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=200&auto=format&fit=crop&q=80" alt="Sample Product" class="w-20 h-20 rounded-xl object-cover shadow-sm">
                                <div class="space-y-1 flex-1 min-w-0">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Beauty & Skincare</span>
                                    <h4 class="text-xs font-bold text-gray-900 truncate">GlowUp Niacinamide 10% Serum</h4>
                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-xs font-extrabold text-gray-900">Rp 120.000</span>
                                        <span class="text-xs font-black text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">+Rp 48.000 (40%)</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Bank Konten Feature Pill --}}
                            <div class="p-3.5 bg-gradient-to-r from-amber-500 to-amber-600 rounded-2xl text-white flex items-center justify-between">
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
                            <strong>Maklon</strong> adalah layanan manufaktur kontrak di mana Pak De Group meracik, memproduksi, dan mengurus perizinan legalitas (BPOM, Halal, HKI) produk impian Anda dari nol — mulai dari formulasi skincare, suplemen herbal, hingga makanan ringan.
                        </p>

                        <div class="space-y-3 pt-2">
                            <div class="flex items-start gap-3 bg-gray-800/80 p-4 rounded-2xl border border-gray-700">
                                <i class="bi bi-check-circle-fill text-amber-400 text-xl flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="block text-sm font-bold text-white">Formulasi & Legalitas Lengkap</strong>
                                    <span class="text-xs text-gray-400">Pabrik berstandar CPKB/CPPOB dengan jaminan izin edar resmi BPOM & Halal MUI.</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 bg-gray-800/80 p-4 rounded-2xl border border-gray-700">
                                <i class="bi bi-check-circle-fill text-amber-400 text-xl flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="block text-sm font-bold text-white">Distribusi Langsung ke Jaringan KOL</strong>
                                    <span class="text-xs text-gray-400">Begitu produk selesai diproduksi, produk langsung masuk ke Bank Konten & dipromosikan jaringan kreator Majapahit.</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <a href="{{ route('brand.register') }}?need=maklon" class="px-7 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-sm inline-flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all hover:scale-105">
                                <i class="bi bi-chat-dots-fill"></i> Konsultasi Maklon Produk Sekarang
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
            FOOTER
        ================================= --}}
        <footer class="bg-gray-950 text-white pt-16 pb-12 border-t border-gray-800">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-gray-800">
                    
                    {{-- Col 1: Brand & About --}}
                    <div class="space-y-4 md:col-span-1">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-600 flex items-center justify-center text-white font-black text-base">
                                MI
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
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-400">Hubungi Kami</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Pak De Group HQ — Surabaya & Mojokerto, Jawa Timur.<br>
                            WhatsApp: +62 812-3456-7890<br>
                            Email: halo@majapahit.com
                        </p>
                    </div>

                </div>

                <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                    <p>&copy; {{ date('Y') }} Majapahit Influence — Pak De Group. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-gray-300">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-gray-300">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </footer>
    </main>

@endsection
