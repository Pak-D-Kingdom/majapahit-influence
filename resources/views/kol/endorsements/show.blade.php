@extends('kol.layouts.app')

@section('title', 'Detail Tugas: ' . ($endorsement->campaign->name ?? 'Endorsement'))
@section('page_title', 'Detail Tugas Endorsement')

@section('content')
<div class="mb-4">
    <a href="{{ route('kol.endorsements.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tugas
    </a>
</div>

{{-- Alert if Content Rejected (Revision Needed) --}}
@if($endorsement->status === 'content_rejected' && $endorsement->latestContentProof)
    <div class="alert alert-danger p-4 mb-4 border-start border-danger border-4 shadow-sm" role="alert">
        <h5 class="alert-heading fw-bold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Perlu Revisi Bukti Konten!
        </h5>
        <p class="mb-2">Admin telah menolak bukti posting sebelumnya dengan catatan berikut:</p>
        <div class="p-3 bg-white rounded text-dark fw-semibold">
            {{ $endorsement->latestContentProof->review_notes ?? 'Silakan sesuaikan kembali bukti konten sesuai brief.' }}
        </div>
        <p class="mb-0 mt-2 small text-muted">Silakan unggah ulang bukti posting yang telah diperbaiki pada formulir di bawah.</p>
    </div>
@endif

<div class="row g-4">
    {{-- Left Column: Campaign Brief & Detail --}}
    <div class="col-lg-7">
        <div class="card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-light text-dark border mb-2">
                        <i class="bi bi-buildings me-1"></i>{{ $endorsement->campaign->brand->name ?? 'Brand' }}
                    </span>
                    <h4 class="fw-bold mb-1">{{ $endorsement->campaign->name ?? 'Campaign' }}</h4>
                </div>
                @if($endorsement->status === 'assigned')
                    <span class="badge bg-info-subtle text-info badge-status">Perlu Dikerjakan</span>
                @elseif($endorsement->status === 'content_submitted')
                    <span class="badge bg-warning-subtle text-warning badge-status">Sedang Ditinjau</span>
                @elseif($endorsement->status === 'content_approved')
                    <span class="badge bg-success-subtle text-success badge-status">Disetujui</span>
                @elseif($endorsement->status === 'content_rejected')
                    <span class="badge bg-danger text-white badge-status">Perlu Revisi</span>
                @elseif($endorsement->status === 'selesai')
                    <span class="badge bg-success text-white badge-status">Selesai</span>
                @endif
            </div>

            <div class="row g-3 py-3 border-top border-bottom my-2 bg-light rounded px-2">
                <div class="col-sm-4">
                    <small class="text-muted d-block">Tipe Konten</small>
                    <span class="fw-bold text-primary">{{ strtoupper(str_replace('_', ' ', $endorsement->content_type)) }}</span>
                </div>
                <div class="col-sm-4">
                    <small class="text-muted d-block">Fee Komisi</small>
                    <span class="fw-bold text-success">Rp {{ number_format($endorsement->fee, 0, ',', '.') }}</span>
                </div>
                <div class="col-sm-4">
                    <small class="text-muted d-block">Deadline Posting</small>
                    <span class="fw-bold text-danger">{{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}</span>
                </div>
            </div>

            <h6 class="fw-bold text-muted text-uppercase small mt-3 mb-2">Deskripsi & Brief:</h6>
            <p class="text-muted">{{ $endorsement->campaign->description ?? 'Tidak ada deskripsi.' }}</p>

            @if($endorsement->notes)
                <div class="p-3 bg-primary-subtle text-primary rounded mb-3">
                    <strong class="d-block mb-1"><i class="bi bi-info-circle me-1"></i> Instruksi Khusus dari Admin:</strong>
                    {{ $endorsement->notes }}
                </div>
            @endif

            @if($endorsement->campaign->content_requirements)
                <h6 class="fw-bold text-muted text-uppercase small mt-3 mb-1">Persyaratan Konten:</h6>
                <p class="small text-muted">{{ $endorsement->campaign->content_requirements }}</p>
            @endif

            @if($endorsement->campaign->dos_and_donts)
                <h6 class="fw-bold text-muted text-uppercase small mt-3 mb-1">Do's & Don'ts:</h6>
                <p class="small text-muted">{{ $endorsement->campaign->dos_and_donts }}</p>
            @endif

            @if($endorsement->campaign->files->count() > 0)
                <div class="mt-3 pt-3 border-top">
                    <h6 class="fw-bold small text-muted text-uppercase mb-2">Lampiran File Brief:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($endorsement->campaign->files as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> {{ $file->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Column: Upload Proof Form / Status --}}
    <div class="col-lg-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-upload me-2 text-primary"></i> Upload Bukti Posting
            </h5>

            @if($endorsement->status === 'selesai' || $endorsement->status === 'content_approved')
                <div class="text-center py-4">
                    <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                    <h5 class="fw-bold">Konten Telah Disetujui!</h5>
                    <p class="text-muted small">Terima kasih, tugas endorsement ini telah diverifikasi oleh agensi dan selesai.</p>
                    
                    @if($endorsement->latestContentProof)
                        <div class="p-3 bg-light rounded text-start mt-3">
                            <small class="text-muted d-block">Link Posting Terverifikasi:</small>
                            <a href="{{ $endorsement->latestContentProof->post_url }}" target="_blank" class="text-primary fw-semibold small text-break">
                                {{ $endorsement->latestContentProof->post_url }} <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <form method="POST" action="{{ route('kol.endorsements.upload', $endorsement) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL Link Posting <span class="text-danger">*</span></label>
                        <input type="url" name="post_url" class="form-control" placeholder="https://instagram.com/p/..." value="{{ old('post_url', $endorsement->latestContentProof->post_url ?? '') }}" required>
                        <small class="text-muted">Masukkan link langsung ke konten yang sudah Anda posting.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Posting <span class="text-danger">*</span></label>
                        <input type="date" name="posted_at" class="form-control" value="{{ old('posted_at', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Screenshot Bukti Konten (Maks 5 File)</label>
                        <input type="file" name="proof_files[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp,application/pdf,video/mp4">
                        <small class="text-muted">Format: JPG, PNG, PDF, atau MP4 (Maks 5MB per file).</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Story diposting jam 13:00, swipe up link terpasang">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-send-fill me-1"></i> Kirim Bukti Posting
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
