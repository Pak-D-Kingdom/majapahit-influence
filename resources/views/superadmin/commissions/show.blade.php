@extends('superadmin.layouts.app')

@section('title', 'Detail Komisi #' . $commission->id . ' | Superadmin Majapahit Influence')
@section('page-title', 'Detail Komisi')

@section('content')
    <div class="mb-6">
        <a href="{{ route('superadmin.commissions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] transition">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Komisi
        </a>

        <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-[#421b13] font-heading">Detail Komisi #{{ $commission->id }}</h2>
                <p class="mt-1 text-sm text-[#765f58]">
                    {{ $commission->kolProfile->user->name ?? 'KOL Creator' }} · {{ $commission->endorsement->campaign->name ?? 'Campaign' }}
                </p>
            </div>
            <div>
                <x-dashboard.status-badge :status="$commission->status" />
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 border border-emerald-200">
            <i class="bi bi-check-circle-fill text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Rincian Finansial --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-[#421b13] font-heading flex items-center gap-2">
                    <i class="bi bi-cash-stack text-[#d57028]"></i>
                    Rincian Pembayaran & Komisi
                </h3>

                <dl class="mt-5 grid gap-4 sm:grid-cols-2 text-sm">
                    <div class="rounded-xl bg-[#fbf7f4] border border-[#421b13]/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Total Nominal Komisi</dt>
                        <dd class="mt-1 text-2xl font-extrabold text-[#d57028] font-heading">
                            Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-[#fbf7f4] border border-[#421b13]/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Fee Total Campaign</dt>
                        <dd class="mt-1 text-lg font-bold text-[#421b13] font-heading">
                            Rp {{ number_format($commission->endorsement->fee ?? 0, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-[#fbf7f4] border border-[#421b13]/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Nama Kreator (KOL)</dt>
                        <dd class="mt-1 font-bold text-[#421b13]">
                            {{ $commission->kolProfile->user->name ?? '-' }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-[#fbf7f4] border border-[#421b13]/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Rekening Bank KOL</dt>
                        <dd class="mt-1 font-semibold text-[#421b13]">
                            {{ $commission->kolProfile->bank_name ?? 'Bank belum diisi' }} · {{ $commission->kolProfile->bank_account_number ?? '-' }} (a.n {{ $commission->kolProfile->bank_account_name ?? '-' }})
                        </dd>
                    </div>

                    <div class="sm:col-span-2 rounded-xl bg-[#fbf7f4] border border-[#421b13]/6 p-3.5">
                        <dt class="text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">Campaign & Brand</dt>
                        <dd class="mt-1 font-bold text-[#421b13]">
                            {{ $commission->endorsement->campaign->name ?? '-' }} <span class="text-xs font-normal text-[#765f58]">(Brand: {{ $commission->endorsement->campaign->brand->name ?? '-' }})</span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Riwayat Persetujuan / Approval Log --}}
            <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-[#421b13] font-heading flex items-center gap-2">
                    <i class="bi bi-clock-history text-[#d57028]"></i>
                    Riwayat Persetujuan & Pencairan
                </h3>

                <div class="mt-4 space-y-3">
                    @forelse ($commission->approvals as $approval)
                        <div class="flex items-start justify-between rounded-xl border border-[#421b13]/8 bg-[#fbf7f4] p-3.5 text-xs">
                            <div>
                                <p class="font-bold text-[#421b13] uppercase tracking-wider font-heading">{{ $approval->action }}</p>
                                <p class="mt-0.5 text-[#765f58]">Oleh: {{ $approval->performedBy->name ?? 'Sistem' }}</p>
                                @if ($approval->notes)
                                    <p class="mt-1.5 text-[#421b13] bg-white p-2 rounded-lg border border-[#421b13]/8 italic">"{{ $approval->notes }}"</p>
                                @endif
                            </div>
                            <span class="text-[#765f58] text-[11px]">{{ $approval->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-[#765f58] py-4 text-center">Belum ada riwayat approval khusus.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel Aksi Proses Pencairan --}}
        <div>
            <div class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-sm sticky top-28">
                <h3 class="text-base font-bold text-[#421b13] font-heading flex items-center gap-2">
                    <i class="bi bi-shield-check text-[#d57028]"></i>
                    Aksi Proses Komisi
                </h3>

                @if ($commission->status !== 'dicairkan')
                    <form method="POST" action="{{ route('superadmin.commissions.process', $commission) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-[#421b13] font-heading">Tanggal Transfer / Pencairan <span class="text-rose-500">*</span></label>
                            <input type="date" name="transfer_date" value="{{ date('Y-m-d') }}" required class="mt-1.5 w-full rounded-xl border border-[#421b13]/15 py-2 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#421b13] font-heading">Bukti Transfer (Struk/Mutasi) <span class="text-rose-500">*</span></label>
                            <input type="file" name="transfer_proof" required accept=".jpg,.jpeg,.png,.webp,.pdf" class="mt-1.5 block w-full rounded-xl border border-dashed border-[#421b13]/20 bg-[#fbf7f4] p-2 text-xs text-[#421b13]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#421b13] font-heading">Catatan Internal / Referensi</label>
                            <textarea name="notes" rows="3" placeholder="No. referensi transfer bank atau catatan khusus..." class="mt-1.5 w-full rounded-xl border border-[#421b13]/15 py-2 px-3 text-xs text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none"></textarea>
                        </div>

                        <button type="submit" class="btn-majapahit-primary w-full text-xs py-3 rounded-xl font-heading">
                            <i class="bi bi-check2-circle mr-1"></i>
                            Tandai Sebagai Dicairkan (Transfer Selesai)
                        </button>
                    </form>
                @else
                    <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-center text-xs text-emerald-800">
                        <i class="bi bi-check-circle-fill text-2xl text-emerald-600 mb-1 block"></i>
                        <p class="font-bold text-sm font-heading">Komisi Telah Dicairkan</p>
                        <p class="mt-1 text-[#765f58]">Dana komisi ini telah sukses ditransfer ke rekening kreator.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection