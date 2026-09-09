@extends('superadmin.layouts.app')

@section('title', 'Komisi')
@section('eyebrow', 'Superadmin')

@section('content')

    {{-- ============================================================
         HEADER HALAMAN
    ============================================================ --}}
    <div class="sa-page-head">
        <div>
            <h1>Manajemen Komisi</h1>
            <p>Kelola perhitungan, approval, pencairan, dan bukti transfer.</p>
        </div>

        <button
            class="sa-btn ghost"
            data-toast="Export CSV/Excel siap diintegrasikan"
        >
            Export CSV / Excel
        </button>
    </div>


    {{-- ============================================================
         STATISTIK KOMISI
    ============================================================ --}}
    <div class="sa-grid sa-stats sa-compact">

        {{-- TOTAL PENDING --}}
        <div class="sa-stat">
            <span>Total Pending</span>
            <strong>Rp 42,8 jt</strong>
        </div>

        {{-- DICAIRKAN BULAN INI --}}
        <div class="sa-stat">
            <span>Dicairkan Bulan Ini</span>
            <strong>Rp 186,2 jt</strong>
        </div>

        {{-- OUTSTANDING --}}
        <div class="sa-stat">
            <span>Outstanding</span>
            <strong>Rp 128,4 jt</strong>
        </div>

    </div>


    {{-- ============================================================
         FILTER & PENCARIAN KOMISI
    ============================================================ --}}
    <div class="sa-card sa-filters">

        {{-- PENCARIAN --}}
        <div class="sa-search">
            ⌕
            <input placeholder="Cari KOL/campaign...">
        </div>

        {{-- FILTER STATUS --}}
        <select>
            <option>Semua status</option>
            <option>Pending</option>
            <option>Approved</option>
            <option>Diproses</option>
            <option>Dicairkan</option>
            <option>Rejected</option>
        </select>

        {{-- FILTER KOL --}}
        <select>
            <option>Semua KOL</option>
            <option>Dimas Pratama</option>
            <option>Sarah Amelia</option>
        </select>

        {{-- FILTER BRAND --}}
        <select>
            <option>Semua brand</option>
            <option>Glow Beauty</option>
            <option>Rasa Nusantara</option>
        </select>

        {{-- FILTER BULAN --}}
        <input
            type="month"
            value="2026-09"
        >

        {{-- TOMBOL FILTER --}}
        <button class="sa-btn ghost">
            Filter
        </button>

        {{-- TOMBOL BATCH APPROVE --}}
        <button
            class="sa-btn primary"
            data-toast="Batch approve diproses"
        >
            Batch Approve
        </button>

    </div>


    {{-- ============================================================
         TABEL DATA KOMISI
    ============================================================ --}}
    <div class="sa-card">

        <table class="sa-table">

            {{-- ====================================================
                 HEADER TABEL
            ===================================================== --}}
            <thead>
                <tr>
                    <th>
                        <input type="checkbox">
                    </th>
                    <th>KOL</th>
                    <th>Campaign / Brand</th>
                    <th>Fee</th>
                    <th>Komisi</th>
                    <th>%</th>
                    <th>Status</th>
                    <th>Pencairan</th>
                </tr>
            </thead>


            {{-- ====================================================
                 DATA KOMISI
            ===================================================== --}}
            <tbody>

                {{-- DATA KOMISI 1 --}}
                <tr>
                    <td>
                        <input type="checkbox">
                    </td>
                    <td>
                        <strong>Dimas Pratama</strong>
                    </td>
                    <td>Glow Up · Glow Beauty</td>
                    <td>Rp 7.500.000</td>
                    <td>Rp 5.250.000</td>
                    <td>70%</td>
                    <td>
                        <span class="status warning">
                            Pending
                        </span>
                    </td>
                    <td>—</td>
                </tr>


                {{-- DATA KOMISI 2 --}}
                <tr>
                    <td>
                        <input type="checkbox">
                    </td>
                    <td>
                        <strong>Sarah Amelia</strong>
                    </td>
                    <td>Rasa Lokal · Rasa Nusantara</td>
                    <td>Rp 4.000.000</td>
                    <td>Rp 2.600.000</td>
                    <td>65%</td>
                    <td>
                        <span class="status info">
                            Approved
                        </span>
                    </td>
                    <td>—</td>
                </tr>


                {{-- DATA KOMISI 3 --}}
                <tr>
                    <td>
                        <input type="checkbox">
                    </td>
                    <td>
                        <strong>Fajar Rizky</strong>
                    </td>
                    <td>Tech Smart · TechOne</td>
                    <td>Rp 12.000.000</td>
                    <td>Rp 9.000.000</td>
                    <td>75%</td>
                    <td>
                        <span class="status success">
                            Dicairkan
                        </span>
                    </td>
                    <td>5 Sep 2026</td>
                </tr>

            </tbody>

        </table>

    </div>


    {{-- ============================================================
         DETAIL KOMISI
    ============================================================ --}}
    <div class="sa-card">

        {{-- ========================================================
             HEADER DETAIL KOMISI
        ========================================================= --}}
        <div class="sa-card-head">

            <div>
                <h2>Detail Komisi — Dimas Pratama</h2>
                <p>Glow Up September · Glow Beauty</p>
            </div>

            <span class="status warning">
                Pending
            </span>

        </div>


        {{-- ========================================================
             PERHITUNGAN KOMISI
        ========================================================= --}}
        <div class="commission-calc">

            {{-- FEE ENDORSEMENT --}}
            <div>
                <span>Fee endorsement</span>
                <strong>Rp 7.500.000</strong>
            </div>

            {{-- PERSENTASE KOMISI --}}
            <div>
                <span>Persentase komisi</span>
                <strong>70% · Macro</strong>
            </div>

            {{-- KOMISI KOL --}}
            <div>
                <span>Komisi KOL</span>
                <strong>Rp 5.250.000</strong>
            </div>

            {{-- BAGIAN AGENSI --}}
            <div>
                <span>Bagian agensi</span>
                <strong>Rp 2.250.000</strong>
            </div>

        </div>


        {{-- ========================================================
             AKSI KOMISI
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
                Approve
            </button>

            {{-- TOMBOL TANDAI DICAIRKAN --}}
            <button class="sa-btn ghost">
                Tandai Dicairkan
            </button>

        </div>

    </div>

@endsection
