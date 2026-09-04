@extends('superadmin.layouts.app')

@section('title', 'Buat Campaign Baru')
@section('page_title', 'Buat Campaign Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('superadmin.campaigns.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Campaign
    </a>
</div>

<div class="card p-4">
    <h5 class="fw-bold mb-3">Informasi Campaign</h5>

    <form method="POST" action="{{ route('superadmin.campaigns.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Nama Campaign <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Promo Ramadhan Big Sale 2026" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Brand / Klien <span class="text-danger">*</span></label>
                <select name="brand_id" class="form-select" required>
                    <option value="">-- Pilih Brand --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-semibold">Deskripsi / Brief Singkat <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan tujuan campaign, target audiens, dan pesan utama yang ingin disampaikan..." required>{{ old('description') }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Total Budget (Rp)</label>
                <input type="number" name="budget" class="form-control" placeholder="Contoh: 25000000" value="{{ old('budget') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Persyaratan Konten (Content Requirements)</label>
                <textarea name="content_requirements" class="form-control" rows="3" placeholder="Contoh: Format reels durasi 30-60 detik, tag @brand_id, gunakan audio viral">{{ old('content_requirements') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Do's and Don'ts</label>
                <textarea name="dos_and_donts" class="form-control" rows="3" placeholder="Contoh: Do: Tampilkan produk di 3 detik pertama. Don't: Jangan mention brand kompetitor">{{ old('dos_and_donts') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status Awal</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Lampiran File Brief (PDF / Gambar / Docx, maks 5 file)</label>
                <input type="file" name="brief_files[]" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-end">
            <a href="{{ route('superadmin.campaigns.index') }}" class="btn btn-light me-2">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i> Simpan Campaign
            </button>
        </div>
    </form>
</div>
@endsection
