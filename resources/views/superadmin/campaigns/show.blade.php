@extends('superadmin.layouts.app')

@section('title', $campaign->name . ' | Detail Campaign')
@section('page-title', 'Detail Campaign')

@section('content')
<div class="space-y-6">
    {{-- Header & Actions --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('superadmin.campaigns.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Daftar Campaign</span>
            </a>
            <h2 class="mt-3 text-2xl font-black tracking-tight text-kerajaan-dark font-heading">{{ $campaign->name }}</h2>
            <p class="mt-1 text-xs text-kerajaan-muted">
                Brand: <strong class="text-kerajaan-dark">{{ $campaign->brand->name ?? 'Partner' }}</strong> &bull;
                Periode: {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} s/d {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <x-dashboard.status-badge :status="$campaign->status" />
            <a href="{{ route('superadmin.campaigns.edit', $campaign) }}" class="inline-flex items-center gap-2 rounded-xl bg-kerajaan-dark px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#190906] font-heading">
                <i class="bi bi-pencil"></i>
                <span>Edit Campaign</span>
            </a>
        </div>
    </div>

    {{-- SUCCESS ALERT BANNER --}}
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 text-xs font-bold text-emerald-800 flex items-center gap-2 shadow-xs">
            <i class="bi bi-check-circle-fill text-base text-emerald-600"></i>
            <div>
                <strong class="block text-sm">Berhasil!</strong>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- ERROR ALERT BANNER --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-kerajaan-red/20 bg-kerajaan-red/10 p-4 text-xs text-kerajaan-red space-y-1 shadow-xs">
            <div class="font-bold font-heading flex items-center gap-1.5">
                <i class="bi bi-exclamation-triangle-fill text-base"></i> Gagal memproses penugasan:
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Content Layout --}}
    <div class="grid gap-6 lg:grid-cols-[1fr_1.3fr]">
        {{-- Left: Brief Campaign --}}
        <section class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <div class="border-b border-kerajaan-dark/5 pb-4">
                <h3 class="font-extrabold text-kerajaan-dark font-heading">Brief & Persyaratan Kampanye</h3>
            </div>

            <dl class="mt-5 space-y-5 text-sm">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Alokasi Budget</dt>
                    <dd class="mt-1 text-lg font-black text-kerajaan-orange font-heading">
                        Rp{{ number_format($campaign->budget, 0, ',', '.') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Deskripsi Singkat</dt>
                    <dd class="mt-1 text-xs leading-relaxed text-kerajaan-dark">
                        {{ $campaign->description ?: 'Belum ada deskripsi khusus.' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Content Requirements</dt>
                    <dd class="mt-1 whitespace-pre-line text-xs leading-relaxed text-kerajaan-dark bg-kerajaan-cream rounded-xl p-3 border border-kerajaan-dark/5">
                        {{ $campaign->content_requirements ?: 'Belum ada panduan teknis konten.' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Do's and Don'ts</dt>
                    <dd class="mt-1 whitespace-pre-line text-xs leading-relaxed text-kerajaan-dark bg-kerajaan-cream rounded-xl p-3 border border-kerajaan-dark/5">
                        {{ $campaign->dos_and_donts ?: 'Tidak ada instruksi larangan khusus.' }}
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Right: KOL Assignment Panel --}}
        <section class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-kerajaan-dark/5 pb-4">
                <div>
                    <h3 class="font-extrabold text-kerajaan-dark font-heading">Assignment KOL</h3>
                    <p class="text-xs text-kerajaan-muted">Tugaskan kreator terdaftar ke campaign ini</p>
                </div>
                <span class="rounded-full bg-kerajaan-sand px-3 py-1 text-xs font-bold text-kerajaan-dark font-heading">
                    {{ $campaign->endorsements->count() }} Ditugaskan
                </span>
            </div>

            {{-- Assign Form --}}
            <form method="POST" action="{{ route('superadmin.campaigns.assign', $campaign) }}" class="mt-5 rounded-xl border border-kerajaan-dark/10 bg-kerajaan-cream p-4">
                @csrf
                <p class="text-xs font-bold uppercase tracking-wider text-kerajaan-orange font-heading mb-3">Tugaskan Kreator Baru</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-kerajaan-dark font-heading mb-1">Pilih KOL <span class="text-kerajaan-red">*</span></label>
                        <select name="kol_profile_id" required class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                            <option value="">-- Pilih Kreator --</option>
                            @foreach ($kols as $kol)
                                <option value="{{ $kol->id }}">{{ $kol->user->name ?? 'KOL' }} ({{ $kol->tier?->name ?: 'No Tier' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-kerajaan-dark font-heading mb-1">Tipe Konten <span class="text-kerajaan-red">*</span></label>
                        <select name="content_type" required class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                            <option value="reels">Instagram Reels</option>
                            <option value="video">TikTok Video</option>
                            <option value="feed_post">Feed Post</option>
                            <option value="story">Instagram Story</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-kerajaan-dark font-heading mb-1">Fee Endorsement (Rp) <span class="text-kerajaan-red">*</span></label>
                        <input type="number" name="fee" min="0" step="50000" required placeholder="Contoh: 500000" class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs font-bold text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-kerajaan-dark font-heading mb-1">Batas Deadline <span class="text-kerajaan-red">*</span></label>
                        <input type="date" name="deadline" required class="w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3 py-2 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                    </div>

                    <div class="sm:col-span-2 pt-1">
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-brown py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-kerajaan-brown hover:to-[#934510] font-heading flex items-center justify-center gap-1.5">
                            <i class="bi bi-person-plus mr-1"></i>
                            <span>Tugaskan KOL Sekarang</span>
                        </button>
                    </div>
                </div>
            </form>

            {{-- List of Assigned KOLs --}}
            <div class="mt-6 space-y-3">
                <p class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">KOL yang Ditugaskan</p>
                @forelse ($campaign->endorsements as $endorsement)
                    <div class="flex items-center justify-between rounded-xl border border-kerajaan-dark/8 bg-white p-3.5 shadow-xs">
                        <div class="flex items-center gap-3">
                            <div class="flex size-9 items-center justify-center rounded-lg bg-kerajaan-orange/10 text-xs font-black text-kerajaan-orange font-heading">
                                {{ str($endorsement->kolProfile->user->name ?? 'K')->substr(0, 1)->upper() }}
                            </div>
                            <div>
                                <a href="{{ route('superadmin.endorsements.show', $endorsement) }}" class="text-xs font-bold text-kerajaan-dark hover:text-kerajaan-orange font-heading">
                                    {{ $endorsement->kolProfile->user->name ?? 'KOL' }}
                                </a>
                                <p class="text-[11px] text-kerajaan-muted">
                                    {{ str($endorsement->content_type)->replace('_', ' ')->title() }} &bull; Rp{{ number_format($endorsement->fee, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-dashboard.status-badge :status="$endorsement->status" />
                            <a href="{{ route('superadmin.endorsements.show', $endorsement) }}" class="text-xs text-kerajaan-muted hover:text-kerajaan-orange inline-flex items-center gap-1 font-bold">
                                <span>Detail</span> <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-kerajaan-dark/15 py-8 text-center">
                        <i class="bi bi-person-x text-2xl text-kerajaan-muted/30"></i>
                        <p class="mt-2 text-xs text-kerajaan-muted">Belum ada KOL yang ditugaskan untuk campaign ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
