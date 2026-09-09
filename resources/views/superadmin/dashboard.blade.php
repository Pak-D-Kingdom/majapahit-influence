@extends('superadmin.layouts.app')

@section('title', 'Dashboard')
@section('eyebrow', 'Superadmin')

@section('content')

{{-- ============================================================
     HEADER HALAMAN
============================================================ --}}
<div class="sa-page-head">
    <div>
        <h1>Dashboard</h1>
        <p>Ringkasan operasional agensi hari ini.</p>
    </div>

    <span class="sa-date">
        7 September 2026
    </span>
</div>


{{-- ============================================================
     STATISTIK UTAMA DASHBOARD
============================================================ --}}
<div class="sa-grid sa-stats">

    {{-- KOL AKTIF --}}
    <a class="sa-stat" href="{{ url('/superadmin/kol') }}">
        <span>KOL Aktif</span>
        <strong>486</strong>
        <small>+12 bulan ini</small>
    </a>

    {{-- ENDORSEMENT BERJALAN --}}
    <a class="sa-stat" href="{{ url('/superadmin/campaigns') }}">
        <span>Endorsement Berjalan</span>
        <strong>38</strong>
        <small>12 perlu perhatian</small>
    </a>

    {{-- PENDING REVIEW --}}
    <a class="sa-stat sa-accent" href="{{ url('/superadmin/registrations') }}">
        <span>Pending Review</span>
        <strong>8</strong>
        <small>Perlu diproses</small>
    </a>

    {{-- PENCAIRAN PENDING --}}
    <a class="sa-stat" href="{{ url('/superadmin/commissions') }}">
        <span>Pencairan Pending</span>
        <strong>14</strong>
        <small>Rp 42.800.000</small>
    </a>

    {{-- KOMISI BELUM CAIR --}}
    <a class="sa-stat" href="{{ url('/superadmin/commissions') }}">
        <span>Komisi Belum Cair</span>
        <strong>Rp 128,4 jt</strong>
        <small>Bulan September</small>
    </a>

</div>


{{-- ============================================================
     GRID UTAMA DASHBOARD
============================================================ --}}
<div class="sa-grid sa-main-grid">


    {{-- ========================================================
         TREN ENDORSEMENT
    ========================================================= --}}
    <section class="sa-card">

        {{-- HEADER TREN ENDORSEMENT --}}
        <div class="sa-card-head">
            <div>
                <h2>Tren Endorsement</h2>
                <p>6 bulan terakhir</p>
            </div>

            <span class="sa-chip">
                2026
            </span>
        </div>


        {{-- GRAFIK TREN ENDORSEMENT --}}
        <div class="sa-chart">

            {{-- BAR GRAFIK --}}
            <div class="bars">
                <span style="height:42%">
                    <i>42</i>
                </span>

                <span style="height:55%">
                    <i>55</i>
                </span>

                <span style="height:49%">
                    <i>49</i>
                </span>

                <span style="height:72%">
                    <i>72</i>
                </span>

                <span style="height:66%">
                    <i>66</i>
                </span>

                <span style="height:84%">
                    <i>84</i>
                </span>
            </div>

            {{-- LABEL BULAN --}}
            <div class="chart-labels">
                <span>Apr</span>
                <span>Mei</span>
                <span>Jun</span>
                <span>Jul</span>
                <span>Agu</span>
                <span>Sep</span>
            </div>

        </div>

    </section>


    {{-- ========================================================
         NOTIFIKASI TERBARU
    ========================================================= --}}
    <section class="sa-card">

        {{-- HEADER NOTIFIKASI --}}
        <div class="sa-card-head">
            <div>
                <h2>Notifikasi Terbaru</h2>
                <p>5 terbaru</p>
            </div>

            <a href="{{ url('/superadmin/notifications') }}">
                Lihat semua
            </a>
        </div>


        {{-- DAFTAR NOTIFIKASI --}}
        <div class="sa-list">

            {{-- NOTIFIKASI 1 --}}
            <div class="sa-list-item">
                <span class="sa-dot orange"></span>

                <div>
                    <strong>Pendaftaran KOL baru</strong>
                    <small>
                        REG-20260907-0008 · 8 menit lalu
                    </small>
                </div>
            </div>


            {{-- NOTIFIKASI 2 --}}
            <div class="sa-list-item">
                <span class="sa-dot red"></span>

                <div>
                    <strong>Endorsement mendekati deadline</strong>
                    <small>
                        Deadline H-1 · 32 menit lalu
                    </small>
                </div>
            </div>


            {{-- NOTIFIKASI 3 --}}
            <div class="sa-list-item">
                <span class="sa-dot yellow"></span>

                <div>
                    <strong>Permintaan pencairan baru</strong>
                    <small>
                        Rp 4.500.000 · 1 jam lalu
                    </small>
                </div>
            </div>


            {{-- NOTIFIKASI 4 --}}
            <div class="sa-list-item">
                <span class="sa-dot orange"></span>

                <div>
                    <strong>Bukti konten di-upload</strong>
                    <small>
                        Campaign Glow Up · 2 jam lalu
                    </small>
                </div>
            </div>


            {{-- NOTIFIKASI 5 --}}
            <div class="sa-list-item">
                <span class="sa-dot red"></span>

                <div>
                    <strong>Deadline terlewati</strong>
                    <small>
                        1 endorsement · 3 jam lalu
                    </small>
                </div>
            </div>

        </div>

    </section>

