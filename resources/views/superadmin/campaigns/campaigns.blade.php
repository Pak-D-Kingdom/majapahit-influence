@extends('superadmin.layouts.app')

@section('title', 'Campaign')
@section('eyebrow', 'Superadmin')

@section('content')


{{-- ============================================================
     HEADER HALAMAN
============================================================ --}}

<div class="sa-page-head">

    <div>
        <h1>Campaign & Endorsement</h1>

        <p>
            Buat campaign, assign KOL, monitor progress, dan verifikasi bukti konten.
        </p>
    </div>

    <button
        class="sa-btn primary"
        data-modal="campaign-form"
    >
        + Buat Campaign
    </button>

</div>


{{-- ============================================================
     FILTER & PENCARIAN CAMPAIGN
============================================================ --}}

<div class="sa-card sa-filters">

    <div class="sa-search">
        ⌕
        <input placeholder="Cari campaign...">
    </div>

    <select>
        <option>Semua brand</option>
        <option>Glow Beauty</option>
        <option>Rasa Nusantara</option>
        <option>TechOne</option>
    </select>

    <select>
        <option>Semua status</option>
        <option>Draft</option>
        <option>Aktif</option>
        <option>Selesai</option>
    </select>

    <input type="date">

    <input type="date">

    <button class="sa-btn ghost">
        Filter
    </button>

</div>


{{-- ============================================================
     TABEL DATA CAMPAIGN
============================================================ --}}

<div class="sa-card">

    {{-- --------------------------------------------------------
         HEADER TABEL
    --------------------------------------------------------- --}}

    <table class="sa-table">

        <thead>
            <tr>
                <th>Campaign</th>
                <th>Brand</th>
                <th>Periode</th>
                <th>KOL Assigned</th>
                <th>Progress</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>


        {{-- --------------------------------------------------------
             DATA CAMPAIGN
        --------------------------------------------------------- --}}

        <tbody>

            {{-- DATA CAMPAIGN 1 --}}
            <tr>

                <td>
                    <strong>Glow Up September</strong>
                    <small>Campaign beauty Q3</small>
                </td>

                <td>
                    Glow Beauty
                </td>

                <td>
                    1–20 Sep 2026
                </td>

                <td>
                    12
                </td>

                <td>

                    <div class="progress">
                        <i style="width:67%"></i>
                    </div>

                    <small>
                        8/12 selesai
                    </small>

                </td>

                <td>
                    <span class="status info">
                        Aktif
                    </span>
                </td>

                <td>
                    <button
                        class="sa-link"
                        data-toast="Detail campaign"
                    >
                        Detail
                    </button>
                </td>

            </tr>


            {{-- DATA CAMPAIGN 2 --}}
            <tr>

                <td>
                    <strong>Rasa Lokal</strong>
                    <small>Food creator activation</small>
                </td>

                <td>
                    Rasa Nusantara
                </td>

                <td>
                    5–30 Sep 2026
                </td>

                <td>
                    8
                </td>

                <td>

                    <div class="progress">
                        <i style="width:38%"></i>
                    </div>

                    <small>
                        3/8 selesai
                    </small>

                </td>

                <td>
                    <span class="status info">
                        Aktif
                    </span>
                </td>

                <td>
                    Detail
                </td>

            </tr>


            {{-- DATA CAMPAIGN 3 --}}
            <tr>

                <td>
                    <strong>Tech Smart</strong>
                </td>

                <td>
                    TechOne
                </td>

                <td>
                    1–15 Sep 2026
                </td>

                <td>
                    7
                </td>

                <td>

                    <div class="progress">
                        <i style="width:100%"></i>
                    </div>

                    <small>
                        7/7 selesai
                    </small>

                </td>

                <td>
                    <span class="status success">
                        Selesai
                    </span>
                </td>

                <td>
                    Detail
                </td>

            </tr>

        </tbody>

    </table>

</div>


{{-- ============================================================
     DETAIL CAMPAIGN
============================================================ --}}

