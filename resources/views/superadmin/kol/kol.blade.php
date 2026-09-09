@extends('superadmin.layouts.app')

@section('title', 'KOL')
@section('eyebrow', 'Superadmin')

@section('content')

    {{-- ============================================================
        HALAMAN MANAJEMEN KOL
    ============================================================ --}}


    {{-- ============================================================
        1. HEADER / PAGE HEADER
    ============================================================ --}}

    <div class="sa-page-head">

        <div>
            <h1>Manajemen KOL</h1>

            <p>
                Kelola profil, sosial media, rate card, status, dan riwayat KOL.
            </p>
        </div>

        {{-- Tombol membuka modal form tambah KOL --}}
        <button
            class="sa-btn primary"
            data-modal="kol-form"
        >
            + Tambah KOL
        </button>

    </div>


    {{-- ============================================================
        2. FILTER & PENCARIAN KOL
    ============================================================ --}}

    <div class="sa-card sa-filters">

        {{-- Search --}}
        <div class="sa-search">
            ⌕
            <input
                type="text"
                placeholder="Cari nama atau username..."
            >
        </div>

        {{-- Filter Niche --}}
        <select>
            <option>Semua niche</option>
            <option>Beauty</option>
            <option>Fashion</option>
            <option>Food</option>
            <option>Gaming</option>
        </select>

        {{-- Filter Platform --}}
        <select>
            <option>Semua platform</option>
            <option>Instagram</option>
            <option>TikTok</option>
            <option>YouTube</option>
        </select>

        {{-- Filter Tier --}}
        <select>
            <option>Semua tier</option>
            <option>Nano</option>
            <option>Micro</option>
            <option>Macro</option>
            <option>Mega</option>
        </select>

        {{-- Filter Status --}}
        <select>
            <option>Semua status</option>
            <option>Aktif</option>
            <option>Nonaktif</option>
            <option>Blacklist</option>
        </select>

        {{-- Reset --}}
        <button type="button" class="sa-btn ghost">
            Reset
        </button>

        {{-- Terapkan Filter --}}
        <button type="button" class="sa-btn primary">
            Filter
        </button>

    </div>

    {{-- ============================================================
        3. CARD DATA KOL
    ============================================================ --}}

    <div class="sa-card">

        {{-- --------------------------------------------------------
            3A. HEADER TABEL
        --------------------------------------------------------- --}}

        <div class="sa-card-head">

            <div>
                <h2>486 KOL Aktif</h2>

                <p>
                    Menampilkan 1–10 dari 486 data
                </p>
            </div>


            {{-- Pilihan jumlah data per halaman --}}
            <div class="kol-list-actions">

                <button
                    type="button"
                    class="sa-btn ghost"
                    data-toast="Export CSV siap diintegrasikan"
                >
                    Export CSV
                </button>

                <select class="small-select">
                    <option>10 / halaman</option>
                    <option>25 / halaman</option>
                    <option>50 / halaman</option>
                    <option>100 / halaman</option>
                </select>

            </div>

        </div>


        {{-- ========================================================
            4. TABEL DATA KOL
        ========================================================= --}}

        <table class="sa-table">

            {{-- ----------------------------------------------------
                4A. HEADER TABEL
            ----------------------------------------------------- --}}

            <thead>
                <tr>
                    <th>KOL</th>
                    <th>Niche</th>
                    <th>Platform</th>
                    <th>Followers</th>
                    <th>ER</th>
                    <th>Tier</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>


            {{-- ----------------------------------------------------
                4B. DATA KOL
            ----------------------------------------------------- --}}

            <tbody>


                {{-- =================================================
                    DATA KOL #1
                ================================================== --}}

                <tr>

                    <td>
                        <div class="person">

                            <div class="avatar-photo">
                                DP
                            </div>

                            <div>
                                <strong>Dimas Pratama</strong>
                                <small>@dimaslife</small>
                            </div>

                        </div>
                    </td>

                    <td>Lifestyle</td>

                    <td>Instagram</td>

                    <td>152K</td>

                    <td>4,8%</td>

                    <td>
                        <span class="tier">
                            Macro
                        </span>
                    </td>

                    <td>
                        <span class="status success">
                            Aktif
                        </span>
                    </td>

                    <td>
                        <button
                            class="sa-more"
                            data-toast="Menu KOL"
                        >
                            •••
                        </button>
                    </td>

                </tr>


                {{-- =================================================
                    DATA KOL #2
                ================================================== --}}

                <tr>

                    <td>
                        <div class="person">

                            <div class="avatar-photo">
                                SA
                            </div>

                            <div>
                                <strong>Sarah Amelia</strong>
                                <small>@sarahamelia</small>
                            </div>

                        </div>
                    </td>

                    <td>Beauty</td>

                    <td>TikTok</td>

                    <td>87K</td>

                    <td>6,1%</td>

                    <td>
                        <span class="tier">
                            Micro
                        </span>
                    </td>

                    <td>
                        <span class="status success">
                            Aktif
                        </span>
                    </td>

                    <td>
                        <button class="sa-more">
                            •••
                        </button>
                    </td>

                </tr>


                {{-- =================================================
                    DATA KOL #3
                ================================================== --}}

                <tr>

                    <td>
                        <div class="person">

                            <div class="avatar-photo">
                                FR
                            </div>

                            <div>
                                <strong>Fajar Rizky</strong>
                                <small>@fajarr</small>
                            </div>

                        </div>
                    </td>

                    <td>Tech</td>

                    <td>YouTube</td>

                    <td>1,2M</td>

                    <td>3,9%</td>

                    <td>
                        <span class="tier">
                            Mega
                        </span>
                    </td>

                    <td>
                        <span class="status success">
                            Aktif
                        </span>
                    </td>

                    <td>
                        <button class="sa-more">
                            •••
                        </button>
                    </td>

                </tr>


                {{-- =================================================
                    DATA KOL #4
                ================================================== --}}

                <tr>

                    <td>
                        <div class="person">

                            <div class="avatar-photo">
                                NA
                            </div>

                            <div>
                                <strong>Nadia Ananda</strong>
                                <small>@nadiaa</small>
                            </div>

                        </div>
                    </td>

                    <td>Food</td>

                    <td>Instagram</td>

                    <td>42K</td>

                    <td>7,2%</td>

                    <td>
                        <span class="tier">
                            Micro
                        </span>
                    </td>

                    <td>
                        <span class="status neutral">
                            Nonaktif
                        </span>
                    </td>

                    <td>
                        <button class="sa-more">
                            •••
                        </button>
                    </td>

                </tr>

            </tbody>

        </table>


        {{-- ========================================================
            5. PAGINATION
        ========================================================= --}}

        <div class="sa-pagination">

            <span>
                1–10 dari 486
            </span>

            <div>

                {{-- Halaman sebelumnya --}}
                <button>
                    ‹
                </button>

                {{-- Halaman aktif --}}
                <button class="active">
                    1
                </button>

                {{-- Halaman berikutnya --}}
                <button>
                    2
                </button>

                <button>
                    3
                </button>

                {{-- Halaman selanjutnya --}}
                <button>
                    ›
                </button>

            </div>

        </div>

    </div>


    {{-- ============================================================
        6. MODAL TAMBAH KOL
    ============================================================ --}}

    <div
        class="sa-modal"
        id="kol-form"
    >

        <div class="sa-modal-box">


            {{-- ----------------------------------------------------
                6A. TOMBOL CLOSE MODAL
            ----------------------------------------------------- --}}

            <button class="sa-modal-close">
                ×
            </button>


            {{-- ----------------------------------------------------
                6B. JUDUL MODAL
            ----------------------------------------------------- --}}

            <h2>
                Tambah KOL
            </h2>

            <p>
                Tambah KOL secara manual.
            </p>


            {{-- ====================================================
                7. FORM TAMBAH KOL
            ===================================================== --}}

            <form data-loading>


                {{-- ------------------------------------------------
                    7A. DATA IDENTITAS KOL
                ------------------------------------------------- --}}

                <div class="form-grid">

                    {{-- Nama lengkap --}}
                    <div class="form-group">
                        <label class="form-label">
                            Nama lengkap <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-input"
                            placeholder="Masukkan nama lengkap"
                        >
                    </div>

                    {{-- Nama panggilan --}}
                    <div class="form-group">
                        <label class="form-label">
                            Nama panggilan
                        </label>
                        <input
                            type="text"
                            class="form-input"
                            placeholder="Masukkan nama panggilan"
                        >
                    </div>


                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input
                            type="email"
                            class="form-input"
                            placeholder="contoh@email.com"
                        >
                    </div>


                    {{-- Nomor telepon --}}
                    <div class="form-group">
                        <label class="form-label">
                            Nomor telepon <span class="required">*</span>
                        </label>
                        <input
                            type="tel"
                            class="form-input"
                            placeholder="08xxxxxxxxxx"
                        >
                    </div>


                    {{-- Kota / domisili --}}
                    <div class="form-group">
                        <label class="form-label">
                            Kota/Domisili
                        </label>
                        <input
                            type="text"
                            class="form-input"
                            placeholder="Contoh: Surabaya"
                        >
                    </div>


                    {{-- Tanggal lahir --}}
                     <div class="form-group">
                        <label class="form-label">
                            Tanggal lahir
                        </label>
                        <input
                            type="date"
                            class="form-input"
                        >
                    </div>


                    {{-- Jenis kelamin --}}
                    <div class="form-group">
                        <label class="form-label">
                            Jenis kelamin
                        </label>
                        <select class="form-select">
                            <option value="">Pilih jenis kelamin</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>
                    </div>


                    {{-- Tier KOL --}}
                    <div class="form-group">
                        <label class="form-label">
                            Tier
                        </label>
                        <select class="form-select">
                            <option>Nano</option>
                            <option>Micro</option>
                            <option>Macro</option>
                            <option>Mega</option>
                        </select>
                    </div>

                </div>


                {{-- ------------------------------------------------
                    7B. NICHE / KATEGORI KOL
                ------------------------------------------------- --}}

                <div class="form-group full-width">
                    <label class="form-label">
                        Niche / kategori
                    </label>
                    <select class="form-select">
                        <option value="">Pilih niche / kategori</option>
                        <option value="lifestyle">Lifestyle</option>
                        <option value="beauty">Beauty</option>
                        <option value="fashion">Fashion</option>
                        <option value="food">Food</option>
                    </select>
                </div>


                {{-- ------------------------------------------------
                    7C. CATATAN KOL
                ------------------------------------------------- --}}

                <div class="form-group full-width">
                    <label class="form-label">
                        Catatan
                    </label>
                    <textarea
                        class="form-textarea"
                        placeholder="Tambahkan catatan jika diperlukan..."
                    ></textarea>
                </div>


                {{-- ------------------------------------------------
                    7D. SUBMIT FORM
                ------------------------------------------------- --}}

                <div class="kol-modal-footer">

                    <button type="button" class="btn-cancel">
                        Batal
                    </button>

                    <button type="submit" class="btn-save">
                        Simpan KOL
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
        SELESAI
        Section content Manajemen KOL
    ============================================================ --}}

@endsection