</div>


{{-- ============================================================
     ENDORSEMENT MENDEKATI DEADLINE
============================================================ --}}
<section class="sa-card">

    {{-- HEADER ENDORSEMENT DEADLINE --}}
    <div class="sa-card-head">
        <div>
            <h2>Endorsement Mendekati Deadline</h2>
            <p>7 hari ke depan</p>
        </div>

        <a href="{{ url('/superadmin/campaigns') }}">
            Lihat semua
        </a>
    </div>


    {{-- TABEL ENDORSEMENT --}}
    <table class="sa-table">

        {{-- HEADER TABEL --}}
        <thead>
            <tr>
                <th>KOL</th>
                <th>Brand</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>


        {{-- DATA ENDORSEMENT --}}
        <tbody>

            {{-- DATA ENDORSEMENT 1 --}}
            <tr>
                <td>
                    <strong>Dimas Pratama</strong>
                    <small>@dimaslife</small>
                </td>

                <td>Glow Beauty</td>
                <td>8 Sep 2026</td>

                <td>
                    <span class="status warning">
                        In-Progress
                    </span>
                </td>

                <td>
                    <button
                        class="sa-link"
                        data-toast="Detail endorsement dibuka"
                    >
                        Detail
                    </button>
                </td>
            </tr>


            {{-- DATA ENDORSEMENT 2 --}}
            <tr>
                <td>
                    <strong>Sarah Amelia</strong>
                    <small>@sarahamelia</small>
                </td>

                <td>Rasa Nusantara</td>
                <td>10 Sep 2026</td>

                <td>
                    <span class="status info">
                        Content Submitted
                    </span>
                </td>

                <td>
                    <button
                        class="sa-link"
                        data-toast="Detail endorsement dibuka"
                    >
                        Detail
                    </button>
                </td>
            </tr>


            {{-- DATA ENDORSEMENT 3 --}}
            <tr>
                <td>
                    <strong>Fajar Rizky</strong>
                    <small>@fajarr</small>
                </td>

                <td>TechOne</td>
                <td>12 Sep 2026</td>

                <td>
                    <span class="status neutral">
                        Assigned
                    </span>
                </td>

                <td>
                    <button
                        class="sa-link"
                        data-toast="Detail endorsement dibuka"
                    >
                        Detail
                    </button>
                </td>
            </tr>

        </tbody>
    </table>

</section>


{{-- ============================================================
     PENDAFTARAN KOL TERBARU
============================================================ --}}
<section class="sa-card">

    {{-- HEADER PENDAFTARAN KOL --}}
    <div class="sa-card-head">
        <div>
            <h2>Pendaftaran KOL Terbaru</h2>
            <p>5 pendaftar terakhir</p>
        </div>

        <a href="{{ url('/superadmin/registrations') }}">
            Lihat semua
        </a>
    </div>


    {{-- TABEL PENDAFTARAN KOL --}}
    <table class="sa-table">

        {{-- HEADER TABEL --}}
        <thead>
            <tr>
                <th>No. Registrasi</th>
                <th>Nama</th>
                <th>Niche</th>
                <th>Platform</th>
                <th>Status</th>
            </tr>
        </thead>


        {{-- DATA PENDAFTARAN KOL --}}
        <tbody>

            {{-- DATA PENDAFTARAN 1 --}}
            <tr>
                <td>REG-20260907-0008</td>

                <td>
                    <strong>Nadia Putri</strong>
                </td>

                <td>Beauty</td>
                <td>Instagram</td>

                <td>
                    <span class="status warning">
                        Pending Review
                    </span>
                </td>
            </tr>


            {{-- DATA PENDAFTARAN 2 --}}
            <tr>
                <td>REG-20260907-0007</td>

                <td>
                    <strong>Rafi Maulana</strong>
                </td>

                <td>Gaming</td>
                <td>TikTok</td>

                <td>
                    <span class="status warning">
                        Pending Review
                    </span>
                </td>
            </tr>


            {{-- DATA PENDAFTARAN 3 --}}
            <tr>
                <td>REG-20260906-0005</td>

                <td>
                    <strong>Alya Sari</strong>
                </td>

                <td>Fashion</td>
                <td>Instagram</td>

                <td>
                    <span class="status success">
                        Approved
                    </span>
                </td>
            </tr>

        </tbody>
    </table>

</section>

@endsection
