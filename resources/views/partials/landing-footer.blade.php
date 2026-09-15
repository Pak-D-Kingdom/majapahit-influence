<footer class="site-footer">
    <div class="landing-container">
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

            {{-- Join / Portal (Dynamic based on Auth) --}}
            <div class="footer-column">
                @auth
                    <h4>Akun Saya</h4>
                    <a href="{{ auth()->user()->getDashboardUrl() }}">
                        <span>Buka Dashboard</span>
                        <i class="bi bi-speedometer2"></i>
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('superadmin.registrations.index') }}">
                            <span>Kelola Pendaftaran</span>
                            <i class="bi bi-card-checklist"></i>
                        </a>
                    @elseif(auth()->user()->isBrand())
                        <a href="{{ route('brand.products.index') }}">
                            <span>Produk Saya</span>
                            <i class="bi bi-box-seam"></i>
                        </a>
                    @elseif(auth()->user()->isKol())
                        <a href="{{ route('kol.profile.show') }}">
                            <span>Profil Creator</span>
                            <i class="bi bi-person-circle"></i>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="footer-logout-form">
                        @csrf
                        <button type="submit" class="footer-logout-btn">
                            <span>Keluar (Logout)</span>
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                @else
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
                @endauth
            </div>

            {{-- Contact / Social --}}
            <div class="footer-column footer-column-social">
                <h4>Terhubung</h4>
                <div class="footer-social-icons">
                    <a href="https://www.tiktok.com/@kerajaanagency?_r=1&_t=ZS-99c20KzWeXJ" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="TikTok" aria-label="TikTok">
                        <i class="bi bi-tiktok"></i>
                    </a>
                    <a href="https://www.instagram.com/kerajaanagency?stkn=MW1uY2w5dW9vYm54Yg==" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Instagram" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.threads.com/@kerajaanagency" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Threads" aria-label="Threads">
                        <i class="bi bi-threads"></i>
                    </a>
                    <a href="https://www.facebook.com/share/1DdSeDyV8r/" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Facebook" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://whatsapp.com/channel/0029VbDUpC07T8bZYVq3KW02" target="_blank" rel="noopener noreferrer" class="footer-social-btn footer-social-wa" title="WhatsApp Channel" aria-label="WhatsApp Channel">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
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
                @auth
                    <a href="{{ auth()->user()->getDashboardUrl() }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </div>
</footer>
