@extends('kol.layouts.app')

@section('title', 'Tugas Endorsement')
@section('page_title', 'Daftar Tugas Endorsement')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Tugas Endorsement Saya</h4>
    <p class="text-muted mb-0">Pantau jadwal posting, upload bukti konten, dan lihat status approval dari agensi.</p>
</div>

{{-- Navigation Tabs --}}
<ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm" style="width: fit-content;">
    <li class="nav-item">
        <a class="nav-link {{ ($tab ?? 'active') === 'active' || ($tab ?? '') === 'aktif' ? 'active' : '' }}" href="{{ route('kol.endorsements.index', ['tab' => 'active']) }}">
            <i class="bi bi-play-circle me-1"></i> Sedang Berjalan (Aktif)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ ($tab ?? '') === 'upcoming' || ($tab ?? '') === 'mendatang' ? 'active' : '' }}" href="{{ route('kol.endorsements.index', ['tab' => 'upcoming']) }}">
            <i class="bi bi-calendar-event me-1"></i> Jadwal Mendatang
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ ($tab ?? '') === 'history' || ($tab ?? '') === 'riwayat' ? 'active' : '' }}" href="{{ route('kol.endorsements.index', ['tab' => 'history']) }}">
            <i class="bi bi-check2-all me-1"></i> Riwayat Selesai
        </a>
    </li>
</ul>

{{-- Table Card --}}
<div class="card p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Campaign & Brand</th>
                    <th>Tipe Konten</th>
                    <th>Fee Komisi</th>
                    <th>Deadline Posting</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($endorsements as $endorsement)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-light text-dark border mb-1">
                                <i class="bi bi-buildings me-1"></i>{{ $endorsement->campaign->brand->name ?? 'Brand' }}
                            </span>
                            <div class="fw-bold fs-6">
                                <a href="{{ route('kol.endorsements.show', $endorsement) }}" class="text-decoration-none text-dark">
                                    {{ $endorsement->campaign->name ?? 'Campaign' }}
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ strtoupper(str_replace('_', ' ', $endorsement->content_type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-success">
                                Rp {{ number_format($endorsement->fee, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Fee Konten</small>
                        </td>
                        <td>
                            <div class="fw-semibold">
                                <i class="bi bi-calendar-check me-1 text-danger"></i>
                                {{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td>
                            @if($endorsement->status === 'assigned')
                                <span class="badge bg-info-subtle text-info badge-status">Perlu Dikerjakan</span>
                            @elseif($endorsement->status === 'content_submitted')
                                <span class="badge bg-warning-subtle text-warning badge-status">Menunggu Review Admin</span>
                            @elseif($endorsement->status === 'content_approved')
                                <span class="badge bg-success-subtle text-success badge-status">Bukti Disetujui</span>
                            @elseif($endorsement->status === 'content_rejected')
                                <span class="badge bg-danger text-white badge-status">Perlu Revisi</span>
                            @elseif($endorsement->status === 'selesai')
                                <span class="badge bg-success text-white badge-status">Selesai</span>
                            @else
                                <span class="badge bg-light text-dark badge-status">{{ ucfirst($endorsement->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('kol.endorsements.show', $endorsement) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i> Detail & Upload Bukti
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2 text-secondary"></i>
                            Tidak ada endorsement pada tab ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($endorsements->hasPages())
        <div class="p-3 border-top">
            {{ $endorsements->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
