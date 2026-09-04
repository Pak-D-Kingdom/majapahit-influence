@extends('superadmin.layouts.app')

@section('title', 'Detail Campaign: ' . $campaign->name)
@section('page_title', 'Detail Campaign & Endorsements')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('superadmin.campaigns.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Campaign
    </a>
    <div>
        <a href="{{ route('superadmin.campaigns.edit', $campaign) }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-pencil me-1"></i> Edit Campaign
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignKolModal">
            <i class="bi bi-person-plus-fill me-1"></i> Tugaskan (Assign) KOL
        </button>
    </div>
</div>

{{-- Header Info Card --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="badge bg-light text-dark border mb-2">
                        <i class="bi bi-buildings me-1"></i>{{ $campaign->brand->name ?? '-' }}
                    </span>
                    <h4 class="fw-bold mb-1">{{ $campaign->name }}</h4>
                </div>
                @if($campaign->status === 'aktif')
                    <span class="badge bg-success-subtle text-success badge-status fs-6">Aktif</span>
                @elseif($campaign->status === 'selesai')
                    <span class="badge bg-primary-subtle text-primary badge-status fs-6">Selesai</span>
                @else
                    <span class="badge bg-warning-subtle text-warning badge-status fs-6">Draft</span>
                @endif
            </div>

            <p class="text-muted">{{ $campaign->description }}</p>

            <div class="row g-3 mt-2 pt-2 border-top">
                <div class="col-sm-4">
                    <small class="text-muted d-block">Periode</small>
                    <span class="fw-semibold">
                        {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} - {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
                    </span>
                </div>
                <div class="col-sm-4">
                    <small class="text-muted d-block">Total Budget</small>
                    <span class="fw-bold text-success">
                        Rp {{ number_format($campaign->budget, 0, ',', '.') }}
                    </span>
                </div>
                <div class="col-sm-4">
                    <small class="text-muted d-block">Dibuat Oleh</small>
                    <span class="fw-semibold">{{ $campaign->creator->name ?? 'Admin' }}</span>
                </div>
            </div>

            @if($campaign->content_requirements || $campaign->dos_and_donts)
                <div class="row g-3 mt-3 pt-3 border-top">
                    @if($campaign->content_requirements)
                        <div class="col-md-6">
                            <h6 class="fw-bold small text-muted text-uppercase">Persyaratan Konten:</h6>
                            <p class="small mb-0">{{ $campaign->content_requirements }}</p>
                        </div>
                    @endif
                    @if($campaign->dos_and_donts)
                        <div class="col-md-6">
                            <h6 class="fw-bold small text-muted text-uppercase">Do's & Don'ts:</h6>
                            <p class="small mb-0">{{ $campaign->dos_and_donts }}</p>
                        </div>
                    @endif
                </div>
            @endif

            @if($campaign->files->count() > 0)
                <div class="mt-3 pt-3 border-top">
                    <h6 class="fw-bold small text-muted text-uppercase mb-2">Lampiran Brief:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($campaign->files as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> {{ $file->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Metrics Summary --}}
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h6 class="fw-bold text-muted text-uppercase small mb-3">Progress Endorsement</h6>
            
            <div class="d-flex align-items-baseline mb-2">
                <h2 class="fw-bold mb-0 text-primary">{{ $completedEndorsements }}</h2>
                <span class="fs-5 text-muted ms-2">/ {{ $totalEndorsements }} Selesai</span>
            </div>

            <div class="progress mb-3" style="height: 10px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPct }}%;"></div>
            </div>
            <p class="small text-muted mb-4">{{ $progressPct }}% penugasan endorsement telah selesai.</p>

            <div class="border-top pt-3">
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#assignKolModal">
                    <i class="bi bi-person-plus-fill me-1"></i> Tugaskan KOL Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

{{-- List Assigned KOL Endorsements --}}
<div class="card p-0">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Daftar Penugasan KOL ({{ $campaign->endorsements->count() }})</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">KOL / Influencer</th>
                    <th>Tipe Konten</th>
                    <th>Fee Brand</th>
                    <th>Deadline</th>
                    <th>Status Lifecycle</th>
                    <th>Bukti Konten</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaign->endorsements as $endorsement)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                    {{ substr($endorsement->kolProfile->nickname ?? ($endorsement->kolProfile->user->name ?? 'K'), 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $endorsement->kolProfile->nickname ?? ($endorsement->kolProfile->user->name ?? '-') }}</div>
                                    <span class="badge bg-light text-secondary border small">
                                        Tier: {{ $endorsement->kolProfile->tier->name ?? 'Nano' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark">
                                {{ strtoupper(str_replace('_', ' ', $endorsement->content_type)) }}
                            </span>
                        </td>
                        <td class="fw-semibold">
                            Rp {{ number_format($endorsement->fee, 0, ',', '.') }}
                        </td>
                        <td>
                            <div class="small">
                                <i class="bi bi-calendar-event me-1"></i>{{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td>
                            @if($endorsement->status === 'assigned')
                                <span class="badge bg-info-subtle text-info badge-status">Ditugaskan</span>
                            @elseif($endorsement->status === 'content_submitted')
                                <span class="badge bg-warning-subtle text-warning badge-status">Bukti Diunggah</span>
                            @elseif($endorsement->status === 'content_approved')
                                <span class="badge bg-success-subtle text-success badge-status">Bukti Disetujui</span>
                            @elseif($endorsement->status === 'content_rejected')
                                <span class="badge bg-danger-subtle text-danger badge-status">Revisi Diperlukan</span>
                            @elseif($endorsement->status === 'selesai')
                                <span class="badge bg-success text-white badge-status">Selesai</span>
                            @else
                                <span class="badge bg-light text-dark badge-status">{{ ucfirst($endorsement->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($endorsement->latestContentProof)
                                <a href="{{ $endorsement->latestContentProof->post_url }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Link Post
                                </a>
                                @if($endorsement->latestContentProof->files->count() > 0)
                                    <span class="badge bg-light text-secondary border">
                                        <i class="bi bi-image me-1"></i>{{ $endorsement->latestContentProof->files->count() }} File
                                    </span>
                                @endif
                            @else
                                <span class="text-muted small">Belum submit</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            {{-- Review Proof Action --}}
                            @if($endorsement->status === 'content_submitted' && $endorsement->latestContentProof)
                                <button type="button" class="btn btn-sm btn-warning text-dark me-1" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $endorsement->id }}">
                                    <i class="bi bi-patch-check"></i> Review Bukti
                                </button>
                            @endif

                            {{-- Complete Action --}}
                            @if($endorsement->status === 'content_approved')
                                <form method="POST" action="{{ route('superadmin.endorsements.complete', $endorsement) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success me-1" onclick="return confirm('Tandai endorsement ini sebagai selesai? Komisi akan dicatat otomatis.')">
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </button>
                                </form>
                            @endif

                            {{-- Cancel Action --}}
                            @if($endorsement->status !== 'selesai')
                                <form method="POST" action="{{ route('superadmin.endorsements.destroy', $endorsement) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Batalkan penugasan KOL ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    {{-- Review Proof Modal --}}
                    @if($endorsement->latestContentProof)
                        <div class="modal fade" id="reviewModal{{ $endorsement->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('superadmin.endorsements.review', $endorsement) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Review Bukti Konten</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">KOL</label>
                                                <div class="fw-bold">{{ $endorsement->kolProfile->nickname }}</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">URL Link Posting</label>
                                                <div>
                                                    <a href="{{ $endorsement->latestContentProof->post_url }}" target="_blank" class="text-primary text-decoration-none fw-semibold">
                                                        {{ $endorsement->latestContentProof->post_url }} <i class="bi bi-box-arrow-up-right small"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @if($endorsement->latestContentProof->notes)
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted">Catatan KOL</label>
                                                    <div class="p-2 bg-light rounded small">{{ $endorsement->latestContentProof->notes }}</div>
                                                </div>
                                            @endif
                                            @if($endorsement->latestContentProof->files->count() > 0)
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted">File Screenshot / Lampiran</label>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($endorsement->latestContentProof->files as $file)
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-eye me-1"></i> {{ $file->file_name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <hr>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Keputusan Review <span class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="approved">Setujui Bukti (Approved)</option>
                                                    <option value="rejected">Tolak / Minta Revisi (Rejected)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Catatan Review / Revisi</label>
                                                <textarea name="notes" class="form-control" rows="3" placeholder="Wajib diisi jika menolak/minta revisi..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Kirim Keputusan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada KOL yang ditugaskan ke campaign ini. Klik tombol <strong>Tugaskan KOL</strong> di atas untuk memulai!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Assign KOL Modal --}}
<div class="modal fade" id="assignKolModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('superadmin.campaigns.assign', $campaign) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tugaskan (Assign) KOL ke Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih KOL Aktif <span class="text-danger">*</span></label>
                        <select name="kol_profile_id" class="form-select" required>
                            <option value="">-- Pilih KOL --</option>
                            @foreach($availableKols as $kol)
                                <option value="{{ $kol->id }}">
                                    {{ $kol->nickname ?? ($kol->user->name ?? 'KOL #' . $kol->id) }} ({{ $kol->tier->name ?? 'Nano' }} - {{ $kol->city ?? 'Indonesia' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya KOL dengan status <strong>Aktif</strong> yang dapat ditugaskan (BR2 & BR3).</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe Konten <span class="text-danger">*</span></label>
                        <select name="content_type" class="form-select" required>
                            <option value="reels">Instagram Reels</option>
                            <option value="feed_post">Instagram Feed Post</option>
                            <option value="story">Instagram Story</option>
                            <option value="tiktok_video">TikTok Video</option>
                            <option value="youtube_video">YouTube Video</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fee Endorsement (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="fee" class="form-control" placeholder="Contoh: 5000000" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deadline Posting <span class="text-danger">*</span></label>
                        <input type="date" name="deadline" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Khusus untuk KOL</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Tag akun @brand, upload antara jam 12-14 siang"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Tugaskan Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