<div class="sa-card">

    {{-- --------------------------------------------------------
         HEADER DETAIL CAMPAIGN
    --------------------------------------------------------- --}}

    <div class="sa-card-head">

        <div>

            <h2>
                Detail: Glow Up September
            </h2>

            <p>
                Glow Beauty · 1–20 September 2026 · Budget Rp 85.000.000
            </p>

        </div>

        <button
            class="sa-btn primary"
            data-modal="assign-form"
        >
            + Assign KOL
        </button>

    </div>


    {{-- --------------------------------------------------------
         BRIEF CAMPAIGN
    --------------------------------------------------------- --}}

    <div class="brief-box">

        <strong>
            Brief
        </strong>

        <p>
            Konten edukasi skincare untuk rangkaian Glow Up.
            Sertakan product shot dan CTA.
        </p>

        <div class="sa-tags">

            <span>
                Feed Post
            </span>

            <span>
                Reels
            </span>

            <span>
                Instagram
            </span>

        </div>

    </div>


    {{-- --------------------------------------------------------
         TABEL KOL CAMPAIGN
    --------------------------------------------------------- --}}

    <table class="sa-table">

        <thead>
            <tr>
                <th>KOL</th>
                <th>Tipe Konten</th>
                <th>Deadline</th>
                <th>Fee</th>
                <th>Status</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>


        <tbody>

            {{-- DATA KOL CAMPAIGN 1 --}}
            <tr>

                <td>
                    <strong>
                        Dimas Pratama
                    </strong>
                </td>

                <td>
                    Reels
                </td>

                <td>
                    8 Sep
                </td>

                <td>
                    Rp 7.500.000
                </td>

                <td>
                    <span class="status warning">
                        In-Progress
                    </span>
                </td>

                <td>
                    —
                </td>

                <td>
                    Detail
                </td>

            </tr>


            {{-- DATA KOL CAMPAIGN 2 --}}
            <tr>

                <td>
                    <strong>
                        Sarah Amelia
                    </strong>
                </td>

                <td>
                    Feed Post
                </td>

                <td>
                    10 Sep
                </td>

                <td>
                    Rp 4.000.000
                </td>

                <td>
                    <span class="status info">
                        Content Submitted
                    </span>
                </td>

                <td>

                    <button
                        class="sa-link"
                        data-toast="Preview bukti konten"
                    >
                        Lihat
                    </button>

                </td>

                <td>

                    <button
                        class="sa-btn tiny primary"
                        data-toast="Panel verifikasi"
                    >
                        Verifikasi
                    </button>

                </td>

            </tr>

        </tbody>

    </table>

</div>


{{-- ============================================================
     MODAL BUAT CAMPAIGN
============================================================ --}}
<div
    class="sa-modal"
    id="campaign-form"
