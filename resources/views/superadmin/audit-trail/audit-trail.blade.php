@extends('superadmin.layouts.app')

@section('title', 'Audit Trail')
@section('eyebrow', 'Superadmin')

@section('content')

    {{-- ============================================================
         HEADER HALAMAN
    ============================================================ --}}
    <div class="sa-page-head">
        <div>
            <h1>Audit Trail</h1>
            <p>Riwayat aksi kritis dan perubahan data.</p>
        </div>
    </div>


    {{-- ============================================================
         FILTER & PENCARIAN AUDIT TRAIL
    ============================================================ --}}
    <div class="sa-card sa-filters">

        {{-- PENCARIAN --}}
        <div class="sa-search">
            ⌕
            <input placeholder="Cari aksi atau entity...">
        </div>

        {{-- FILTER AKSI --}}
        <select>
            <option>Semua aksi</option>
            <option>Approve</option>
            <option>Reject</option>
            <option>Perubahan Status</option>
            <option>Perubahan Komisi</option>
            <option>Login</option>
        </select>

        {{-- FILTER ENTITY --}}
        <select>
            <option>Semua entity</option>
            <option>KOL</option>
            <option>Endorsement</option>
            <option>Commission</option>
        </select>

        {{-- FILTER TANGGAL --}}
        <input type="date">
        <input type="date">

        {{-- TOMBOL FILTER --}}
        <button class="sa-btn ghost">
            Filter
        </button>

    </div>


    {{-- ============================================================
         TABEL DATA AUDIT TRAIL
    ============================================================ --}}
    <div class="sa-card">

        {{-- ========================================================
             HEADER TABEL
        ========================================================= --}}
        <table class="sa-table">

            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Entity</th>
                    <th>Perubahan</th>
                    <th>IP</th>
                </tr>
            </thead>


            {{-- ====================================================
                 DATA AUDIT TRAIL
            ===================================================== --}}
            <tbody>

                {{-- DATA AUDIT 1 --}}
                <tr>
                    <td>07 Sep 2026 09:02</td>
                    <td>Rina</td>
                    <td>
                        <span class="status success">
                            Approve
                        </span>
                    </td>
                    <td>Registration #0005</td>
                    <td>Pending Review → Approved</td>
                    <td>192.168.1.20</td>
                </tr>


                {{-- DATA AUDIT 2 --}}
                <tr>
                    <td>07 Sep 2026 08:48</td>
                    <td>Rina</td>
                    <td>Perubahan Status</td>
                    <td>KOL #0182</td>
                    <td>Nonaktif → Aktif</td>
                    <td>192.168.1.20</td>
                </tr>


                {{-- DATA AUDIT 3 --}}
                <tr>
                    <td>06 Sep 2026 17:21</td>
                    <td>Rina</td>
                    <td>Perubahan Komisi</td>
                    <td>Commission #992</td>
                    <td>65% → 70% · override</td>
                    <td>192.168.1.20</td>
                </tr>


                {{-- DATA AUDIT 4 --}}
                <tr>
                    <td>06 Sep 2026 16:05</td>
                    <td>Rina</td>
                    <td>Login</td>
                    <td>User #1</td>
                    <td>Login berhasil</td>
                    <td>192.168.1.20</td>
                </tr>

            </tbody>

        </table>

    </div>

@endsection
