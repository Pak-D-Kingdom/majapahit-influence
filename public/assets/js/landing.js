/**
 * KERAJAAN — Majapahit Influence
 * Clean Interactive JavaScript (No GSAP / No Canvas Layout Overrides)
 */

document.addEventListener('DOMContentLoaded', () => {

    const header = document.getElementById('siteHeader');
    const menuToggle = document.getElementById('mobileMenuToggle');
    const mobileNav = document.getElementById('mobileNav');

    /*
    |--------------------------------------------------------------------------
    | Navbar Scroll Effect
    |--------------------------------------------------------------------------
    */
    const handleScroll = () => {
        if (!header) return;
        if (window.scrollY > 30) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

    /*
    |--------------------------------------------------------------------------
    | Mobile Menu Toggle
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
    | Interactive Product Demo ("CARA KERJA") Controller
    |--------------------------------------------------------------------------
    */
    const initInteractiveProductDemo = () => {
        const demoSection = document.getElementById('how-it-works');
        if (!demoSection) return;

        const stepButtons = demoSection.querySelectorAll('.demo-step-btn');
        const slides = demoSection.querySelectorAll('.mockup-slide');
        const browserUrlText = document.getElementById('browserUrlText');
        const virtualCursor = document.getElementById('mockupVirtualCursor');
        const cursorLabel = document.getElementById('cursorActionLabel');
        const browserFrame = document.getElementById('demoBrowserFrame');
        const viewport = document.getElementById('browserViewport');

        // Modal Elements
        const modal = document.getElementById('demoVideoModal');
        const btnOpenModal = document.getElementById('btnOpenDemoModal');
        const btnCloseModal = document.getElementById('btnCloseDemoModal');
        const modalBackdrop = document.getElementById('demoModalBackdrop');
        const videoElement = document.getElementById('demoVideoElement');
        const fallbackStepBadge = document.getElementById('fallbackStepBadge');
        const fallbackStepTitle = document.getElementById('fallbackStepTitle');
        const fallbackStepDesc = document.getElementById('fallbackStepDesc');
        const fallbackScrubFill = document.getElementById('fallbackScrubFill');
        const fallbackPlayToggle = document.getElementById('fallbackPlayToggle');
        const fallbackPlayIcon = document.getElementById('fallbackPlayIcon');
        const fallbackTimeDisplay = document.getElementById('fallbackTimeDisplay');

        const steps = [
            {
                url: 'https://app.kerajaan.id/explore/creators',
                cursorLabel: 'Pilih Creator Kuliner...',
                targetSelector: '#step1ViewProfileBtn',
                badge: 'LANGKAH 01 DARI 05',
                title: 'Temukan & Filter Creator',
                desc: 'Jelajahi lebih dari 500+ creator terkurasi dengan filter kategori dan analitik keterlibatan langsung.'
            },
            {
                url: 'https://app.kerajaan.id/kol/sarah-amelia',
                cursorLabel: 'Ajak Kolaborasi...',
                targetSelector: '#step2InviteBtn',
                badge: 'LANGKAH 02 DARI 05',
                title: 'Analisis Profil & Kinerja Creator',
                desc: 'Analisis metrik riil: 142K+ pengikut, 4.9% tingkat interaksi, dan riwayat sukses kolaborasi.'
            },
            {
                url: 'https://app.kerajaan.id/campaigns/create',
                cursorLabel: 'Publikasikan Brief & Anggaran...',
                targetSelector: '#step3PublishBtn',
                badge: 'LANGKAH 03 DARI 05',
                title: 'Pengaturan Kampanye & Kebutuhan',
                desc: 'Buat brief kampanye, tetapkan deliverables Instagram Reels / TikTok, dan tentukan target alokasi budget.'
            },
            {
                url: 'https://app.kerajaan.id/collaborations/COL-2026-089',
                cursorLabel: 'Setujui Draft Konten...',
                targetSelector: '#step4ApproveBtn',
                badge: 'LANGKAH 04 DARI 05',
                title: 'Pusat Kolaborasi & Persetujuan',
                desc: 'Tinjau draft konten video dari creator, beri catatan revisi atau setujui secara langsung dalam satu workspace.'
            },
            {
                url: 'https://app.kerajaan.id/analytics/campaign-089',
                cursorLabel: 'Pantau Laporan Hasil...',
                targetSelector: '#step5DownloadBtn',
                badge: 'LANGKAH 05 DARI 05',
                title: 'Pantau Analitik Real-Time & ROI',
                desc: 'Pantau 1.2M+ impresi, 3.8x ROI, dan peringkat kontribusi creator secara real-time dengan grafik akurat.'
            }
        ];

        let activeIndex = 0;
        let cycleTimer = null;
        let isHovered = false;
        let isSectionInView = true;
        const CYCLE_INTERVAL = 5500;

        function updateModalFallback(index) {
            if (!fallbackStepBadge || !steps[index]) return;
            const current = steps[index];
            fallbackStepBadge.textContent = current.badge;
            fallbackStepTitle.textContent = current.title;
            fallbackStepDesc.textContent = current.desc;
        }

        function moveVirtualCursor(index) {
            if (!virtualCursor || window.innerWidth < 768) return;

            const stepInfo = steps[index];
            if (cursorLabel) cursorLabel.textContent = stepInfo.cursorLabel;

            setTimeout(() => {
                const target = demoSection.querySelector(stepInfo.targetSelector);
                if (!target || !viewport) return;

                const vpRect = viewport.getBoundingClientRect();
                const targetRect = target.getBoundingClientRect();

                const targetX = targetRect.left - vpRect.left + (targetRect.width / 2);
                const targetY = targetRect.top - vpRect.top + (targetRect.height / 2);

                virtualCursor.style.left = `${targetX}px`;
                virtualCursor.style.top = `${targetY}px`;
                virtualCursor.style.opacity = '1';
            }, 250);
        }

        function setActiveStep(index, isManual = false) {
            if (index < 0 || index >= steps.length) return;
            activeIndex = index;

            stepButtons.forEach((btn, idx) => {
                const isActive = idx === index;
                btn.classList.toggle('active', isActive);
                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            slides.forEach((slide, idx) => {
                const isActive = idx === index;
                slide.classList.toggle('active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            if (browserUrlText) {
                browserUrlText.textContent = steps[index].url;
            }

            moveVirtualCursor(index);
            updateModalFallback(index);

            if (isManual) {
                resetCycleTimer();
            }
        }

        function resetCycleTimer() {
            if (cycleTimer) clearInterval(cycleTimer);
            cycleTimer = setInterval(() => {
                if (!isHovered && isSectionInView) {
                    const nextIndex = (activeIndex + 1) % steps.length;
                    setActiveStep(nextIndex, false);
                }
            }, CYCLE_INTERVAL);
        }

        stepButtons.forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                setActiveStep(idx, true);
            });
        });

        if (browserFrame) {
            browserFrame.addEventListener('mouseenter', () => { isHovered = true; });
            browserFrame.addEventListener('mouseleave', () => { isHovered = false; });
        }

        const step1Btn = document.getElementById('step1ViewProfileBtn');
        if (step1Btn) {
            step1Btn.addEventListener('click', () => setActiveStep(1, true));
        }

        const step2Btn = document.getElementById('step2InviteBtn');
        if (step2Btn) {
            step2Btn.addEventListener('click', () => setActiveStep(2, true));
        }

        const step3Btn = document.getElementById('step3PublishBtn');
        if (step3Btn) {
            step3Btn.addEventListener('click', () => setActiveStep(3, true));
        }

        const step4Btn = document.getElementById('step4ApproveBtn');
        if (step4Btn) {
            step4Btn.addEventListener('click', () => setActiveStep(4, true));
        }

        setActiveStep(0, false);
        resetCycleTimer();

        // Modal Video Demo
        let fallbackTimer = null;
        let fallbackSeconds = 8;
        let isFallbackPlaying = true;

        const openModal = () => {
            if (!modal) return;
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            if (videoElement && !videoElement.paused) {
                videoElement.play().catch(() => {});
            }

            startFallbackSimulation();
        };

        const closeModal = () => {
            if (!modal) return;
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';

            if (videoElement) {
                videoElement.pause();
            }

            if (fallbackTimer) clearInterval(fallbackTimer);
        };

        const startFallbackSimulation = () => {
            if (fallbackTimer) clearInterval(fallbackTimer);
            fallbackTimer = setInterval(() => {
                if (!isFallbackPlaying) return;
                fallbackSeconds = (fallbackSeconds + 1) % 31;
                const formattedSec = fallbackSeconds.toString().padStart(2, '0');
                if (fallbackTimeDisplay) {
                    fallbackTimeDisplay.textContent = `00:${formattedSec} / 00:30`;
                }
                if (fallbackScrubFill) {
                    const pct = (fallbackSeconds / 30) * 100;
                    fallbackScrubFill.style.width = `${pct}%`;
                }

                const modalStepIdx = Math.floor(fallbackSeconds / 6) % steps.length;
                updateModalFallback(modalStepIdx);
            }, 1000);
        };

        if (btnOpenModal) {
            btnOpenModal.addEventListener('click', openModal);
        }

        if (btnCloseModal) {
            btnCloseModal.addEventListener('click', closeModal);
        }

        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', closeModal);
        }

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && modal.classList.contains('open')) {
                closeModal();
            }
        });

        if (fallbackPlayToggle) {
            fallbackPlayToggle.addEventListener('click', () => {
                isFallbackPlaying = !isFallbackPlaying;
                if (fallbackPlayIcon) {
                    fallbackPlayIcon.classList.toggle('bi-play-fill', !isFallbackPlaying);
                    fallbackPlayIcon.classList.toggle('bi-pause-fill', isFallbackPlaying);
                }
            });
        }
    };

    initInteractiveProductDemo();

    /*
    |--------------------------------------------------------------------------
    | ScrollSpy & Active Menu Highlighting
    |--------------------------------------------------------------------------
    */
    const sectionIds = ['home', 'tentang', 'roles', 'how-it-works', 'mitra'];
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

            if (window.scrollY < 120) {
                setActiveNav('home');
                return;
            }

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
