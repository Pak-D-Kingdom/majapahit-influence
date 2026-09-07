@extends('superadmin.layouts.app')

@section('title', 'Review Pendaftaran — ' . $registration->full_name)
@section('page-title', 'Review Pendaftaran')

@section('content')
    <div class="mb-6">
        <a href="{{ route('superadmin.registrations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Pendaftaran
        </a>

        <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="rounded-lg bg-indigo-50 px-2.5 py-1 font-mono text-xs font-bold text-indigo-700">
                        {{ $registration->registration_number }}
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-500">Mendaftar pada {{ $registration->created_at->format('d M Y, H:i') }}</span>
                </div>
                <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">{{ $registration->full_name }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $registration->email }} · {{ $registration->phone }}</p>
            </div>
            <div>
                <x-dashboard.status-badge :status="$registration->status" />
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <div class="flex items-center gap-2 font-semibold">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>Terdapat kesalahan pada formulir:</span>
            </div>
            <ul class="mt-2 list-inside list-disc text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.3fr_0.9fr]">
        {{-- Kolom Kiri: Detail Informasi & Portofolio --}}
        <div class="space-y-6">
            {{-- Card Profil & Kontak --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <h3 class="text-base font-bold text-slate-950 flex items-center gap-2">
                    <i class="bi bi-person-badge text-indigo-600"></i>
                    Informasi Profil & Media Sosial
                </h3>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 text-sm">
                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <p class="text-xs font-medium text-slate-400">Platform Utama</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            {{ str(data_get($registration->social_media, 'platform', '-'))->title() }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <p class="text-xs font-medium text-slate-400">Username / Akun</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            {{ data_get($registration->social_media, 'username', '-') }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <p class="text-xs font-medium text-slate-400">Jumlah Followers</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            {{ number_format(data_get($registration->social_media, 'followers_count', 0)) }} followers
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <p class="text-xs font-medium text-slate-400">Kota / Domisili</p>
                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $registration->city ?: 'Belum diisi' }}
                        </p>
                    </div>

                    @if (data_get($registration->social_media, 'profile_url'))
                        <div class="sm:col-span-2 rounded-xl bg-slate-50 p-3.5">
                            <p class="text-xs font-medium text-slate-400">Tautan Profil</p>
                            <a href="{{ data_get($registration->social_media, 'profile_url') }}" target="_blank" class="mt-1 inline-flex items-center gap-1 font-semibold text-indigo-600 hover:underline">
                                <span>{{ data_get($registration->social_media, 'profile_url') }}</span>
                                <i class="bi bi-box-arrow-up-right text-xs"></i>
                            </a>
                        </div>
                    @endif

                    <div class="sm:col-span-2 rounded-xl bg-slate-50 p-3.5">
                        <p class="text-xs font-medium text-slate-400">Niche / Kategori</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @forelse ((array) $registration->niches as $niche)
                                <span class="rounded-lg bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                    {{ is_array($niche) ? ($niche['name'] ?? '-') : $niche }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400">Tidak ada niche yang dipilih</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-5 space-y-4 border-t border-slate-100 pt-5">
                    <div>
                        <p class="text-xs font-medium text-slate-400">Ekspektasi Rate Card</p>
                        <p class="mt-1 text-sm font-semibold text-slate-800 whitespace-pre-line">{{ $registration->expected_rate ?: 'Tidak dicantumkan' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Alasan Bergabung</p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600 whitespace-pre-line">{{ $registration->join_reason ?: 'Tidak ada alasan yang dicantumkan' }}</p>
                    </div>
                </div>
            </div>

            {{-- Card Lampiran Portofolio --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <h3 class="text-base font-bold text-slate-950 flex items-center gap-2">
                    <i class="bi bi-paperclip text-indigo-600"></i>
                    Berkas Portofolio / Bukti Konten
                </h3>

                <div class="mt-4 space-y-2.5">
                    @forelse ($registration->files as $file)
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/60 p-3 text-sm">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                                    <i class="bi bi-file-earmark-text"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-slate-800">{{ $file->file_name }}</p>
                                    <p class="text-xs text-slate-400">{{ number_format($file->file_size / 1024, 0) }} KB</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="shrink-0 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                <i class="bi bi-download mr-1"></i>Unduh
                            </a>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 p-6 text-center text-xs text-slate-400">
                            Tidak ada berkas portofolio yang dilampirkan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Panel Keputusan Review Admin --}}
        <div>
            <div class="sticky top-28 rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <h3 class="text-base font-bold text-slate-950 flex items-center gap-2">
                    <i class="bi bi-shield-check text-indigo-600"></i>
                    Keputusan Review
                </h3>

                @if ($registration->status === 'pending_review' || $registration->status === 'pending')
                    <p class="mt-2 text-xs leading-relaxed text-slate-500">
                        Tentukan klasifikasi Tier untuk calon KOL dan buat akun kreatornya secara otomatis.
                    </p>

                    <form method="POST" action="{{ route('superadmin.registrations.review', $registration) }}" class="mt-5 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-slate-700">
                                Pilih Tier KOL <span class="text-rose-500">*</span>
                            </label>
                            <select name="tier_id" required class="mt-1.5 w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Klasifikasi Tier --</option>
                                @foreach ($tiers as $tier)
                                    <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700">
                                Catatan Review Internal
                            </label>
                            <textarea name="notes" rows="3" placeholder="Tambahkan catatan khusus untuk profil ini (opsional)..." class="mt-1.5 w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="pt-2 space-y-2.5">
                            <button type="submit" name="action" value="approve" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-emerald-700 transition">
                                <i class="bi bi-check2-circle"></i>
                                Approve & Buat Akun KOL
                            </button>

                            <button type="button" id="show-reject" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                                <i class="bi bi-x-circle"></i>
                                Tolak / Reject Pendaftaran
                            </button>
                        </div>

                        <div id="reject-box" class="hidden border-t border-slate-100 pt-4 space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700">
                                    Alasan Penolakan <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" placeholder="Tuliskan alasan penolakan pendaftaran..." class="mt-1.5 w-full rounded-xl border-rose-200 text-sm focus:border-rose-500 focus:ring-rose-500"></textarea>
                            </div>
                            <button type="submit" name="action" value="reject" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-rose-700 transition">
                                <i class="bi bi-trash"></i>
                                Konfirmasi Tolak Pendaftaran
                            </button>
                        </div>
                    </form>
                @else
                    <div class="mt-4 rounded-xl bg-slate-50 p-4 text-center">
                        <div class="mx-auto flex size-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <i class="bi bi-check-all text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm font-bold text-slate-800">Pendaftaran Selesai Diproses</p>
                        <p class="mt-1 text-xs text-slate-500">Status saat ini adalah <strong>{{ $registration->status }}</strong>. Seluruh riwayat tersimpan di Audit Trail.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('show-reject')?.addEventListener('click', function() {
        const box = document.getElementById('reject-box');
        box.classList.toggle('hidden');
        if (!box.classList.contains('hidden')) {
            document.getElementById('rejection_reason')?.focus();
        }
    });
</script>
@endpush
