@extends('superadmin.layouts.app')

@section('title', 'Detail Komisi')
@section('page-title', 'Detail Komisi')

@section('content')
    <div class="mb-6">
        <a href="{{ route('superadmin.commissions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Komisi
        </a>

        <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-950">Detail Komisi #{{ $commission->id }}</h2>
                <p class="mt-1 text-sm text-slate-500">
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
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <h3 class="text-base font-bold text-slate-950 flex items-center gap-2">
                    <i class="bi bi-cash-stack text-indigo-600"></i>
                    Rincian Pembayaran & Komisi
                </h3>

                <dl class="mt-5 grid gap-4 sm:grid-cols-2 text-sm">
                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <dt class="text-xs font-medium text-slate-400">Total Nominal Komisi</dt>
                        <dd class="mt-1 text-xl font-bold text-slate-900">
                            Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <dt class="text-xs font-medium text-slate-400">Fee Total Campaign</dt>
                        <dd class="mt-1 text-lg font-semibold text-slate-800">
                            Rp {{ number_format($commission->endorsement->fee ?? 0, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <dt class="text-xs font-medium text-slate-400">Nama Kreator (KOL)</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $commission->kolProfile->user->name ?? '-' }}
                        </dd>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <dt class="text-xs font-medium text-slate-400">Rekening Bank KOL</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $commission->kolProfile->bank_name ?? 'Bank belum diisi' }} - {{ $commission->kolProfile->bank_account_number ?? '-' }} (a.n {{ $commission->kolProfile->bank_account_name ?? '-' }})
                        </dd>
                    </div>

                    <div class="sm:col-span-2 rounded-xl bg-slate-50 p-3.5">
                        <dt class="text-xs font-medium text-slate-400">Campaign & Brand</dt>
                        <dd class="mt-1 font-semibold text-slate-800">
                            {{ $commission->endorsement->campaign->name ?? '-' }} (Brand: {{ $commission->endorsement->campaign->brand->name ?? '-' }})
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Riwayat Persetujuan / Approval Log --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <h3 class="text-base font-bold text-slate-950 flex items-center gap-2">
                    <i class="bi bi-clock-history text-indigo-600"></i>
                    Riwayat Persetujuan & Pencairan
                </h3>

                <div class="mt-4 space-y-3">
                    @forelse ($commission->approvals as $approval)
                        <div class="flex items-start justify-between rounded-xl border border-slate-100 bg-slate-50/70 p-3.5 text-xs">
                            <div>
                                <p class="font-bold text-slate-800 uppercase tracking-wide">{{ $approval->action }}</p>
                                <p class="mt-0.5 text-slate-500">Oleh: {{ $approval->performedBy->name ?? 'Sistem' }}</p>
                                @if ($approval->notes)
                                    <p class="mt-1 text-slate-600 bg-white p-2 rounded-lg border border-slate-200/60">{{ $approval->notes }}</p>
                                @endif
                            </div>
                            <span class="text-slate-400">{{ $approval->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada riwayat approval khusus.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel Aksi Proses Pencairan --}}
        <div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <h3 class="text-base font-bold text-slate-950 flex items-center gap-2">
                    <i class="bi bi-shield-check text-indigo-600"></i>
                    Aksi Proses Komisi
                </h3>

                @if ($commission->status !== 'dicairkan')
                    <form method="POST" action="{{ route('superadmin.commissions.process', $commission) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700">Tanggal Transfer / Pencairan <span class="text-rose-500">*</span></label>
                            <input type="date" name="transfer_date" value="{{ date('Y-m-d') }}" required class="mt-1.5 w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700">Bukti Transfer (Struk/Mutasi) <span class="text-rose-500">*</span></label>
                            <input type="file" name="transfer_proof" required accept=".jpg,.jpeg,.png,.webp,.pdf" class="mt-1.5 block w-full rounded-xl border border-dashed border-slate-300 p-2 text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700">Catatan Internal / Referensi</label>
                            <textarea name="notes" rows="3" placeholder="No. referensi transfer bank atau catatan khusus..." class="mt-1.5 w-full rounded-xl border-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-slate-950 px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-slate-800 transition">
                            <i class="bi bi-check2-circle mr-1"></i>
                            Tandai Sebagai Dicairkan (Transfer Selesai)
                        </button>
                    </form>
                @else
                    <div class="mt-4 rounded-xl bg-emerald-50 p-4 text-center text-xs text-emerald-800">
                        <i class="bi bi-check-circle-fill text-2xl text-emerald-600 mb-1 block"></i>
                        <p class="font-bold text-sm">Komisi Telah Dicairkan</p>
                        <p class="mt-1 text-slate-500">Dana komisi ini telah sukses ditransfer ke rekening kreator.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection