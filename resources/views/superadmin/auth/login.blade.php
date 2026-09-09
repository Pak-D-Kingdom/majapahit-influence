<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login — Majapahit Influence</title>

    <meta
        name="description"
        content="Login ke platform Majapahit Influence."
    >

    {{-- ============================================================
         GOOGLE FONTS
    ============================================================ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- ============================================================
         BOOTSTRAP ICONS
    ============================================================ --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- ============================================================
         SUPERADMIN CSS
         Login menggunakan CSS utama superadmin.
    ============================================================ --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/superadmin/css/superadmin.css') }}"
    >
</head>

<body class="auth-page">

    {{-- ============================================================
         LOGIN CONTAINER
    ============================================================ --}}
    <main class="auth-container">

        {{-- ========================================================
             BRAND
        ======================================================== --}}
        <div class="auth-brand">

            <div class="auth-brand-mark">
                M
            </div>

            <div class="auth-brand-text">
                <strong>MAJAPAHIT</strong>
                <span>INFLUENCE</span>
            </div>

        </div>


        {{-- ========================================================
             LOGIN CARD
        ======================================================== --}}
        <section class="auth-card">

            {{-- ====================================================
                 HEADER
            ==================================================== --}}
            <div class="auth-card-header">

                <span class="auth-eyebrow">
                    SUPERADMIN
                </span>

                <h1>
                    Selamat Datang Kembali
                </h1>

                <p>
                    Masuk untuk mengelola platform Majapahit Influence.
                </p>

            </div>


            {{-- ====================================================
                 LOGIN FORM
                 Frontend only.
                 Belum terhubung dengan backend authentication.
            ==================================================== --}}
            <form
                class="auth-form"
                data-auth-form
                action="#"
                method="POST"
                novalidate
            >

                {{-- ==================================================
                     EMAIL
                ================================================== --}}
                <div class="auth-field">

                    <div class="auth-label-row">
                        <label for="email">
                            Email
                        </label>
                    </div>

                    <div class="auth-input-wrap">

                        <i
                            class="bi bi-envelope"
                            aria-hidden="true"
                        ></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Masukkan email Anda"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>


                {{-- ==================================================
                     PASSWORD
                ================================================== --}}
                <div class="auth-field">

                    <div class="auth-label-row">

                        <label for="password">
                            Password
                        </label>

                        <a
                            href="#"
                            class="auth-forgot"
                        >
                            Lupa password?
                        </a>

                    </div>

                    <div class="auth-input-wrap">

                        <i
                            class="bi bi-lock"
                            aria-hidden="true"
                        ></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="auth-password-toggle"
                            data-password-toggle
                            aria-label="Tampilkan password"
                        >
                            <i
                                class="bi bi-eye"
                                aria-hidden="true"
                            ></i>
                        </button>

                    </div>

                </div>


                {{-- ==================================================
                     REMEMBER ME
                ================================================== --}}
                <label class="auth-remember">

                    <input
                        type="checkbox"
                        name="remember"
                    >

                    <span class="auth-checkmark"></span>

                    <span>
                        Ingat saya
                    </span>

                </label>


                {{-- ==================================================
                     SUBMIT
                ================================================== --}}
                <button
                    type="submit"
                    class="auth-submit"
                >
                    <span>Masuk</span>

                    <i
                        class="bi bi-arrow-right"
                        aria-hidden="true"
                    ></i>
                </button>

            </form>


            {{-- ====================================================
                 FOOTER CARD
            ==================================================== --}}
            <div class="auth-card-footer">

                <span>
                    Belum memiliki akses?
                </span>

                <a href="#">
                    Hubungi administrator
                </a>

            </div>

        </section>


        {{-- ========================================================
             BACK TO LANDING
        ======================================================== --}}
        <a
            href="{{ url('/') }}"
            class="auth-back"
        >
            <i
                class="bi bi-arrow-left"
                aria-hidden="true"
            ></i>

            <span>
                Kembali ke halaman utama
            </span>
        </a>

    </main>


    {{-- ============================================================
         DECORATIVE ELEMENTS
         Hanya visual, tidak memengaruhi fungsi login.
    ============================================================ --}}
    <div
        class="auth-decoration auth-decoration-left"
        aria-hidden="true"
    ></div>

    <div
        class="auth-decoration auth-decoration-right"
        aria-hidden="true"
    ></div>


    {{-- ============================================================
         SUPERADMIN JAVASCRIPT
         Login interaction menggunakan JS utama superadmin.
    ============================================================ --}}
    <script src="{{ asset('assets/superadmin/js/superadmin.js') }}"></script>

</body>
</html>

