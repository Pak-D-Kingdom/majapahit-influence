@extends('superadmin.layouts.app')

@section('title', 'Pendaftaran KOL')
@section('eyebrow', 'Superadmin')

@section('content')

{{-- ============================================================
     HEADER HALAMAN
============================================================ --}}
<div class="sa-page-head">
    <div>
        <h1>Pendaftaran KOL</h1>
        <p>Review dan proses pendaftaran KOL baru.</p>
    </div>
</div>


{{-- ============================================================
     FILTER & PENCARIAN PENDAFTARAN KOL
============================================================ --}}
<div class="sa-card sa-filters">

    {{-- PENCARIAN --}}
    <div class="sa-search">
        ⌕
        <input placeholder="Cari nomor registrasi atau nama...">
    </div>

    {{-- FILTER STATUS --}}
    <select>
        <option>Semua status</option>
        <option>Pending Review</option>
        <option>Reviewed</option>
        <option>Approved</option>
        <option>Rejected</option>
    </select>

    {{-- FILTER NICHE --}}
    <select>
        <option>Semua niche</option>
        <option>Beauty</option>
        <option>Fashion</option>
        <option>Gaming</option>
        <option>Food</option>
    </select>

    {{-- FILTER TANGGAL --}}
    <input type="date">
    <input type="date">

    {{-- TOMBOL TERAPKAN FILTER --}}
    <button class="sa-btn ghost">
        Terapkan
    </button>

</div>


{{-- ============================================================
     TABEL PENDAFTARAN KOL
============================================================ --}}
<div class="sa-card">

    {{-- HEADER TABEL --}}
    <div class="sa-card-head">
        <div>
            <h2>8 Pendaftaran Pending</h2>
            <p>Review berdasarkan tanggal daftar.</p>
        </div>
    </div>


    {{-- TABEL DATA --}}
    <table class="sa-table">

        {{-- HEADER KOLOM --}}
        <thead>
            <tr>
                <th>No. Registrasi</th>
                <th>Nama</th>
                <th>Niche</th>
                <th>Platform</th>
                <th>Followers</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        {{-- DATA PENDAFTARAN --}}
        <tbody>

            {{-- DATA PENDAFTARAN 1 --}}
            <tr>
                <td>
                    <strong>REG-20260907-0008</strong>
                </td>
                <td>Nadia Putri</td>
                <td>Beauty</td>
                <td>Instagram</td>
                <td>62K</td>
                <td>7 Sep 2026</td>
                <td>
                    <span class="status warning">
                        Pending Review
                    </span>
                </td>
                <td>
                    <button
                        class="sa-btn tiny primary"
                        data-review
                    >
                        Review
                    </button>
                </td>
            </tr>


            {{-- DATA PENDAFTARAN 2 --}}
            <tr>
                <td>
                    <strong>REG-20260907-0007</strong>
                </td>
                <td>Rafi Maulana</td>
                <td>Gaming</td>
                <td>TikTok</td>
                <td>118K</td>
                <td>7 Sep 2026</td>
                <td>
                    <span class="status warning">
                        Pending Review
                    </span>
                </td>
                <td>
                    <button
                        class="sa-btn tiny primary"
                        data-review
                    >
                        Review
                    </button>
                </td>
            </tr>


            {{-- DATA PENDAFTARAN 3 --}}
            <tr>
                <td>
                    <strong>REG-20260906-0005</strong>
                </td>
                <td>Alya Sari</td>
                <td>Fashion</td>
                <td>Instagram</td>
                <td>31K</td>
                <td>6 Sep 2026</td>
                <td>
                    <span class="status success">
                        Approved
                    </span>
                </td>
                <td>
                    <button class="sa-link">
                        Detail
                    </button>
                </td>
            </tr>

        </tbody>
    </table>

</div>


{{-- ============================================================
     PANEL DETAIL & REVIEW PENDAFTARAN KOL
============================================================ --}}
<div class="sa-card sa-review-panel">

    {{-- HEADER DETAIL REVIEW --}}
    <div class="sa-card-head">
        <div>
            <span class="sa-chip">
                REG-20260907-0008
            </span>

            <h2>Nadia Putri</h2>
            <p>Detail pendaftaran & review internal</p>
        </div>

        <span class="status warning">
            Pending Review
        </span>
    </div>


    {{-- ========================================================
         DETAIL DATA PENDAFTAR
    ========================================================= --}}
    <div class="detail-grid">

        {{-- DATA PENDAFTAR --}}
        <div>
            <h3>Data Pendaftar</h3>

            <dl>
                <dt>Email</dt>
                <dd>nadia@example.com</dd>

                <dt>Telepon</dt>
                <dd>0812 3456 7890</dd>

                <dt>Kota</dt>
                <dd>Surabaya</dd>

                <dt>Niche</dt>
                <dd>Beauty, Lifestyle</dd>

                <dt>Platform</dt>
                <dd>
                    Instagram · @nadiaputri · 62K followers
                </dd>

                <dt>Rate Card</dt>
                <dd>
                    Rp 3.500.000 – Rp 6.000.000
                </dd>

                <dt>Alasan bergabung</dt>
                <dd>
                    Ingin mendapatkan campaign yang lebih terstruktur.
                </dd>
            </dl>
        </div>


        {{-- ====================================================
             PORTOFOLIO & REVIEW INTERNAL
        ===================================================== --}}
        <div>

            {{-- PORTOFOLIO --}}
            <h3>Portofolio</h3>

            <div class="file-grid">
                <div class="file-preview">
                    IMG
                    <br>
                    01
                </div>

                <div class="file-preview">
                    IMG
                    <br>
                    02
                </div>

                <div class="file-preview">
                    PDF
                    <br>
                    03
                </div>
            </div>


            {{-- REVIEW INTERNAL --}}
            <h3>Review Internal</h3>

            {{-- RATING --}}
            <div class="rating" data-rating>
                ☆ ☆ ☆ ☆ ☆
            </div>

            {{-- CATATAN REVIEW --}}
            <textarea placeholder="Catatan internal review..."></textarea>

        </div>

    </div>


    {{-- ========================================================
         TIMELINE REVIEW
    ========================================================= --}}
    <div class="timeline">

        <strong>Timeline Review</strong>

        <p>
            7 Sep 2026 · Pendaftaran diterima
        </p>

        <p>
            7 Sep 2026 · Notifikasi dibuat untuk Superadmin
        </p>

    </div>


    {{-- ========================================================
         AKSI REVIEW PENDAFTARAN
    ========================================================= --}}
    <div class="sa-actions">

        {{-- TOMBOL REJECT --}}
        <button
            class="sa-btn danger"
            data-reject
        >
            Reject
        </button>

        {{-- TOMBOL APPROVE --}}
        <button
            class="sa-btn primary"
            data-approve
        >
            Approve & Buat Akun KOL
        </button>

    </div>

</div>

@endsection
