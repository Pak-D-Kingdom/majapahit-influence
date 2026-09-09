@extends('superadmin.layouts.app')

@section('title', 'Notifikasi')
@section('eyebrow', 'Superadmin')

@section('content')

    {{-- ============================================================
         HEADER HALAMAN
    ============================================================ --}}
    <div class="sa-page-head">

        <div>
            <h1>Semua Notifikasi</h1>
            <p>20 notifikasi terbaru dan status baca.</p>
        </div>

        <button
            class="sa-btn ghost"
            data-toast="Semua notifikasi ditandai sudah dibaca"
        >
            Tandai semua dibaca
        </button>

    </div>


    {{-- ============================================================
         FILTER NOTIFIKASI
    ============================================================ --}}
    <div class="sa-card sa-filters">

        {{-- FILTER TIPE --}}
        <select>
            <option>Semua tipe</option>
            <option>Pendaftaran KOL</option>
            <option>Deadline</option>
            <option>Bukti Konten</option>
            <option>Pencairan Komisi</option>
        </select>

        {{-- FILTER STATUS --}}
        <select>
            <option>Semua status</option>
            <option>Belum dibaca</option>
            <option>Sudah dibaca</option>
        </select>

        {{-- FILTER TANGGAL --}}
        <input type="date">

        {{-- TOMBOL FILTER --}}
        <button class="sa-btn ghost">
            Filter
        </button>

    </div>


    {{-- ============================================================
         DAFTAR NOTIFIKASI
    ============================================================ --}}
    <div class="sa-card sa-list big">

        {{-- ========================================================
             NOTIFIKASI 1
        ========================================================= --}}
        <div class="sa-list-item unread">

            <span class="sa-notif-icon orange">
                ✎
            </span>

            <div>
                <strong>Pendaftaran KOL baru masuk</strong>
                <p>
                    Nadia Putri mengirim pendaftaran REG-20260907-0008.
                </p>
                <small>8 menit lalu</small>
            </div>

        </div>


        {{-- ========================================================
             NOTIFIKASI 2
        ========================================================= --}}
        <div class="sa-list-item unread">

            <span class="sa-notif-icon red">
                !
            </span>

            <div>
                <strong>Endorsement mendekati deadline</strong>
                <p>
                    Endorsement Dimas Pratama jatuh tempo besok.
                </p>
                <small>32 menit lalu</small>
            </div>

        </div>


        {{-- ========================================================
             NOTIFIKASI 3
        ========================================================= --}}
        <div class="sa-list-item unread">

            <span class="sa-notif-icon yellow">
                Rp
            </span>

            <div>
                <strong>Permintaan pencairan komisi</strong>
                <p>
                    Sarah Amelia mengajukan pencairan Rp 2.600.000.
                </p>
                <small>1 jam lalu</small>
            </div>

        </div>


        {{-- ========================================================
             NOTIFIKASI 4
        ========================================================= --}}
        <div class="sa-list-item">

            <span class="sa-notif-icon orange">
                ▣
            </span>

            <div>
                <strong>Bukti konten di-upload</strong>
                <p>
                    Sarah Amelia mengunggah bukti konten campaign Rasa Lokal.
                </p>
                <small>2 jam lalu</small>
            </div>

        </div>

    </div>

@endsection
