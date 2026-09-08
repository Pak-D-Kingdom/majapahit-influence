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

});