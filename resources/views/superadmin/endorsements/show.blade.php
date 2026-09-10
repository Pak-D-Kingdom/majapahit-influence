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
                Brand: <strong class="text-[#421b13]">{{ $endorsement->campaign->brand->name ?? 'Partner' }}</strong> &bull;
                Kreator: <strong class="text-[#421b13]">{{ $endorsement->kolProfile->user->name ?? 'KOL' }}</strong>
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <x-dashboard.status-badge :status="$endorsement->status" />
            
            @if ($endorsement->status !== 'selesai')
                <form action="{{ route('superadmin.endorsements.complete', $endorsement->id) }}" method="POST" onsubmit="return confirm('Tandai endorsement ini sebagai selesai? Komisi akan dicatat ke saldo KOL.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 font-heading">
                        <i class="bi bi-check2-all"></i>
                        <span>Selesaikan Endorsement</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('superadmin.endorsements.edit', $endorsement) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#421b13] px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#190906] font-heading">
                <i class="bi bi-pencil"></i>
                <span>Edit Endorsement</span>
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-bold text-emerald-800 flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-base text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-xs font-bold text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                    <dd class="mt-1 text-sm font-bold text-[#421b13] font-heading">{{ $endorsement->kolProfile->user->name ?? '-' }}</dd>
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
                    <dd class="mt-1 text-sm font-semibold {{ $endorsement->deadline && $endorsement->deadline->isPast() && $endorsement->status !== 'selesai' ? 'text-red-600' : 'text-[#421b13]' }}">
                        {{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
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
                        {{ $endorsement->campaign->content_requirements ?: ($endorsement->campaign->description ?: 'Tidak ada brief khusus.') }}
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
                            <span class="text-[11px] text-[#765f58]">{{ $proof->created_at ? $proof->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>

                        @if ($proof->post_url)
                            <div class="mt-3">
                                <a href="{{ $proof->post_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#d57028] to-[#d5282d] px-3.5 py-2.5 text-xs font-bold text-white shadow-xs hover:brightness-105 transition font-heading w-full justify-center">
                                    <i class="bi bi-box-arrow-up-right text-xs"></i>
                                    <span>Buka Postingan Konten (Live URL)</span>
                                </a>
                                <p class="mt-1.5 text-[11px] text-[#765f58] truncate break-all px-1">
                                    <i class="bi bi-link-45deg"></i> {{ $proof->post_url }}
                                </p>
                            </div>
                        @endif

                        {{-- Screenshot / File Bukti --}}
                        @if ($proof->files && $proof->files->isNotEmpty())
                            <div class="mt-3 space-y-2">
                                <span class="text-[11px] font-bold text-[#421b13] uppercase tracking-wider block font-heading">
                                    <i class="bi bi-images mr-1 text-[#d57028]"></i> File Bukti & Screenshot ({{ $proof->files->count() }})
                                </span>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($proof->files as $file)
                                        @php
                                            $isImage = in_array(strtolower(pathinfo($file->file_name ?? $file->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                            $fileUrl = asset('storage/' . $file->file_path);
                                        @endphp
                                        @if ($isImage)
                                            <a href="{{ $fileUrl }}" target="_blank" class="group relative block overflow-hidden rounded-xl border border-[#421b13]/10 bg-white shadow-xs hover:border-[#d57028] transition">
                                                <img src="{{ $fileUrl }}" alt="{{ $file->file_name ?? 'Bukti Konten' }}" class="h-28 w-full object-cover group-hover:scale-105 transition duration-200">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1">
                                                    <i class="bi bi-arrows-fullscreen"></i> Lihat Foto
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ $fileUrl }}" target="_blank" class="flex items-center gap-2 rounded-xl border border-[#421b13]/10 bg-white p-2.5 text-xs text-[#421b13] hover:border-[#d57028] hover:text-[#d57028] transition">
                                                <i class="bi bi-file-earmark-pdf text-xl text-rose-500"></i>
                                                <span class="truncate font-semibold">{{ $file->file_name ?? 'Dokumen Bukti' }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($proof->notes)
                            <div class="mt-2 rounded-lg bg-white p-2.5 text-xs text-[#765f58] border border-[#421b13]/5">
                                <strong class="text-[#421b13]">Catatan KOL:</strong> {{ $proof->notes }}
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
