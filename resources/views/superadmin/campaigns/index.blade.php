@extends('superadmin.layouts.app')

@section('title', 'Manajemen Campaign')
@section('page_title', 'Daftar Campaign')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Campaign</h4>
        <p class="text-muted mb-0">Kelola seluruh campaign promosi dan penugasan endorsement KOL.</p>
    </div>
    <a href="{{ route('superadmin.campaigns.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Buat Campaign Baru
    </a>
</div>

{{-- Filter Card --}}
<div class="card mb-4 p-3">
    <form method="GET" action="{{ route('superadmin.campaigns.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Pencarian</label>
            <input type="text" name="search" class="form-control" placeholder="Cari nama campaign atau brand..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small">Brand</label>
            <select name="brand_id" class="form-select">
                <option value="">Semua Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
    </form>
</div>

{{-- Table Card --}}
<div class="card p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Nama Campaign</th>
                    <th>Brand</th>
                    <th>Periode</th>
                    <th>Budget</th>
                    <th>KOL Assigned</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('superadmin.campaigns.show', $campaign) }}" class="fw-bold text-decoration-none text-dark">
                                {{ $campaign->name }}
                            </a>
                            <div class="small text-muted">{{ Str::limit($campaign->description, 50) }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-buildings me-1"></i>{{ $campaign->brand->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="small">
                                <i class="bi bi-calendar3 me-1"></i>{{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} s/d {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="fw-semibold">
                            Rp {{ number_format($campaign->budget, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="bi bi-people me-1"></i>{{ $campaign->endorsements_count ?? 0 }} KOL
                            </span>
                        </td>
                        <td>
                            @if($campaign->status === 'aktif')
                                <span class="badge bg-success-subtle text-success badge-status">Aktif</span>
                            @elseif($campaign->status === 'selesai')
                                <span class="badge bg-primary-subtle text-primary badge-status">Selesai</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning badge-status">Draft</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('superadmin.campaigns.show', $campaign) }}" class="btn btn-sm btn-outline-info me-1" title="Detail & Penugasan">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <a href="{{ route('superadmin.campaigns.edit', $campaign) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada campaign yang dibuat. Silakan buat campaign pertama Anda!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($campaigns->hasPages())
        <div class="p-3 border-top">
            {{ $campaigns->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
