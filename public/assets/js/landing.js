/**
 * KERAJAAN — Majapahit Influence
 * High-End 3D Motion & Interactive Script
 * Powered by GSAP 3 & HTML5 3D Canvas
 */

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Navbar & Mobile Navigation
    |--------------------------------------------------------------------------
    */
    const header = document.getElementById('siteHeader');
    const menuToggle = document.getElementById('mobileMenuToggle');
    const mobileNav = document.getElementById('mobileNav');

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

    // Check for user prefers-reduced-motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /*
    |--------------------------------------------------------------------------
    | 3D Interactive Ecosystem Constellation Canvas
    |--------------------------------------------------------------------------
    */
    const initHero3DCanvas = () => {
        const canvas = document.getElementById('hero3dCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        let width = 0;
        let height = 0;
        let animationFrameId = null;
        let isVisible = true;

        // 3D Scene parameters
        const nodeCount = window.innerWidth < 768 ? 36 : 64;
        const nodes = [];
        const focalLength = 340;
        const maxDistance = 120;

        let rotX = 0;
        let rotY = 0;
        let targetRotX = 0;
        let targetRotY = 0;

        const resize = () => {
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            width = canvas.parentElement?.clientWidth || window.innerWidth;
            height = canvas.parentElement?.clientHeight || window.innerHeight;

            canvas.width = width * dpr;
            canvas.height = height * dpr;
            canvas.style.width = `${width}px`;
            canvas.style.height = `${height}px`;

            ctx.scale(dpr, dpr);
        };

        // Create 3D spherical point cloud
        const initNodes = () => {
            nodes.length = 0;
            const radius = Math.min(width, height) * 0.42;

            for (let i = 0; i < nodeCount; i++) {
                // Spherical coordinate distribution
                const theta = Math.random() * Math.PI * 2;
                const phi = Math.acos(Math.random() * 2 - 1);
                const r = radius * (0.35 + Math.random() * 0.65);

                const x = r * Math.sin(phi) * Math.cos(theta);
                const y = r * Math.sin(phi) * Math.sin(theta) * 0.7; // slight vertical compression
                const z = r * Math.cos(phi);

                nodes.push({
                    x, y, z,
                    origX: x, origY: y, origZ: z,
                    baseRadius: Math.random() * 2.2 + 1.2,
                    colorType: Math.random() > 0.6 ? '#1698f6' : (Math.random() > 0.3 ? '#0b64d4' : '#78a5d6'),
                    pulseOffset: Math.random() * Math.PI * 2,
                    pulseSpeed: 0.02 + Math.random() * 0.02
                });
            }
        };

        // Track cursor for 3D camera rotation
        const handleMouseMove = (e) => {
            if (prefersReducedMotion) return;
            const rect = canvas.getBoundingClientRect();
            if (e.clientY < rect.top || e.clientY > rect.bottom) return;

            const normX = (e.clientX - rect.left) / rect.width - 0.5;
            const normY = (e.clientY - rect.top) / rect.height - 0.5;

            targetRotY = normX * 0.45;
            targetRotX = -normY * 0.35;
        };

        window.addEventListener('mousemove', handleMouseMove, { passive: true });

        // Pause animation when hero is off-screen
        const heroSection = document.getElementById('home');
        if (heroSection && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    isVisible = entry.isIntersecting;
                    if (isVisible && !animationFrameId) {
                        render();
                    }
                });
            }, { threshold: 0.05 });
            observer.observe(heroSection);
        }

        // Render loop with 3D projection & depth sorting
        let tick = 0;
        const render = () => {
            if (!isVisible) {
                animationFrameId = null;
                return;
            }

            tick += 0.01;
            ctx.clearRect(0, 0, width, height);

            // Interpolate rotation (smooth camera damping)
            rotX += (targetRotX - rotX) * 0.05;
            rotY += (targetRotY - rotY) * 0.05;

            // Idle auto rotation
            const currentRotY = rotY + tick * 0.15;
            const currentRotX = rotX + Math.sin(tick * 0.2) * 0.05;

            const cosY = Math.cos(currentRotY);
            const sinY = Math.sin(currentRotY);
            const cosX = Math.cos(currentRotX);
            const sinX = Math.sin(currentRotX);

            const centerX = width * 0.62; // Offset slightly towards hero visual
            const centerY = height * 0.48;

            const projected = [];

            // Project each 3D point to 2D screen
            for (let i = 0; i < nodes.length; i++) {
                const p = nodes[i];

                // Y-axis rotation
                let x1 = p.origX * cosY - p.origZ * sinY;
                let z1 = p.origZ * cosY + p.origX * sinY;

                // X-axis rotation
                let y1 = p.origY * cosX - z1 * sinX;
                let z2 = z1 * cosX + p.origY * sinX;

                // Subtle organic oscillation
                y1 += Math.sin(tick + p.pulseOffset) * 6;

                // Perspective projection
                const scale = focalLength / (focalLength + z2 + 300);
                if (scale <= 0) continue;

                const sx = centerX + x1 * scale;
                const sy = centerY + y1 * scale;
                const alpha = Math.max(0.08, Math.min(0.85, (z2 + 300) / 600));

                projected.push({
                    sx, sy,
                    z: z2,
                    scale,
                    alpha,
                    radius: p.baseRadius * scale * (1 + 0.2 * Math.sin(tick * 2 + p.pulseOffset)),
                    color: p.colorType
                });
            }

            // Draw dynamic 3D connection lines
            const lineLen = projected.length;
            for (let i = 0; i < lineLen; i++) {
                const p1 = projected[i];
                for (let j = i + 1; j < lineLen; j++) {
                    const p2 = projected[j];
                    const dx = p1.sx - p2.sx;
                    const dy = p1.sy - p2.sy;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < maxDistance) {
                        const lineAlpha = (1 - dist / maxDistance) * 0.25 * Math.min(p1.alpha, p2.alpha);
                        ctx.beginPath();
                        ctx.moveTo(p1.sx, p1.sy);
                        ctx.lineTo(p2.sx, p2.sy);
                        ctx.strokeStyle = `rgba(11, 100, 212, ${lineAlpha})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            }

            // Draw 3D nodes with depth lighting
            for (let i = 0; i < projected.length; i++) {
                const p = projected[i];

                // Glow halo
                const gradient = ctx.createRadialGradient(p.sx, p.sy, 0, p.sx, p.sy, p.radius * 3.5);
                gradient.addColorStop(0, `rgba(22, 152, 246, ${p.alpha * 0.5})`);
                gradient.addColorStop(1, 'rgba(22, 152, 246, 0)');
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(p.sx, p.sy, p.radius * 3.5, 0, Math.PI * 2);
                ctx.fill();

                // Solid core
                ctx.fillStyle = p.color;
                ctx.globalAlpha = p.alpha;
                ctx.beginPath();
                ctx.arc(p.sx, p.sy, Math.max(1, p.radius), 0, Math.PI * 2);
                ctx.fill();
                ctx.globalAlpha = 1;
            }

            animationFrameId = requestAnimationFrame(render);
        };

        window.addEventListener('resize', () => {
            resize();
            initNodes();
        }, { passive: true });

        resize();
        initNodes();
        render();
    };

    /*
    |--------------------------------------------------------------------------
    | GSAP 3D Interactive Parallax & Spatial Tilt for Hero
    |--------------------------------------------------------------------------
    */
    const initHero3DParallax = () => {
        if (prefersReducedMotion || typeof gsap === 'undefined') return;

        const hero = document.getElementById('home');
        const heroVisual = document.getElementById('heroVisual');
        const card = document.getElementById('heroCreatorCard');
        const campaign = document.getElementById('heroCampaignCard');
        const kol = document.getElementById('heroKolBadge');
        const circle = document.getElementById('heroCircle');

        if (!hero || !card) return;

        // Smooth spatial mouse tracking
        let bounds = hero.getBoundingClientRect();
        const updateBounds = () => {
            bounds = hero.getBoundingClientRect();
        };
        window.addEventListener('resize', updateBounds, { passive: true });

        hero.addEventListener('mousemove', (e) => {
            const relX = (e.clientX - bounds.left) / bounds.width - 0.5;
            const relY = (e.clientY - bounds.top) / bounds.height - 0.5;

            // 3D Card rotation
            gsap.to(card, {
                rotateY: -5 + relX * 16,
                rotateX: 4 - relY * 14,
                x: relX * 18,
                y: relY * 14,
                duration: 0.6,
                ease: 'power2.out',
                overwrite: 'auto'
            });

            // Floating campaign card (high depth)
            if (campaign) {
                gsap.to(campaign, {
                    x: relX * 36,
                    y: relY * 26,
                    rotateY: relX * 10,
                    duration: 0.75,
                    ease: 'power2.out',
                    overwrite: 'auto'
                });
            }

            // Floating KOL badge (counter depth)
            if (kol) {
                gsap.to(kol, {
                    x: relX * -28,
                    y: relY * -20,
                    rotateY: relX * -8,
                    duration: 0.7,
                    ease: 'power2.out',
                    overwrite: 'auto'
                });
            }

            // Decorative circle
            if (circle) {
                gsap.to(circle, {
                    x: relX * 42,
                    y: relY * 34,
                    duration: 0.85,
                    ease: 'power2.out',
                    overwrite: 'auto'
                });
            }
        });

        // Spring reset when cursor exits hero
        hero.addEventListener('mouseleave', () => {
            gsap.to(card, {
                rotateY: -5,
                rotateX: 4,
                x: 0,
                y: 0,
                duration: 1.2,
                ease: 'elastic.out(1, 0.5)',
                overwrite: 'auto'
            });

            if (campaign) {
                gsap.to(campaign, {
                    x: 0,
                    y: 0,
                    rotateY: 0,
                    duration: 1,
                    ease: 'elastic.out(1, 0.6)',
                    overwrite: 'auto'
                });
            }

            if (kol) {
                gsap.to(kol, {
                    x: 0,
                    y: 0,
                    rotateY: 0,
                    duration: 1,
                    ease: 'elastic.out(1, 0.6)',
                    overwrite: 'auto'
                });
            }

            if (circle) {
                gsap.to(circle, {
                    x: 0,
                    y: 0,
                    duration: 1,
                    ease: 'elastic.out(1, 0.6)',
                    overwrite: 'auto'
                });
            }
        });
    };

    /*
    |--------------------------------------------------------------------------
    | Organic Floating Physics Loop
    |--------------------------------------------------------------------------
    */
    const initOrganicFloating = () => {
        if (prefersReducedMotion || typeof gsap === 'undefined') return;

        const campaign = document.getElementById('heroCampaignCard');
        const kol = document.getElementById('heroKolBadge');
        const circle = document.getElementById('heroCircle');

        if (campaign) {
            gsap.to(campaign, {
                y: '-=10',
                duration: 3.2,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });
        }

        if (kol) {
            gsap.to(kol, {
                y: '+=12',
                duration: 3.8,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
                delay: 0.6
            });
        }

        if (circle) {
            gsap.to(circle, {
                rotate: 360,
                duration: 40,
                repeat: -1,
                ease: 'none'
            });
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Hero Entrance Choreography
    |--------------------------------------------------------------------------
    */
    const initHeroEntrance = () => {
        if (prefersReducedMotion || typeof gsap === 'undefined') return;

        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

        tl.from('.site-header', {
            y: -80,
            opacity: 0,
            duration: 0.9
        })
        .from('.eyebrow', {
            scale: 0.85,
            opacity: 0,
            y: -15,
            duration: 0.7
        }, '-=0.5')
        .from('.hero-content h1', {
            y: 35,
            opacity: 0,
            duration: 0.85
        }, '-=0.5')
        .from('.hero-description', {
            y: 20,
            opacity: 0,
            duration: 0.75
        }, '-=0.55')
        .from('.hero-actions a', {
            y: 20,
            opacity: 0,
            stagger: 0.12,
            duration: 0.65
        }, '-=0.45')
        .from('.hero-trust', {
            y: 20,
            opacity: 0,
            duration: 0.6
        }, '-=0.35')
        .from('#heroCreatorCard', {
            scale: 0.88,
            y: 50,
            rotateY: -18,
            opacity: 0,
            duration: 1.1,
            ease: 'back.out(1.4)'
        }, '-=1.0')
        .from('#heroCampaignCard', {
            scale: 0.8,
            x: 35,
            opacity: 0,
            duration: 0.75,
            ease: 'back.out(1.6)'
        }, '-=0.65')
        .from('#heroKolBadge', {
            scale: 0.8,
            x: -35,
            opacity: 0,
            duration: 0.75,
            ease: 'back.out(1.6)'
        }, '-=0.65');
    };

    /*
    |--------------------------------------------------------------------------
    | ScrollTrigger Section Animations & 3D Cards
    |--------------------------------------------------------------------------
    */
    const initScrollAnimations = () => {
        if (prefersReducedMotion || typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

        gsap.registerPlugin(ScrollTrigger);

        // About Section Reveal
        gsap.from('#tentang .about-header', {
            scrollTrigger: {
                trigger: '#tentang',
                start: 'top 80%'
            },
            y: 35,
            opacity: 0,
            duration: 0.8,
            ease: 'power3.out'
        });

        gsap.from('#tentang .company-identity', {
            scrollTrigger: {
                trigger: '#tentang .company-intro',
                start: 'top 80%'
            },
            x: -40,
            opacity: 0,
            duration: 0.9,
            ease: 'power3.out'
        });

        gsap.from('#tentang .company-content', {
            scrollTrigger: {
                trigger: '#tentang .company-intro',
                start: 'top 80%'
            },
            x: 40,
            opacity: 0,
            duration: 0.9,
            ease: 'power3.out'
        });

        // Ecosystem Role Cards (3D Entry & Hover Tilt)
        const roleCards = document.querySelectorAll('.role-card');
        roleCards.forEach((card, idx) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: '#roles',
                    start: 'top 75%'
                },
                y: 50,
                opacity: 0,
                rotateY: idx === 0 ? -6 : 6,
                duration: 0.9,
                delay: idx * 0.2,
                ease: 'power3.out'
            });

            // Interactive dynamic tilt on mousemove
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;

                gsap.to(card, {
                    rotateY: x * 10,
                    rotateX: -y * 8,
                    transformPerspective: 1000,
                    duration: 0.4,
                    ease: 'power2.out'
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    rotateY: 0,
                    rotateX: 0,
                    duration: 0.7,
                    ease: 'power2.out'
                });
            });
        });

        // Benefits / Steps Stagger
        const joinSteps = document.querySelectorAll('.join-step, .join-card, .faq-item');
        if (joinSteps.length > 0) {
            ScrollTrigger.batch(joinSteps, {
                onEnter: batch => gsap.from(batch, {
                    y: 30,
                    opacity: 0,
                    stagger: 0.12,
                    duration: 0.75,
                    ease: 'power3.out'
                }),
                start: 'top 85%',
                once: true
            });
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Initialize Motion Systems
    |--------------------------------------------------------------------------
    */
    initHero3DCanvas();
    initHero3DParallax();
    initOrganicFloating();
    initHeroEntrance();
    initScrollAnimations();
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
