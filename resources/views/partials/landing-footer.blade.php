<footer class="site-footer">
    <div class="container">
        {{-- Footer Main Grid --}}
        <div class="footer-main">
            {{-- Brand Column --}}
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="footer-logo">
                    <img src="{{ asset('assets/landing/images/logo/logokerajaantransv3-nobg.png') }}" alt="KERAJAAN">
                </a>
                <p>
                    Ekosistem Creator-Powered Commerce yang menghubungkan kreator, brand partner, produk unggulan, dan peluang kolaborasi.
                </p>
            </div>

            {{-- Navigation --}}
            <div class="footer-column">
                <h4>Navigasi</h4>
                <a href="{{ url('/') }}#home">Beranda</a>
                <a href="{{ url('/') }}#tentang">Tentang Kami</a>
                <a href="{{ url('/') }}#roles">Ekosistem</a>
                <a href="{{ url('/') }}#mitra">Produk & Brand</a>
                <a href="{{ route('catalog.index') }}">E-Commerce</a>
            </div>

            {{-- Join / Portal --}}
            <div class="footer-column">
                <h4>Pendaftaran</h4>
                <a href="{{ route('registration.create') }}">
                    <span>Gabung sebagai Creator</span>
                    <i class="bi bi-arrow-up-right"></i>
                </a>
                <a href="{{ route('brand.register') }}">
                    <span>Gabung sebagai Brand</span>
                    <i class="bi bi-arrow-up-right"></i>
                </a>
                <a href="{{ route('login') }}">
                    <span>Masuk ke Akun</span>
                    <i class="bi bi-box-arrow-in-right"></i>
                </a>
            </div>

            {{-- Contact / Social --}}
            <div class="footer-column">
                <h4>Terhubung</h4>
                <a href="#" class="footer-social">
                    <i class="bi bi-instagram"></i>
                    <span>Instagram</span>
                </a>
                <a href="#" class="footer-social">
                    <i class="bi bi-tiktok"></i>
                    <span>TikTok</span>
                </a>
                <a href="mailto:info@kerajaan.id" class="footer-social">
                    <i class="bi bi-envelope"></i>
                    <span>info@kerajaan.id</span>
                </a>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="footer-bottom">
            <span>
                &copy; {{ date('Y') }} KERAJAAN. All rights reserved.
            </span>
            <div class="footer-bottom-links">
                <a href="{{ url('/') }}#tentang">Tentang Kami</a>
                <a href="{{ route('catalog.index') }}">Katalog Produk</a>
                <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </div>
</footer>
