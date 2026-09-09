@extends('superadmin.layouts.app')

@section('title', 'Brand')
@section('eyebrow', 'Superadmin')

@section('content')


{{-- ============================================================
     HEADER HALAMAN
============================================================ --}}

<div class="sa-page-head">

    <div>
        <h1>Manajemen Brand</h1>

        <p>
            Kelola klien, PIC, campaign aktif, dan riwayat campaign.
        </p>
    </div>

    <button
        class="sa-btn primary"
        data-modal="brand-form"
    >
        + Tambah Brand
    </button>

</div>


{{-- ============================================================
     FILTER & PENCARIAN
============================================================ --}}

<div class="sa-card sa-filters">

    <div class="sa-search">
        ⌕
        <input placeholder="Cari nama brand...">
    </div>

    <select>
        <option>Semua industri</option>
        <option>Beauty</option>
        <option>Food</option>
        <option>Technology</option>
        <option>Fashion</option>
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
     TABEL DATA BRAND
============================================================ --}}

<div class="sa-card">

    <table class="sa-table">

        {{-- --------------------------------------------------------
             HEADER TABEL
        --------------------------------------------------------- --}}

        <thead>
            <tr>
                <th>Brand</th>
                <th>Industri</th>
                <th>PIC</th>
                <th>Campaign Aktif</th>
                <th>Total Endorsement</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>


        {{-- --------------------------------------------------------
             DATA BRAND
        --------------------------------------------------------- --}}

        <tbody>

            {{-- DATA BRAND 1 --}}
            <tr>
                <td>
                    <strong>Glow Beauty</strong>
                    <small>Brand kecantikan</small>
                </td>

                <td>
                    Beauty
                </td>

                <td>
                    Andi · 0812•••
                </td>

                <td>
                    3
                </td>

                <td>
                    18
                </td>

                <td>
                    <span class="status success">
                        Aktif
                    </span>
                </td>

                <td>
                    •••
                </td>
            </tr>


            {{-- DATA BRAND 2 --}}
            <tr>
                <td>
                    <strong>Rasa Nusantara</strong>
                    <small>F&B</small>
                </td>

                <td>
                    Food
                </td>

                <td>
                    Maya · maya@•••
                </td>

                <td>
                    2
                </td>

                <td>
                    11
                </td>

                <td>
                    <span class="status success">
                        Aktif
                    </span>
                </td>

                <td>
                    •••
                </td>
            </tr>


            {{-- DATA BRAND 3 --}}
            <tr>
                <td>
                    <strong>TechOne</strong>
                    <small>Teknologi</small>
                </td>

                <td>
                    Technology
                </td>

                <td>
                    Bagas · 0813•••
                </td>

                <td>
                    1
                </td>

                <td>
                    7
                </td>

                <td>
                    <span class="status neutral">
                        Nonaktif
                    </span>
                </td>

                <td>
                    •••
                </td>
            </tr>

        </tbody>

    </table>

</div>


{{-- ============================================================
     MODAL TAMBAH BRAND
============================================================ --}}
<div class="sa-modal brand-modal" id="brand-form">
    <div class="brand-modal">

        {{-- HEADER --}}
        <div class="brand-modal-header">
            <div>
                <h2 class="brand-modal-title">Tambah Brand</h2>
                <p class="brand-modal-description">
                    Tambahkan informasi brand dan PIC untuk kebutuhan campaign.
                </p>
            </div>

            <button type="button" class="sa-modal-close">
                ×
            </button>
        </div>

        {{-- BODY --}}
        <div class="brand-modal-body">

            <form data-loading>

                {{-- DATA BRAND --}}
                <div class="form-section">
                    <div class="form-section-title">
                        Informasi Brand
                    </div>

                    <div class="form-grid">

                        <div class="form-group">
                            <label class="form-label">
                                Nama Brand <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-input"
                                placeholder="Contoh: Glow Beauty"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Industri <span class="required">*</span>
                            </label>
                            <select class="form-select" required>
                                <option value="">Pilih industri</option>
                                <option>Beauty</option>
                                <option>Food & Beverage</option>
                                <option>Technology</option>
                                <option>Fashion</option>
                                <option>Health</option>
                                <option>Lifestyle</option>
                                <option>Others</option>
                            </select>
                        </div>

                    </div>
                </div>


                {{-- DATA PIC --}}
                <div class="form-section">
                    <div class="form-section-title">
                        Informasi PIC
                    </div>

                    <div class="form-grid">

                        <div class="form-group">
                            <label class="form-label">
                                Nama PIC <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-input"
                                placeholder="Contoh: Andi Pratama"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Jabatan PIC
                            </label>
                            <input
                                type="text"
                                class="form-input"
                                placeholder="Contoh: Marketing Manager"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Email PIC
                            </label>
                            <input
                                type="email"
                                class="form-input"
                                placeholder="contoh@email.com"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Telepon PIC
                            </label>
                            <input
                                type="tel"
                                class="form-input"
                                placeholder="08xxxxxxxxxx"
                            >
                        </div>

                    </div>
                </div>


                {{-- INFORMASI TAMBAHAN --}}
                <div class="form-section">
                    <div class="form-section-title">
                        Informasi Tambahan
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            Alamat
                        </label>
                        <textarea
                            class="form-textarea"
                            placeholder="Masukkan alamat brand..."
                        ></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            Catatan
                        </label>
                        <textarea
                            class="form-textarea"
                            placeholder="Tambahkan catatan jika diperlukan..."
                        ></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">
                            Logo Brand
                        </label>

                        <input
                            type="file"
                            class="form-input"
                            accept=".jpg,.jpeg,.png,.svg"
                        >

                        <small class="form-help">
                            JPG, JPEG, PNG, atau SVG. Maksimal 2 MB.
                        </small>
                    </div>
                </div>

            </form>

        </div>

        {{-- FOOTER --}}
        <div class="kol-modal-footer">

            <button type="button" class="btn-cancel">
               Batal
            </button>

            <button type="submit" class="btn-save">
               Simpan
            </button>

         </div>

    </div>
</div>

@endsection
