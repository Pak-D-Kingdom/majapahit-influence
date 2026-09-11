@extends('superadmin.layouts.app')

@section('title', 'Detail Endorsement | ' . $endorsement->campaign->name)
@section('page-title', 'Detail Endorsement')

@section('content')
<div class="space-y-6">
    {{-- Header & Top Actions --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('superadmin.endorsements.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Endorsement</span>
            </a>
            <h2 class="mt-3 text-2xl font-black tracking-tight text-kerajaan-dark font-heading">{{ $endorsement->campaign->name }}</h2>
            <p class="mt-1 text-xs text-kerajaan-muted">
                Brand: <strong class="text-kerajaan-dark">{{ $endorsement->campaign->brand->name ?? 'Partner' }}</strong> &bull;
                Kreator: <strong class="text-kerajaan-dark">{{ $endorsement->kolProfile->user->name ?? 'KOL' }}</strong>
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <x-dashboard.status-badge :status="$endorsement->status" />
            
            @if ($endorsement->status !== 'selesai')
                <button type="button" onclick="openCompleteEndorsementModal()" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 font-heading">
                    <i class="bi bi-check2-all"></i>
                    <span>Selesaikan Endorsement</span>
                </button>
            @endif

            <a href="{{ route('superadmin.endorsements.edit', $endorsement) }}" class="inline-flex items-center gap-2 rounded-xl bg-kerajaan-dark px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#071d49] font-heading">
                <i class="bi bi-pencil"></i>
                <span>Edit Endorsement</span>
            </a>
        </div>
    </div>

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
        <section class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs lg:col-span-2">
            <div class="border-b border-kerajaan-dark/5 pb-4">
                <h3 class="font-extrabold text-kerajaan-dark font-heading">Spesifikasi Pekerjaan</h3>
                <p class="text-xs text-kerajaan-muted">Detail penugasan dan panduan yang wajib dipatuhi kreator</p>
            </div>

            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Kreator (KOL)</dt>
                    <dd class="mt-1 text-sm font-bold text-kerajaan-dark font-heading">{{ $endorsement->kolProfile->user->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Format Konten</dt>
                    <dd class="mt-1 text-sm font-bold text-kerajaan-orange font-heading">
                        {{ str($endorsement->content_type)->replace('_', ' ')->title() }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Fee Endorsement</dt>
                    <dd class="mt-1 text-sm font-black text-kerajaan-dark">
                        Rp{{ number_format($endorsement->fee, 0, ',', '.') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Batas Deadline</dt>
                    <dd class="mt-1 text-sm font-semibold {{ $endorsement->deadline && $endorsement->deadline->isPast() && $endorsement->status !== 'selesai' ? 'text-red-600' : 'text-kerajaan-dark' }}">
                        {{ $endorsement->deadline ? $endorsement->deadline->format('d M Y') : '-' }}
                    </dd>
                </div>
                @if ($endorsement->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Catatan Tambahan</dt>
                        <dd class="mt-1 text-xs leading-relaxed text-kerajaan-muted bg-kerajaan-cream p-3 rounded-xl border border-kerajaan-dark/5">
                            {{ $endorsement->notes }}
                        </dd>
                    </div>
                @endif
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Brief & Persyaratan Campaign</dt>
                    <dd class="mt-1 whitespace-pre-line text-xs leading-relaxed text-kerajaan-dark bg-kerajaan-cream p-4 rounded-xl border border-kerajaan-dark/5">
                        {{ $endorsement->campaign->content_requirements ?: ($endorsement->campaign->description ?: 'Tidak ada brief khusus.') }}
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Content Proof & Review Panel --}}
        <section class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <div class="border-b border-kerajaan-dark/5 pb-4">
                <h3 class="font-extrabold text-kerajaan-dark font-heading">Bukti Konten (Proof)</h3>
                <p class="text-xs text-kerajaan-muted">Review link publikasi dan materi konten kreator</p>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($endorsement->contentProofs as $proof)
                    <div class="rounded-xl border border-kerajaan-dark/10 bg-kerajaan-cream p-4">
                        <div class="flex items-center justify-between">
                            <x-dashboard.status-badge :status="$proof->review_status" />
                            <span class="text-[11px] text-kerajaan-muted">{{ $proof->created_at ? $proof->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>

                        @if ($proof->post_url)
                            <div class="mt-3">
                                <a href="{{ $proof->post_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-red px-3.5 py-2.5 text-xs font-bold text-white shadow-xs hover:brightness-105 transition font-heading w-full justify-center">
                                    <i class="bi bi-box-arrow-up-right text-xs"></i>
                                    <span>Buka Postingan Konten (Live URL)</span>
                                </a>
                                <p class="mt-1.5 text-[11px] text-kerajaan-muted truncate break-all px-1">
                                    <i class="bi bi-link-45deg"></i> {{ $proof->post_url }}
                                </p>
                            </div>
                        @endif

                        {{-- Screenshot / File Bukti --}}
                        @if ($proof->files && $proof->files->isNotEmpty())
                            <div class="mt-3 space-y-2">
                                <span class="text-[11px] font-bold text-kerajaan-dark uppercase tracking-wider block font-heading">
                                    <i class="bi bi-images mr-1 text-kerajaan-orange"></i> File Bukti & Screenshot ({{ $proof->files->count() }})
                                </span>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($proof->files as $file)
                                        @php
                                            $isImage = in_array(strtolower(pathinfo($file->file_name ?? $file->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                            $fileUrl = asset('storage/' . $file->file_path);
                                        @endphp
                                        @if ($isImage)
                                            <a href="{{ $fileUrl }}" target="_blank" class="group relative block overflow-hidden rounded-xl border border-kerajaan-dark/10 bg-white shadow-xs hover:border-kerajaan-orange transition">
                                                <img src="{{ $fileUrl }}" alt="{{ $file->file_name ?? 'Bukti Konten' }}" class="h-28 w-full object-cover group-hover:scale-105 transition duration-200">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1">
                                                    <i class="bi bi-arrows-fullscreen"></i> Lihat Foto
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ $fileUrl }}" target="_blank" class="flex items-center gap-2 rounded-xl border border-kerajaan-dark/10 bg-white p-2.5 text-xs text-kerajaan-dark hover:border-kerajaan-orange hover:text-kerajaan-orange transition">
                                                <i class="bi bi-file-earmark-pdf text-xl text-rose-500"></i>
                                                <span class="truncate font-semibold">{{ $file->file_name ?? 'Dokumen Bukti' }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($proof->notes)
                            <div class="mt-2 rounded-lg bg-white p-2.5 text-xs text-kerajaan-muted border border-kerajaan-dark/5">
                                <strong class="text-kerajaan-dark">Catatan KOL:</strong> {{ $proof->notes }}
                            </div>
                        @endif

                        @if ($proof->review_notes)
                            <div class="mt-2 rounded-lg bg-white p-2.5 text-xs text-kerajaan-muted border border-kerajaan-dark/5">
                                <strong class="text-kerajaan-dark">Catatan Review:</strong> {{ $proof->review_notes }}
                            </div>
                        @endif

                        @if ($proof->review_status === 'pending')
                            <form method="POST" action="{{ route('superadmin.endorsements.proof.review', [$endorsement, $proof]) }}" class="mt-4 border-t border-kerajaan-dark/10 pt-3">
                                @csrf
                                <label class="block text-xs font-bold text-kerajaan-dark font-heading mb-1">Catatan Evaluasi</label>
                                <textarea name="review_notes" rows="2" placeholder="Tuliskan catatan jika perlu revisi..." class="w-full rounded-xl border border-kerajaan-dark/15 bg-white p-2.5 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden"></textarea>
                                <div class="mt-3 flex gap-2">
                                    <button type="submit" name="action" value="approve" class="flex-1 rounded-xl bg-emerald-600 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 font-heading">
                                        <i class="bi bi-check-lg mr-1"></i>
                                        <span>Setujui (Approve)</span>
                                    </button>
                                    <button type="submit" name="action" value="reject" class="flex-1 rounded-xl bg-kerajaan-red py-2 text-xs font-bold text-white shadow-xs transition hover:bg-[#b81d22] font-heading">
                                        <i class="bi bi-x-lg mr-1"></i>
                                        <span>Minta Revisi</span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <i class="bi bi-camera-video text-2xl text-kerajaan-muted/30"></i>
                        <p class="mt-2 text-xs text-kerajaan-muted">KOL belum mengunggah bukti tayang konten.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>

@if ($endorsement->status !== 'selesai')
    {{-- MODAL SELESAIKAN ENDORSEMENT --}}
    <div id="modal-complete-endorsement" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs transition-opacity duration-200" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-emerald-100">
            <button type="button" onclick="closeCompleteEndorsementModal()" class="absolute right-5 top-5 inline-flex size-8 items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>

            <div class="flex items-center gap-3.5 mb-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                    <i class="bi bi-check2-all text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-[#071d49] font-heading">Selesaikan Endorsement</h3>
                    <p class="text-xs text-[#64748b]">Tandai penugasan ini telah tuntas.</p>
                </div>
            </div>

            <div class="mb-4 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 p-3.5 text-xs text-emerald-900">
                <span class="text-emerald-700 block text-[11px] font-medium">Campaign:</span>
                <strong class="text-emerald-950 font-bold text-sm block mt-0.5">{{ $endorsement->campaign->name }}</strong>
                <p class="mt-2 text-xs text-emerald-800 leading-relaxed">Menyelesaikan endorsement ini akan mengunci status dan secara otomatis mencatatkan komisi ke saldo akun KOL terkait.</p>
            </div>

            <form action="{{ route('superadmin.endorsements.complete', $endorsement->id) }}" method="POST" class="space-y-4">
                @csrf

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeCompleteEndorsementModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition font-heading">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition font-heading">
                        <i class="bi bi-check2-circle"></i>
                        <span>Ya, Selesaikan Sekarang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    function openCompleteEndorsementModal() {
        const modal = document.getElementById('modal-complete-endorsement');
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeCompleteEndorsementModal() {
        const modal = document.getElementById('modal-complete-endorsement');
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal-complete-endorsement');
        modal?.addEventListener('click', function(e) {
            if (e.target === modal) closeCompleteEndorsementModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeCompleteEndorsementModal();
        });
    });
</script>
@endpush
