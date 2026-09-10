@extends('superadmin.layouts.app')

@section('title', 'Detail Komisi #' . $commission->id . ' | Superadmin kerajaan Influence')
@section('page-title', 'Detail Komisi')

@section('content')
    <div class="mb-6">
        <a href="{{ route('superadmin.commissions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown transition">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Komisi
        </a>

        <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-kerajaan-dark font-heading">Detail Komisi #{{ $commission->id }}</h2>
                <p class="mt-1 text-sm text-kerajaan-muted">
                    {{ $commission->kolProfile->user->name ?? 'KOL Creator' }} · {{ $commission->endorsement->campaign->name ?? 'Campaign' }}
                </p>
            </div>
            <div>
                <x-dashboard.status-badge :status="$commission->status" />
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Rincian Finansial --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-kerajaan-dark font-heading flex items-center gap-2">
                    <i class="bi bi-cash-stack text-kerajaan-orange"></i>
                    Rincian Pembayaran & Komisi
                </h3>

                <dl class="mt-5 grid gap-4 sm:grid-cols-2 text-sm">
                    <div class="rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Total Nominal Komisi</dt>
                        <dd class="mt-1 text-2xl font-extrabold text-kerajaan-orange font-heading">
                            Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Fee Total Campaign</dt>
                        <dd class="mt-1 text-lg font-bold text-kerajaan-dark font-heading">
                            Rp {{ number_format($commission->endorsement->fee ?? 0, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Nama Kreator (KOL)</dt>
                        <dd class="mt-1 font-bold text-kerajaan-dark">
                            {{ $commission->kolProfile->user->name ?? '-' }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Rekening Bank KOL</dt>
                        <dd class="mt-1 font-semibold text-kerajaan-dark">
                            {{ $commission->kolProfile->bank_name ?? 'Bank belum diisi' }} · {{ $commission->kolProfile->bank_account_number ?? '-' }} (a.n {{ $commission->kolProfile->bank_account_name ?? '-' }})
                        </dd>
                    </div>

                    <div class="sm:col-span-2 rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Campaign & Brand</dt>
                        <dd class="mt-1 font-bold text-kerajaan-dark">
                            {{ $commission->endorsement->campaign->name ?? '-' }} <span class="text-xs font-normal text-kerajaan-muted">(Brand: {{ $commission->endorsement->campaign->brand->name ?? '-' }})</span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Riwayat Persetujuan / Approval Log --}}
            <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-kerajaan-dark font-heading flex items-center gap-2">
                    <i class="bi bi-clock-history text-kerajaan-orange"></i>
                    Riwayat Persetujuan & Pencairan
                </h3>

                <div class="mt-4 space-y-3">
                    @forelse ($commission->approvals as $approval)
                        <div class="flex items-start justify-between rounded-xl border border-kerajaan-dark/8 bg-kerajaan-cream p-3.5 text-xs">
                            <div>
                                <p class="font-bold text-kerajaan-dark uppercase tracking-wider font-heading">{{ $approval->action }}</p>
                                <p class="mt-0.5 text-kerajaan-muted">Oleh: {{ $approval->performedBy->name ?? 'Sistem' }}</p>
                                @if ($approval->notes)
                                    <p class="mt-1.5 text-kerajaan-dark bg-white p-2 rounded-lg border border-kerajaan-dark/8 italic">"{{ $approval->notes }}"</p>
                                @endif
                            </div>
                            <span class="text-kerajaan-muted text-[11px]">{{ $approval->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-kerajaan-muted py-4 text-center">Belum ada riwayat approval khusus.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel Aksi Proses Pencairan --}}
        <div>
            <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-sm sticky top-28">
                <h3 class="text-base font-bold text-kerajaan-dark font-heading flex items-center gap-2">
                    <i class="bi bi-shield-check text-kerajaan-orange"></i>
                    Aksi Proses Komisi
                </h3>

                @if ($commission->status !== 'dicairkan')
                    <form method="POST" action="{{ route('superadmin.commissions.process', $commission) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-kerajaan-dark font-heading">Tanggal Transfer / Pencairan <span class="text-rose-500">*</span></label>
                            <input type="date" name="transfer_date" value="{{ date('Y-m-d') }}" required class="mt-1.5 w-full rounded-xl border border-kerajaan-dark/15 py-2 px-3 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-kerajaan-dark font-heading">Bukti Transfer (Struk/Mutasi) <span class="text-rose-500">*</span></label>
                            <input type="file" name="transfer_proof" required accept=".jpg,.jpeg,.png,.webp,.pdf" class="mt-1.5 block w-full rounded-xl border border-dashed border-kerajaan-dark/20 bg-kerajaan-cream p-2 text-xs text-kerajaan-dark">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-kerajaan-dark font-heading">Catatan Internal / Referensi</label>
                            <textarea name="notes" rows="3" placeholder="No. referensi transfer bank atau catatan khusus..." class="mt-1.5 w-full rounded-xl border border-kerajaan-dark/15 py-2 px-3 text-xs text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none"></textarea>
                        </div>

                        <button type="submit" class="btn-kerajaan-primary w-full text-xs py-3 rounded-xl font-heading">
                            <i class="bi bi-check2-circle mr-1"></i>
                            Tandai Sebagai Dicairkan (Transfer Selesai)
                        </button>
                    </form>
                @else
                    <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-center text-xs text-emerald-800 space-y-3">
                        <div>
                            <i class="bi bi-check-circle-fill text-2xl text-emerald-600 mb-1 block"></i>
                            <p class="font-bold text-sm font-heading">Komisi Telah Dicairkan</p>
                            <p class="mt-1 text-kerajaan-muted">Dana komisi ini telah sukses ditransfer ke rekening kreator pada {{ $commission->disbursed_at ? $commission->disbursed_at->format('d M Y') : '-' }}.</p>
                        </div>

                        @if ($commission->disbursement_proof_path)
                            @php
                                $proofUrl = asset('storage/' . $commission->disbursement_proof_path);
                                $isImg = in_array(strtolower(pathinfo($commission->disbursement_proof_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                            @endphp
                            <div class="pt-3 border-t border-emerald-200 text-left">
                                <p class="text-xs font-bold text-emerald-900 font-heading mb-2 flex items-center gap-1.5">
                                    <i class="bi bi-receipt text-emerald-600"></i> Bukti Transfer Tersimpan:
                                </p>
                                @if ($isImg)
                                    <a href="{{ $proofUrl }}" target="_blank" class="group relative block overflow-hidden rounded-xl border border-emerald-200 bg-white shadow-xs hover:border-emerald-500 transition">
                                        <img src="{{ $proofUrl }}" alt="Bukti Transfer Bank" class="max-h-48 w-full object-contain bg-gray-50 rounded-lg">
                                        <div class="p-2 text-center text-xs font-bold text-emerald-800 bg-white group-hover:bg-emerald-50 transition flex items-center justify-center gap-1">
                                            <i class="bi bi-arrows-fullscreen"></i> Lihat Bukti Penuh
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ $proofUrl }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-emerald-700 transition">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        <span>Unduh / Buka Dokumen Bukti</span>
                                        <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection