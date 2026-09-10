@extends('kol.layouts.app')

@section('title', 'Detail Endorsement')
@section('page-title', 'Detail Endorsement')

@section('content')
    {{-- Back Link & Header --}}
    <div class="mb-6">
        <a href="{{ route('kol.endorsements.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b64d4] hover:text-[#0c3685] transition font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Endorsement</span>
        </a>

        <div class="mt-3 flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-[#0b64d4] border border-blue-200 font-heading">
                        {{ $endorsement->campaign->brand->name }}
                    </span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="text-xs font-semibold text-slate-500">
                        {{ str($endorsement->content_type)->replace('_', ' ')->title() }}
                    </span>
                </div>
                <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#071d49] font-heading">
                    {{ $endorsement->campaign->name }}
                </h2>
            </div>
            <div>
                <x-dashboard.status-badge :status="$endorsement->status" />
            </div>
        </div>
    </div>

    {{-- Action CTA Banner if proof upload is available --}}
    @if (in_array($endorsement->status, ['assigned', 'in_progress', 'content_rejected']))
        <div class="mb-6 overflow-hidden rounded-2xl border border-[#0b64d4]/30 bg-gradient-to-r from-[#0b64d4]/10 via-[#1698f6]/5 to-white p-5 shadow-xs sm:p-6">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex items-center gap-4">
                    <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0b64d4] to-[#1698f6] text-white shadow-sm shadow-[#0b64d4]/30">
                        <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-extrabold text-[#071d49] font-heading">Sudah selesai membuat atau memposting konten?</h4>
                        <p class="text-xs text-slate-500">Kirim link posting dan screenshot bukti pekerjaan untuk direview oleh tim admin.</p>
                    </div>
                </div>
                <a href="{{ route('kol.endorsements.proof.create', $endorsement) }}" class="btn-kerajaan-primary shrink-0">
                    <i class="bi bi-upload"></i>
                    <span>Upload Bukti Konten</span>
                </a>
            </div>
        </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        {{-- Left: Brief & Details --}}
        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-extrabold text-[#071d49] font-heading">Brief & Rincian Pekerjaan</h3>
                    <span class="text-xs font-semibold text-slate-400">ID #{{ $endorsement->id }}</span>
                </div>

                {{-- Key Stats Row --}}
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 font-heading">Fee Endorsement</p>
                        <p class="mt-1 text-2xl font-extrabold text-emerald-600 font-heading">
                            Rp {{ number_format($endorsement->fee, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400">Sebelum perhitungan bagi hasil komisi</p>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 font-heading">Tenggat Waktu (Deadline)</p>
                        <p class="mt-1 text-base font-extrabold text-[#071d49] font-heading">
                            {{ $endorsement->deadline->format('d M Y') }}
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400">
                            Mulai: {{ $endorsement->start_date ? $endorsement->start_date->format('d M Y') : 'Langsung' }}
                        </p>
                    </div>
                </div>

                {{-- Detailed Guidelines --}}
                <div class="mt-6 space-y-5">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Deskripsi Campaign</h4>
                        <div class="mt-2 rounded-xl border border-slate-100 bg-slate-50/50 p-4 text-xs leading-relaxed text-slate-700">
                            {{ $endorsement->campaign->description ?: 'Tidak ada deskripsi khusus.' }}
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Ketentuan Konten (Requirements)</h4>
                        <div class="mt-2 whitespace-pre-line rounded-xl border border-slate-100 bg-slate-50/50 p-4 text-xs leading-relaxed text-slate-700">
                            {{ $endorsement->campaign->content_requirements ?: 'Ikuti arahan konten standar.' }}
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Do's & Don'ts</h4>
                        <div class="mt-2 whitespace-pre-line rounded-xl border border-slate-100 bg-slate-50/50 p-4 text-xs leading-relaxed text-slate-700">
                            {{ $endorsement->campaign->dos_and_donts ?: 'Tidak ada instruksi do & donts khusus.' }}
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Right: Content Proofs List --}}
        <div>
            <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-extrabold text-[#071d49] font-heading">Bukti Konten (Proof)</h3>
                    <span class="text-xs font-semibold text-slate-400">{{ $endorsement->contentProofs->count() }} Terkirim</span>
                </div>

                @forelse ($endorsement->contentProofs as $proof)
                    <div class="mt-4 rounded-xl border border-slate-200/70 bg-slate-50/60 p-4 transition hover:bg-white hover:border-[#0b64d4]/30">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-[#071d49] font-heading">
                                Dikirim: {{ $proof->created_at->format('d M Y, H:i') }}
                            </span>
                            <x-dashboard.status-badge :status="$proof->review_status" />
                        </div>

                        @if ($proof->posted_at)
                            <p class="mt-2 text-xs text-slate-500">
                                <i class="bi bi-calendar-check mr-1 text-[#0b64d4]"></i>
                                Tanggal tayang: {{ $proof->posted_at->format('d M Y') }}
                            </p>
                        @endif

                        @if ($proof->post_url)
                            <div class="mt-2">
                                <a href="{{ $proof->post_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 truncate text-xs font-bold text-[#0b64d4] hover:text-[#0c3685] transition">
                                    <i class="bi bi-link-45deg text-base"></i>
                                    <span class="truncate">{{ $proof->post_url }}</span>
                                    <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                </a>
                            </div>
                        @endif

                        @if ($proof->notes)
                            <div class="mt-2.5 rounded-lg bg-white p-2.5 text-xs text-slate-600 border border-slate-100">
                                <span class="font-bold text-[#071d49]">Catatanmu:</span> {{ $proof->notes }}
                            </div>
                        @endif

                        @if ($proof->review_notes)
                            <div class="mt-2.5 rounded-lg bg-rose-50 p-2.5 text-xs text-rose-700 border border-rose-200">
                                <span class="font-bold">Feedback Tim Admin:</span> {{ $proof->review_notes }}
                            </div>
                        @endif

                        {{-- Files list --}}
                        @if ($proof->files->isNotEmpty())
                            <div class="mt-3 border-t border-slate-100 pt-2.5">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-heading mb-1.5">File Bukti Terlampir:</p>
                                <div class="space-y-1">
                                    @foreach ($proof->files as $file)
                                        <div class="flex items-center justify-between text-xs text-slate-600">
                                            <span class="truncate flex items-center gap-1.5">
                                                <i class="bi bi-file-earmark-check text-[#0b64d4]"></i>
                                                {{ $file->file_name }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">({{ number_format($file->file_size / 1024, 0) }} KB)</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-500">
                        <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-slate-50 text-[#0b64d4]">
                            <i class="bi bi-cloud-upload text-xl"></i>
                        </div>
                        <p class="mt-3 text-xs font-semibold text-[#071d49]">Belum ada bukti konten diunggah</p>
                        <p class="mt-1 text-[11px] text-slate-400">Unggah bukti postingan begitu konten Anda sudah tayang.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </div>
@endsection
