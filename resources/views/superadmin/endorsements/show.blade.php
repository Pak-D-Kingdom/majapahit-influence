@extends('superadmin.layouts.app')

@section('title', 'Detail Endorsement | ' . $endorsement->campaign->name)
@section('page-title', 'Detail Endorsement')

@section('content')
<div class="space-y-6">
    {{-- Header & Top Actions --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('superadmin.endorsements.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Endorsement</span>
            </a>
            <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">{{ $endorsement->campaign->name }}</h2>
            <p class="mt-1 text-xs text-[#765f58]">
                Brand: <strong class="text-[#421b13]">{{ $endorsement->campaign->brand->name }}</strong> &bull;
                Kreator: <strong class="text-[#421b13]">{{ $endorsement->kolProfile->user->name }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <x-dashboard.status-badge :status="$endorsement->status" />
            <a href="{{ route('superadmin.endorsements.edit', $endorsement) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#421b13] px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#190906] font-heading">
                <i class="bi bi-pencil"></i>
                <span>Edit Endorsement</span>
            </a>
        </div>
    </div>

    {{-- Content Layout --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Job Details --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs lg:col-span-2">
            <div class="border-b border-[#421b13]/5 pb-4">
                <h3 class="font-extrabold text-[#421b13] font-heading">Spesifikasi Pekerjaan</h3>
                <p class="text-xs text-[#765f58]">Detail penugasan dan panduan yang wajib dipatuhi kreator</p>
            </div>

            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Kreator (KOL)</dt>
                    <dd class="mt-1 text-sm font-bold text-[#421b13] font-heading">{{ $endorsement->kolProfile->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Format Konten</dt>
                    <dd class="mt-1 text-sm font-bold text-[#d57028] font-heading">
                        {{ str($endorsement->content_type)->replace('_', ' ')->title() }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Fee Endorsement</dt>
                    <dd class="mt-1 text-sm font-black text-[#421b13]">
                        Rp{{ number_format($endorsement->fee, 0, ',', '.') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Batas Deadline</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#421b13]">
                        {{ $endorsement->deadline->format('d M Y') }}
                    </dd>
                </div>
                @if ($endorsement->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Catatan Tambahan</dt>
                        <dd class="mt-1 text-xs leading-relaxed text-[#765f58] bg-[#fbf7f4] p-3 rounded-xl border border-[#421b13]/5">
                            {{ $endorsement->notes }}
                        </dd>
                    </div>
                @endif
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-[#765f58] font-heading">Brief & Persyaratan Campaign</dt>
                    <dd class="mt-1 whitespace-pre-line text-xs leading-relaxed text-[#421b13] bg-[#fbf7f4] p-4 rounded-xl border border-[#421b13]/5">
                        {{ $endorsement->campaign->content_requirements ?: 'Tidak ada instruksi khusus.' }}
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Content Proof & Review Panel --}}
        <section class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs">
            <div class="border-b border-[#421b13]/5 pb-4">
                <h3 class="font-extrabold text-[#421b13] font-heading">Bukti Konten (Proof)</h3>
                <p class="text-xs text-[#765f58]">Review link publikasi dan materi konten kreator</p>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($endorsement->contentProofs as $proof)
                    <div class="rounded-xl border border-[#421b13]/10 bg-[#fbf7f4] p-4">
                        <div class="flex items-center justify-between">
                            <x-dashboard.status-badge :status="$proof->review_status" />
                            <span class="text-[11px] text-[#765f58]">{{ $proof->created_at->format('d M Y H:i') }}</span>
                        </div>

                        @if ($proof->post_url)
                            <div class="mt-3">
                                <a href="{{ $proof->post_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:underline font-heading break-all">
                                    <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                    <span>{{ $proof->post_url }}</span>
                                </a>
                            </div>
                        @endif

                        @if ($proof->review_notes)
                            <div class="mt-2 rounded-lg bg-white p-2.5 text-xs text-[#765f58] border border-[#421b13]/5">
                                <strong class="text-[#421b13]">Catatan Review:</strong> {{ $proof->review_notes }}
                            </div>
                        @endif

                        @if ($proof->review_status === 'pending')
                            <form method="POST" action="{{ route('superadmin.endorsements.proof.review', [$endorsement, $proof]) }}" class="mt-4 border-t border-[#421b13]/10 pt-3">
                                @csrf
                                <label class="block text-xs font-bold text-[#421b13] font-heading mb-1">Catatan Evaluasi</label>
                                <textarea name="review_notes" rows="2" placeholder="Tuliskan catatan jika perlu revisi..." class="w-full rounded-xl border border-[#421b13]/15 bg-white p-2.5 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden"></textarea>
                                <div class="mt-3 flex gap-2">
                                    <button type="submit" name="action" value="approve" class="flex-1 rounded-xl bg-emerald-600 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 font-heading">
                                        <i class="bi bi-check-lg mr-1"></i>
                                        <span>Setujui (Approve)</span>
                                    </button>
                                    <button type="submit" name="action" value="reject" class="flex-1 rounded-xl bg-[#d5282d] py-2 text-xs font-bold text-white shadow-xs transition hover:bg-[#b81d22] font-heading">
                                        <i class="bi bi-x-lg mr-1"></i>
                                        <span>Minta Revisi</span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <i class="bi bi-camera-video text-2xl text-[#765f58]/30"></i>
                        <p class="mt-2 text-xs text-[#765f58]">KOL belum mengunggah bukti tayang konten.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
