document.addEventListener('DOMContentLoaded', () => {

    
/* ============================================================
   AUTH / LOGIN
   Interaksi frontend halaman login.
   ============================================================ */

    /* ========================================================
       PASSWORD SHOW / HIDE
       ======================================================== */

    document
        .querySelectorAll('[data-password-toggle]')
        .forEach(toggle => {

            toggle.addEventListener('click', () => {

                const wrapper = toggle.closest('.auth-input-wrap');
                const input = wrapper?.querySelector('input');
                const icon = toggle.querySelector('i');

                if (!input) {
                    return;
                }

                const isPassword = input.type === 'password';

                input.type = isPassword
                    ? 'text'
                    : 'password';

                if (icon) {

                    icon.classList.toggle(
                        'bi-eye',
                        !isPassword
                    );

                    icon.classList.toggle(
                        'bi-eye-slash',
                        isPassword
                    );
                }

                toggle.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );
            });

        });


    /* ========================================================
       LOGIN FORM
       
       Frontend only.
       Submit belum dikirim ke backend.
       ======================================================== */

    const authForm = document.querySelector('[data-auth-form]');

    if (authForm) {

        authForm.addEventListener('submit', event => {

            event.preventDefault();

            /*
             * Backend authentication belum diimplementasikan.
             *
             * Form sengaja tidak melakukan redirect,
             * request POST, atau manipulasi database.
             */

        });

    }

});

    // ========================================
    // SIDEBAR
    // ========================================

    const sidebar = document.querySelector('#sa-sidebar');

    document
        .querySelector('[data-sidebar-toggle]')
        ?.addEventListener('click', () => {
            sidebar?.classList.toggle('open');
        });


    // ========================================
    // NAVIGASI & ACTIVE MENU
    // ========================================

    const path = location.pathname;

    document.querySelectorAll('.sa-nav a[data-nav]').forEach((link) => {
        const key = link.dataset.nav;

        if (
            path.includes(`/superadmin/${key}`) ||
            (key === 'dashboard' && path.endsWith('/superadmin'))
        ) {
            link.classList.add('active');
        }
    });


    // ========================================
    // TOAST NOTIFICATION
    // ========================================

    const toast = document.querySelector('#sa-toast');

    const showToast = (message) => {
        if (!toast) return;

        toast.textContent = message;
        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 2200);
    };

    document.querySelectorAll('[data-toast]').forEach((element) => {
        element.addEventListener('click', () => {
            showToast(element.dataset.toast || 'Berhasil');
        });
    });


    // ========================================
    // MODAL
    // ========================================

    document.querySelectorAll('[data-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            document
                .getElementById(button.dataset.modal)
                ?.classList.add('open');
        });
    });

    document.querySelectorAll('.sa-modal-close').forEach((button) => {
        button.addEventListener('click', () => {
            button
                .closest('.sa-modal')
                ?.classList.remove('open');
        });
    });

    document.querySelectorAll('.sa-modal').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.remove('open');
            }
        });
    });


    // ========================================
    // FORM LOADING
    // ========================================

    document.querySelectorAll('form[data-loading]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const button = form.querySelector(
                'button[type="submit"], button:last-of-type'
            );

            if (!button) return;

            button.disabled = true;

            const oldText = button.textContent;
            button.textContent = 'Menyimpan...';

            setTimeout(() => {
                button.disabled = false;
                button.textContent = oldText;

                showToast('Data berhasil disimpan (demo frontend)');

                form
                    .closest('.sa-modal')
                    ?.classList.remove('open');
            }, 700);
        });
    });


    // ========================================
    // APPROVAL
    // ========================================

    document.querySelectorAll('[data-approve]').forEach((button) => {
        button.addEventListener('click', () => {
            if (confirm('Approve data ini?')) {
                showToast('Approval diproses (demo frontend)');
            }
        });
    });


    // ========================================
    // REJECT
    // ========================================

    document.querySelectorAll('[data-reject]').forEach((button) => {
        button.addEventListener('click', () => {
            const reason = prompt('Masukkan alasan reject (wajib):');

            if (reason?.trim()) {
                showToast('Reject diproses (demo frontend)');
            }
        });
    });


    // ========================================
    // REVIEW PANEL
    // ========================================

    document.querySelectorAll('[data-review]').forEach((button) => {
        button.addEventListener('click', () => {
            document
                .querySelector('.sa-review-panel')
                ?.scrollIntoView({
                    behavior: 'smooth'
                });
        });
    });

