@extends('superadmin.layouts.app')

@section('title', 'Kelola User')
@section('eyebrow', 'Superadmin')

@section('content')


{{-- ============================================================
     HEADER HALAMAN
============================================================ --}}

<div class="sa-page-head">

    <div>
        <h1>Kelola User</h1>

        <p>
            Kelola user internal sesuai permission matrix.
        </p>
    </div>

    <button
        class="sa-btn primary"
        data-modal="user-form"
    >
        + Tambah User
    </button>

</div>


{{-- ============================================================
     FILTER & PENCARIAN USER
============================================================ --}}

<div class="sa-card sa-filters">

    <div class="sa-search">
        ⌕
        <input placeholder="Cari nama/email...">
    </div>

    <select>
        <option>Semua role</option>
        <option>Superadmin</option>
        <option>KOL</option>
    </select>

    <select>
        <option>Semua status</option>
        <option>Aktif</option>
        <option>Nonaktif</option>
    </select>

    <button class="sa-btn ghost">
        Filter
    </button>

</div>


{{-- ============================================================
     TABEL DATA USER
============================================================ --}}

<div class="sa-card">

    {{-- --------------------------------------------------------
         HEADER TABEL
    --------------------------------------------------------- --}}

    <table class="sa-table">

        <thead>

            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Status</th>
                <th>Login Terakhir</th>
                <th>Dibuat</th>
                <th></th>
            </tr>

        </thead>


        {{-- --------------------------------------------------------
             DATA USER
        --------------------------------------------------------- --}}

        <tbody>


            {{-- DATA USER 1 --}}

            <tr>

                <td>
                    <strong>
                        Rina
                    </strong>

                    <small>
                        rina@majapahit.id
                    </small>
                </td>

                <td>
                    <span class="tier">
                        Superadmin
                    </span>
                </td>

                <td>
                    <span class="status success">
                        Aktif
                    </span>
                </td>

                <td>
                    7 Sep 2026 08:55
                </td>

                <td>
                    1 Jan 2026
                </td>

                <td>
                    •••
                </td>

            </tr>


            {{-- DATA USER 2 --}}

            <tr>

                <td>
                    <strong>
                        Dimas Pratama
                    </strong>

                    <small>
                        dimas@example.com
                    </small>
                </td>

                <td>
                    <span class="tier">
                        KOL
                    </span>
                </td>

                <td>
                    <span class="status success">
                        Aktif
                    </span>
                </td>

                <td>
                    6 Sep 2026 21:03
                </td>

                <td>
                    2 Sep 2026
                </td>

                <td>
                    •••
                </td>

            </tr>

        </tbody>

    </table>

</div>


{{-- ============================================================
     MODAL TAMBAH USER
============================================================ --}}
<div
    class="sa-modal"
    id="user-form"
    aria-hidden="true"
>
    <div class="sa-modal-box user-modal">

        {{-- ====================================================
             HEADER MODAL
        ===================================================== --}}
        <div class="user-modal-header">

            <div class="user-modal-heading">
                <span class="user-modal-eyebrow">
                    USER MANAGEMENT
                </span>

                <h2 class="user-modal-title">
                    Tambah User
                </h2>

                <p class="user-modal-description">
                    Tambahkan user internal dan tentukan role serta status aksesnya.
                </p>
            </div>

            <button
                type="button"
                class="user-modal-close sa-modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>


        {{-- ====================================================
             BODY MODAL
        ===================================================== --}}
        <div class="user-modal-body">

            <form data-loading>

                {{-- ==================================================
                     SECTION INFORMASI USER
                =================================================== --}}
                <div class="user-form-section">

                    <div class="user-form-section-title">
                        Informasi User
                    </div>

                    <div class="user-form-grid">

                        {{-- NAMA --}}
                        <div class="user-form-group">

                            <label
                                for="user-name"
                                class="user-form-label"
                            >
                                Nama
                                <span class="required">*</span>
                            </label>

                            <input
                                id="user-name"
                                type="text"
                                class="user-form-input"
                                placeholder="Contoh: Rina"
                                required
                            >

                        </div>


                        {{-- EMAIL --}}
                        <div class="user-form-group">

                            <label
                                for="user-email"
                                class="user-form-label"
                            >
                                Email
                                <span class="required">*</span>
                            </label>

                            <input
                                id="user-email"
                                type="email"
                                class="user-form-input"
                                placeholder="nama@majapahit.id"
                                required
                            >

                        </div>


                        {{-- ROLE --}}
                        <div class="user-form-group">

                            <label
                                for="user-role"
                                class="user-form-label"
                            >
                                Role
                            </label>

                            <select
                                id="user-role"
                                class="user-form-select"
                            >
                                <option>Superadmin</option>
                                <option>KOL</option>
                            </select>

                        </div>


                        {{-- STATUS --}}
                        <div class="user-form-group">

                            <label
                                for="user-status"
                                class="user-form-label"
                            >
                                Status
                            </label>

                            <select
                                id="user-status"
                                class="user-form-select"
                            >
                                <option>Aktif</option>
                                <option>Nonaktif</option>
                            </select>

                        </div>

                    </div>

                    <p class="user-form-help">
                        Field bertanda <span>*</span> wajib diisi.
                    </p>

                </div>


                {{-- ==================================================
                     FOOTER / ACTION
                =================================================== --}}
                <div class="user-modal-footer">

                    <button
                        type="button"
                        class="user-btn-cancel"
                        data-modal-close="user-form"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="user-btn-save"
                    >
                        Simpan User
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

@endsection
