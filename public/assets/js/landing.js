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

            mobileNav.classList.toggle('open');

            const icon = menuToggle.querySelector('i');

            if (mobileNav.classList.contains('open')) {

                icon.classList.remove('bi-list');
                icon.classList.add('bi-x');

            } else {

                icon.classList.remove('bi-x');
                icon.classList.add('bi-list');

            }

        });


        /*
        | Close menu when clicking navigation
        */

        mobileNav.querySelectorAll('a').forEach(link => {

            link.addEventListener('click', () => {

                mobileNav.classList.remove('open');

                const icon = menuToggle.querySelector('i');

                icon.classList.remove('bi-x');
                icon.classList.add('bi-list');

            });

        });

    }

});