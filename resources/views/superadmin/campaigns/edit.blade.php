@extends('superadmin.layouts.app')

@section('title', 'Edit Campaign')
@section('page_title', 'Edit Campaign')

@section('content')
<div class="mb-4">
    <a href="{{ route('superadmin.campaigns.show', $campaign) }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Campaign
    </a>
</div>

<div class="card p-4">
    <h5 class="fw-bold mb-3">Edit Campaign: {{ $campaign->name }}</h5>

    <form method="POST" action="{{ route('superadmin.campaigns.update', $campaign) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Nama Campaign <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $campaign->name) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Brand / Klien <span class="text-danger">*</span></label>
                <select name="brand_id" class="form-select" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $campaign->brand_id) == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-semibold">Deskripsi / Brief Singkat <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $campaign->start_date ? $campaign->start_date->format('Y-m-d') : '') }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '') }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Total Budget (Rp)</label>
                <input type="number" name="budget" class="form-control" value="{{ old('budget', $campaign->budget) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Persyaratan Konten (Content Requirements)</label>
                <textarea name="content_requirements" class="form-control" rows="3">{{ old('content_requirements', $campaign->content_requirements) }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Do's and Don'ts</label>
                <textarea name="dos_and_donts" class="form-control" rows="3">{{ old('dos_and_donts', $campaign->dos_and_donts) }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ old('status', $campaign->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ old('status', $campaign->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tambah File Brief Baru</label>
                <input type="file" name="brief_files[]" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-end">
            <a href="{{ route('superadmin.campaigns.show', $campaign) }}" class="btn btn-light me-2">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i> Perbarui Campaign
            </button>
        </div>
    </form>
</div>
@endsection
