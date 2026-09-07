@extends('superadmin.layouts.app')

@section('title', $campaign->name . ' | Detail Campaign')
@section('page-title', 'Detail Campaign')

@section('content')
<div class="space-y-6">
    {{-- Header & Actions --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('superadmin.campaigns.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Daftar Campaign</span>
            </a>
            <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">{{ $campaign->name }}</h2>
            <p class="mt-1 text-xs text-[#765f58]">
                Brand: <strong class="text-[#421b13]">{{ $campaign->brand->name }}</strong> &bull;
                Periode: {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} s/d {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <x-dashboard.status-badge :status="$campaign->status" />
            <a href="{{ route('superadmin.campaigns.edit', $campaign) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#421b13] px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#190906] font-heading">
                <i class="bi bi-pencil"></i>
                <span>Edit Campaign</span>
            </a>
        </div>
    </div>

    {{-- Content Layout --}}
    <div class="grid gap-6 lg:grid-cols-[1fr_1.3fr]">
        {{-- Left: Brief Campaign --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <div class="border-b border-[#421b13]/5 pb-4">
                <h3 class="font-extrabold text-[#421b13] font-heading">Brief & Persyaratan Kampanye</h3>
            </div>

            <dl class="mt-5 space-y-5 text-sm">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Alokasi Budget</dt>
                    <dd class="mt-1 text-lg font-black text-[#d57028] font-heading">
                        Rp{{ number_format($campaign->budget, 0, ',', '.') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Deskripsi Singkat</dt>
                    <dd class="mt-1 text-xs leading-relaxed text-[#421b13]">
                        {{ $campaign->description ?: 'Belum ada deskripsi khusus.' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Content Requirements</dt>
                    <dd class="mt-1 whitespace-pre-line text-xs leading-relaxed text-[#421b13] bg-[#fbf7f4] rounded-xl p-3 border border-[#421b13]/5">
                        {{ $campaign->content_requirements ?: 'Belum ada panduan teknis konten.' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Do's and Don'ts</dt>
                    <dd class="mt-1 whitespace-pre-line text-xs leading-relaxed text-[#421b13] bg-[#fbf7f4] rounded-xl p-3 border border-[#421b13]/5">
                        {{ $campaign->dos_and_donts ?: 'Tidak ada instruksi larangan khusus.' }}
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Right: KOL Assignment Panel --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-[#421b13]/5 pb-4">
                <div>
                    <h3 class="font-extrabold text-[#421b13] font-heading">Assignment KOL</h3>
                    <p class="text-xs text-[#765f58]">Tugaskan kreator terdaftar ke campaign ini</p>
                </div>
                <span class="rounded-full bg-[#f7eee8] px-3 py-1 text-xs font-bold text-[#421b13] font-heading">
                    {{ $campaign->endorsements->count() }} Ditugaskan
                </span>
            </div>

            {{-- Assign Form --}}
            <form method="POST" action="{{ route('superadmin.campaigns.assign', $campaign) }}" class="mt-5 rounded-xl border border-[#421b13]/10 bg-[#fbf7f4] p-4">
                @csrf
                <p class="text-xs font-bold uppercase tracking-wider text-[#d57028] font-heading mb-3">Tugaskan Kreator Baru</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Pilih KOL</label>
                        <select name="kol_profile_id" required class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3 py-2 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                            <option value="">-- Pilih Kreator --</option>
                            @foreach ($kols as $kol)
                                <option value="{{ $kol->id }}">{{ $kol->user->name }} ({{ $kol->tier?->name ?: 'No Tier' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Tipe Konten</label>
                        <select name="content_type" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3 py-2 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                            <option value="reels">Instagram Reels</option>
                            <option value="video">TikTok Video</option>
                            <option value="feed_post">Feed Post</option>
                            <option value="story">Instagram Story</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Fee Endorsement (Rp)</label>
                        <input type="number" name="fee" min="0" required placeholder="Contoh: 500000" class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3 py-2 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Batas Deadline</label>
                        <input type="date" name="deadline" required class="w-full rounded-xl border border-[#421b13]/15 bg-white px-3 py-2 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                    </div>

                    <div class="sm:col-span-2 pt-1">
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-[#b86021] hover:to-[#934510] font-heading">
                            <i class="bi bi-person-plus mr-1"></i>
                            <span>Tugaskan KOL Sekarang</span>
                        </button>
                    </div>
                </div>
            </form>

            {{-- List of Assigned KOLs --}}
            <div class="mt-6 space-y-3">
                <p class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">KOL yang Sedang Berjalan</p>
                @forelse ($campaign->endorsements as $endorsement)
                    <div class="flex items-center justify-between rounded-xl border border-[#421b13]/8 bg-white p-3.5 shadow-xs">
                        <div class="flex items-center gap-3">
                            <div class="flex size-9 items-center justify-center rounded-lg bg-[#d57028]/10 text-xs font-black text-[#d57028] font-heading">
                                {{ str($endorsement->kolProfile->user->name)->substr(0, 1)->upper() }}
                            </div>
                            <div>
                                <a href="{{ route('superadmin.endorsements.show', $endorsement) }}" class="text-xs font-bold text-[#421b13] hover:text-[#d57028] font-heading">
                                    {{ $endorsement->kolProfile->user->name }}
                                </a>
                                <p class="text-[11px] text-[#765f58]">
                                    {{ str($endorsement->content_type)->replace('_', ' ')->title() }} &bull; Rp{{ number_format($endorsement->fee, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-dashboard.status-badge :status="$endorsement->status" />
                            <a href="{{ route('superadmin.endorsements.show', $endorsement) }}" class="text-xs text-[#765f58] hover:text-[#d57028]">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-[#421b13]/15 py-8 text-center">
                        <i class="bi bi-person-x text-2xl text-[#765f58]/30"></i>
                        <p class="mt-2 text-xs text-[#765f58]">Belum ada KOL yang ditugaskan untuk campaign ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
