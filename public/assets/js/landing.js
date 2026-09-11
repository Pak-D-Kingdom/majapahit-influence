document.addEventListener('DOMContentLoaded', () => {

    const header = document.getElementById('siteHeader');
    const menuToggle = document.getElementById('mobileMenuToggle');
    const mobileNav = document.getElementById('mobileNav');


    /*
    |--------------------------------------------------------------------------
    | Navbar scroll effect
    |--------------------------------------------------------------------------
    */

    const handleScroll = () => {

        if (window.scrollY > 30) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

    };

    window.addEventListener('scroll', handleScroll);

    handleScroll();


    /*
    |--------------------------------------------------------------------------
    | Mobile menu
    |--------------------------------------------------------------------------
    */

    if (menuToggle && mobileNav) {

        menuToggle.addEventListener('click', () => {

            const isCurrentlyOpen = mobileNav.classList.contains('open');

            if (isCurrentlyOpen) {
                mobileNav.classList.remove('open');
                mobileNav.classList.add('hidden');
                menuToggle.setAttribute('aria-expanded', 'false');

                const icon = menuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('bi-x-lg', 'bi-x');
                    icon.classList.add('bi-list');
                }
            } else {
                mobileNav.classList.add('open');
                mobileNav.classList.remove('hidden');
                menuToggle.setAttribute('aria-expanded', 'true');

                const icon = menuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('bi-list');
                    icon.classList.add('bi-x-lg');
                }
            }

        });


        /*
        | Close menu when clicking navigation
        */

        mobileNav.querySelectorAll('a').forEach(link => {

            link.addEventListener('click', () => {

                mobileNav.classList.remove('open');
                mobileNav.classList.add('hidden');
                menuToggle.setAttribute('aria-expanded', 'false');

                const icon = menuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('bi-x-lg', 'bi-x');
                    icon.classList.add('bi-list');
                }

            });

        });

    }

    /*
    |--------------------------------------------------------------------------
    | ScrollSpy & Active Menu Highlighting
    |--------------------------------------------------------------------------
    */
    const sectionIds = ['home', 'tentang', 'roles', 'mitra'];
    const sections = sectionIds
        .map(id => document.getElementById(id))
        .filter(Boolean);

    if (sections.length > 0) {
        const desktopNavLinks = document.querySelectorAll('.desktop-nav .nav-link, .desktop-nav .nav-link-ecommerce');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav .mobile-nav-link');

        const setActiveNav = (activeId) => {
            desktopNavLinks.forEach(link => {
                if (link.getAttribute('data-nav') === activeId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            mobileNavLinks.forEach(link => {
                if (link.getAttribute('data-nav') === activeId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        };

        const onScrollSpy = () => {
            const headerHeight = header ? header.offsetHeight : 80;
            const scrollPos = window.scrollY + headerHeight + 60;

            // Near top
            if (window.scrollY < 120) {
                setActiveNav('home');
                return;
            }

            // Near bottom of page
            if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 60) {
                setActiveNav(sections[sections.length - 1].id);
                return;
            }

            let currentSection = sections[0].id;
            for (let i = 0; i < sections.length; i++) {
                const section = sections[i];
                const top = section.offsetTop;
                const height = section.offsetHeight;

                if (scrollPos >= top && scrollPos < top + height) {
                    currentSection = section.id;
                    break;
                } else if (scrollPos >= top) {
                    currentSection = section.id;
                }
            }

            setActiveNav(currentSection);
        };

        window.addEventListener('scroll', onScrollSpy, { passive: true });
        onScrollSpy();

        // Smooth scroll for in-page anchors on landing page
        document.querySelectorAll('a[href*="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const href = anchor.getAttribute('href');
                if (!href) return;

                const hashIndex = href.indexOf('#');
                if (hashIndex === -1) return;

                const hash = href.substring(hashIndex);
                if (hash === '#' || hash === '') return;

                const targetEl = document.querySelector(hash);
                if (targetEl) {
                    const isSamePage = window.location.pathname === '/' || 
                                       window.location.pathname.endsWith('/index.php') ||
                                       href.startsWith('#');

                    if (isSamePage) {
                        e.preventDefault();
                        const headerOffset = header ? header.offsetHeight : 80;
                        const elementPosition = targetEl.getBoundingClientRect().top + window.pageYOffset;
                        const offsetPosition = Math.max(0, elementPosition - headerOffset);

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });

                        const targetId = hash.replace('#', '');
                        if (sectionIds.includes(targetId)) {
                            setActiveNav(targetId);
                        }
                    }
                }
            });
        });
    }

});