>
    <div class="campaign-modal">

        {{-- ====================================================
             HEADER
        ===================================================== --}}
        <div class="campaign-modal-header">

            <div>
                <h2 class="campaign-modal-title">
                    Buat Campaign
                </h2>

                <p class="campaign-modal-description">
                    Buat campaign baru untuk mengatur endorsement KOL.
                </p>
            </div>

            <button
                type="button"
                class="campaign-modal-close sa-modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>


        {{-- ====================================================
             BODY
        ===================================================== --}}
        <div class="campaign-modal-body">

            <form data-loading>

                {{-- ============================================
                     SECTION 1 — INFORMASI CAMPAIGN
                ============================================= --}}
                <div class="campaign-form-section">

                    <div class="campaign-form-section-title">
                        Informasi Campaign
                    </div>

                    <div class="campaign-form-grid">

                        {{-- Nama Campaign --}}
                        <div class="campaign-form-group">

                            <label class="campaign-form-label">
                                Nama Campaign
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                class="campaign-form-input"
                                placeholder="Contoh: Glow Up September"
                                required
                            >

                        </div>


                        {{-- Brand --}}
                        <div class="campaign-form-group">

                            <label class="campaign-form-label">
                                Brand
                                <span class="required">*</span>
                            </label>

                            <select
                                class="campaign-form-select"
                                required
                            >
                                <option value="">Pilih brand</option>
                                <option>Glow Beauty</option>
                                <option>Rasa Nusantara</option>
                                <option>TechOne</option>
                            </select>

                        </div>

                    </div>

                </div>


                {{-- ============================================
                     SECTION 2 — PERIODE & ANGGARAN
                ============================================= --}}
                <div class="campaign-form-section">

                    <div class="campaign-form-section-title">
                        Periode & Anggaran
                    </div>

                    <div class="campaign-form-grid">

                        {{-- Tanggal Mulai --}}
                        <div class="campaign-form-group">

                            <label class="campaign-form-label">
                                Tanggal Mulai
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                class="campaign-form-input"
                                required
                            >

                        </div>


                        {{-- Tanggal Selesai --}}
                        <div class="campaign-form-group">

                            <label class="campaign-form-label">
                                Tanggal Selesai
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                class="campaign-form-input"
                                required
                            >

                        </div>


                        {{-- Budget --}}
                        <div class="campaign-form-group">

                            <label class="campaign-form-label">
                                Budget
                            </label>

                            <div class="campaign-input-prefix">
                                <span>Rp</span>

                                <input
                                    type="number"
                                    class="campaign-form-input"
                                    placeholder="85.000.000"
                                >
                            </div>

                        </div>


                        {{-- Attachment --}}
                        <div class="campaign-form-group">

                            <label class="campaign-form-label">
                                Brief Attachment
                            </label>

                            <input
                                type="file"
                                class="campaign-form-file"
                                accept=".pdf,.jpg,.jpeg,.png"
                            >

                            <small class="campaign-form-help">
                                PDF, JPG, JPEG, PNG · Maks. 5 MB
                            </small>

                        </div>

                    </div>

                </div>


                {{-- ============================================
                     SECTION 3 — BRIEF & KETENTUAN
                ============================================= --}}
                <div class="campaign-form-section">

                    <div class="campaign-form-section-title">
                        Brief & Ketentuan Konten
                    </div>


                    {{-- Deskripsi --}}
                    <div class="campaign-form-group full-width">

                        <label class="campaign-form-label">
                            Deskripsi / Brief
                        </label>

                        <textarea
                            class="campaign-form-textarea"
                            placeholder="Jelaskan tujuan, konsep, dan pesan utama campaign..."
                        ></textarea>

                    </div>


                    {{-- Persyaratan Konten --}}
                    <div class="campaign-form-group full-width">

                        <label class="campaign-form-label">
                            Persyaratan Konten
                        </label>

                        <textarea
                            class="campaign-form-textarea"
                            placeholder="Contoh: wajib menampilkan produk, CTA, hashtag, mention brand..."
                        ></textarea>

                    </div>


                    {{-- Dos & Don'ts --}}
                    <div class="campaign-form-group full-width">

                        <label class="campaign-form-label">
                            Do's & Don'ts
                        </label>

                        <textarea
                            class="campaign-form-textarea"
                            placeholder="Tuliskan hal yang wajib dilakukan dan hal yang harus dihindari..."
                        ></textarea>

                    </div>

                </div>

            </form>

        </div>


        {{-- ====================================================
             FOOTER
        ===================================================== --}}
        <div class="campaign-modal-footer">

            <button
                type="button"
                class="campaign-btn-cancel sa-modal-close"
            >
                Batal
            </button>

            <button
                type="submit"
                class="campaign-btn-save"
            >
                Simpan Draft
            </button>

        </div>

    </div>
</div>

{{-- ============================================================
     MODAL ASSIGN KOL
============================================================ --}}

<div
    class="sa-modal"
    id="assign-form"
>
    <div class="assign-modal">

        {{-- HEADER --}}
        <div class="assign-modal-header">

            <div>
                <span class="modal-eyebrow">CAMPAIGN ASSIGNMENT</span>

                <h2 class="assign-modal-title">
                    Assign KOL
                </h2>

                <p class="assign-modal-description">
                    Pilih KOL aktif dan tentukan detail endorsement.
                </p>
            </div>

            <button
                type="button"
                class="modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>


        {{-- BODY --}}
        <div class="assign-modal-body">

            {{-- ==================================================
                 SECTION 1 — PILIH KOL
            =================================================== --}}

            <section class="assign-section">

                <div class="assign-section-head">
                    <div>
                        <h3>Pilih KOL</h3>
                        <p>
                            Hanya KOL dengan status aktif yang dapat dipilih.
                        </p>
                    </div>

                    <span class="selected-count">
                        0 dipilih
                    </span>
                </div>


                {{-- SEARCH --}}
                <div class="assign-search">

                    <span class="search-icon">
                        ⌕
                    </span>

                    <input
                        type="text"
                        placeholder="Cari berdasarkan nama KOL..."
                    >

                </div>


                {{-- KOL LIST --}}
                <div class="assign-list">

                    <label class="kol-option">

                        <input
                            type="checkbox"
                            name="kol[]"
                            value="dimas"
                        >

                        <span class="kol-check"></span>

                        <span class="kol-info">

                            <strong>
                                Dimas Pratama
                            </strong>

                            <small>
                                Macro · 152K followers
                            </small>

                        </span>

                        <span class="kol-tier">
                            Macro
                        </span>

                    </label>


                    <label class="kol-option">

                        <input
                            type="checkbox"
                            name="kol[]"
                            value="sarah"
                        >

                        <span class="kol-check"></span>

                        <span class="kol-info">

                            <strong>
                                Sarah Amelia
                            </strong>

                            <small>
                                Micro · 87K followers
                            </small>

                        </span>

                        <span class="kol-tier">
                            Micro
                        </span>

                    </label>


                    <label class="kol-option">

                        <input
                            type="checkbox"
                            name="kol[]"
                            value="fajar"
                        >

                        <span class="kol-check"></span>

                        <span class="kol-info">

                            <strong>
                                Fajar Rizky
                            </strong>

                            <small>
                                Mega · 1,2M followers
                            </small>

                        </span>

                        <span class="kol-tier">
                            Mega
                        </span>

                    </label>

                </div>

            </section>


            {{-- ==================================================
                 SECTION 2 — DETAIL ENDORSEMENT
            =================================================== --}}

            <section class="assign-section">

                <div class="assign-section-head">
                    <div>
                        <h3>Detail Endorsement</h3>
                        <p>
                            Tentukan format konten, deadline, dan fee.
                        </p>
                    </div>
                </div>


                <div class="assign-form-grid">

                    {{-- TIPE KONTEN --}}
                    <div class="assign-field">

                        <label for="content_type">
                            Tipe konten
                        </label>

                        <select
                            id="content_type"
                            class="assign-input"
                        >
                            <option value="">
                                Pilih tipe konten
                            </option>

                            <option>
                                Reels
                            </option>

                            <option>
                                Feed Post
                            </option>

                            <option>
                                Story
                            </option>

                            <option>
                                Video
                            </option>
                        </select>

                    </div>


                    {{-- DEADLINE --}}
                    <div class="assign-field">

                        <label for="deadline">
                            Deadline
                        </label>

                        <input
                            id="deadline"
                            type="date"
                            class="assign-input"
                        >

                    </div>


                    {{-- FEE --}}
                    <div class="assign-field full">

                        <label for="endorsement_fee">
                            Fee endorsement
                        </label>

                        <div class="currency-input">

                            <span>Rp</span>

                            <input
                                id="endorsement_fee"
                                type="number"
                                placeholder="7.500.000"
                            >

                        </div>

                        <small class="field-help">
                            Masukkan fee endorsement untuk KOL yang dipilih.
                        </small>

                    </div>

                </div>

            </section>

        </div>


        {{-- FOOTER --}}
        <div class="assign-modal-footer">

            <button
                type="button"
                class="btn-cancel"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-save"
            >
                Assign KOL
            </button>

        </div>

    </div>
</div>

@endsection
