@extends('superadmin.layouts.app')

@section('title', 'Review Pendaftaran | ' . $registration->full_name)
@section('page-title', 'Review Pendaftaran KOL')

@section('content')
    <div class="mb-6">
        <a href="{{ route('superadmin.registrations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown transition">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Pendaftaran
        </a>

        <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="rounded-lg bg-kerajaan-orange/10 border border-kerajaan-orange/25 px-2.5 py-1 font-mono text-xs font-bold text-kerajaan-orange">
                        {{ $registration->registration_number }}
                    </span>
                    <span class="text-xs text-kerajaan-muted/50">•</span>
                    <span class="text-xs text-kerajaan-muted">Mendaftar pada {{ $registration->created_at->format('d M Y, H:i') }}</span>
                </div>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-kerajaan-dark font-heading">{{ $registration->full_name }}</h2>
                <p class="mt-1 text-sm text-kerajaan-muted">{{ $registration->email }} · {{ $registration->phone }}</p>
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
            <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-kerajaan-dark font-heading flex items-center gap-2">
                    <i class="bi bi-person-badge text-kerajaan-orange"></i>
                    Informasi Profil & Media Sosial
                </h3>

                <div class="mt-5 space-y-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Akun Media Sosial Terdaftar</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @forelse ($registration->normalized_social_media as $account)
                            <div class="rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-kerajaan-dark text-xs">
                                        <i class="bi bi-{{ match(strtolower($account['platform'] ?? '')) { 'tiktok' => 'tiktok', 'youtube' => 'youtube', 'facebook' => 'facebook', 'threads' => 'threads', 'twitter', 'x' => 'twitter-x', default => 'instagram' } }} text-kerajaan-orange"></i>
                                        <span>{{ str($account['platform'] ?? 'Platform')->title() }}</span>
                                    </span>
                                    <span class="text-[11px] font-bold text-kerajaan-muted">{{ number_format($account['followers_count'] ?? 0) }} followers</span>
                                </div>
                                <p class="text-sm font-extrabold text-kerajaan-dark truncate">{{ $account['username'] ?: '-' }}</p>
                                @if (!empty($account['profile_url']))
                                    <a href="{{ $account['profile_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-[11px] font-bold text-kerajaan-orange hover:underline">
                                        <span class="truncate max-w-[200px]">{{ $account['profile_url'] }}</span>
                                        <i class="bi bi-box-arrow-up-right text-[9px]"></i>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="sm:col-span-2 rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5 text-xs text-kerajaan-muted">
                                Tidak ada data media sosial.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2 text-sm">
                    <div class="rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Kota / Domisili</p>
                        <p class="mt-1 font-bold text-kerajaan-dark">
                            {{ $registration->city ?: 'Belum diisi' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Total Followers</p>
                        <p class="mt-1 font-bold text-kerajaan-dark">
                            {{ number_format(collect($registration->normalized_social_media)->sum('followers_count')) }} followers
                        </p>
                    </div>

                    <div class="sm:col-span-2 rounded-xl bg-kerajaan-cream border border-kerajaan-dark/6 p-3.5">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Niche / Kategori</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @forelse ((array) $registration->niches as $niche)
                                <span class="rounded-lg bg-kerajaan-sand border border-kerajaan-dark/10 px-2.5 py-1 text-xs font-bold text-kerajaan-dark">
                                    {{ is_array($niche) ? ($niche['name'] ?? '-') : $niche }}
                                </span>
                            @empty
                                <span class="text-xs text-kerajaan-muted">Tidak ada niche yang dipilih</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-5 space-y-4 border-t border-kerajaan-dark/8 pt-5">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Ekspektasi Rate Card</p>
                        <p class="mt-1 text-sm font-semibold text-kerajaan-dark whitespace-pre-line">{{ $registration->expected_rate ?: 'Tidak dicantumkan' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Alasan Bergabung</p>
                        <p class="mt-1 text-sm leading-relaxed text-kerajaan-muted whitespace-pre-line">{{ $registration->join_reason ?: 'Tidak ada alasan yang dicantumkan' }}</p>
                    </div>
                </div>
            </div>

            {{-- Card Lampiran Portofolio --}}
            <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-kerajaan-dark font-heading flex items-center gap-2">
                    <i class="bi bi-paperclip text-kerajaan-orange"></i>
                    Berkas Portofolio / Bukti Konten
                </h3>

                <div class="mt-4 space-y-2.5">
                    @forelse ($registration->files as $file)
                        <div class="flex items-center justify-between rounded-xl border border-kerajaan-dark/8 bg-kerajaan-cream p-3 text-sm">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-kerajaan-orange/10 text-kerajaan-orange">
                                    <i class="bi bi-file-earmark-text"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-kerajaan-dark font-heading text-xs">{{ $file->file_name }}</p>
                                    <p class="text-[11px] text-kerajaan-muted">{{ number_format($file->file_size / 1024, 0) }} KB</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn-kerajaan-secondary text-xs py-1 px-2.5">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-kerajaan-dark/15 p-6 text-center text-xs text-kerajaan-muted">
                            Tidak ada berkas portofolio yang dilampirkan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Panel Keputusan Review Admin --}}
        <div>
            <div class="sticky top-28 rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-kerajaan-dark font-heading flex items-center gap-2">
                    <i class="bi bi-shield-check text-kerajaan-orange"></i>
                    Keputusan Review
                </h3>

                @if ($registration->status === 'pending_review' || $registration->status === 'pending')
                    <p class="mt-2 text-xs leading-relaxed text-kerajaan-muted">
                        Tentukan klasifikasi Tier untuk calon KOL dan buat akun kreatornya secara otomatis.
                    </p>

                    <form method="POST" action="{{ route('superadmin.registrations.review', $registration) }}" class="mt-5 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                                Pilih Tier KOL <span class="text-rose-500">*</span>
                            </label>
                            <select name="tier_id" required class="mt-1.5 w-full rounded-xl border border-kerajaan-dark/15 text-xs py-2.5 px-3 text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none">
                                <option value="">-- Pilih Klasifikasi Tier --</option>
                                @foreach ($tiers as $tier)
                                    <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                                Catatan Review Internal
                            </label>
                            <textarea name="notes" rows="3" placeholder="Tambahkan catatan khusus untuk profil ini (opsional)..." class="mt-1.5 w-full rounded-xl border border-kerajaan-dark/15 text-xs py-2.5 px-3 text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none"></textarea>
                        </div>

                        <div class="pt-2 space-y-2.5">
                            <button type="submit" name="action" value="approve" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition font-heading">
                                <i class="bi bi-check2-circle"></i>
                                Approve & Buat Akun KOL
                            </button>

                            <button type="button" id="show-reject" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-kerajaan-red/30 bg-kerajaan-red/5 px-4 py-2.5 text-xs font-bold text-kerajaan-red hover:bg-kerajaan-red/15 transition font-heading">
                                <i class="bi bi-x-circle"></i>
                                Tolak / Reject Pendaftaran
                            </button>
                        </div>

                        <div id="reject-box" class="hidden border-t border-kerajaan-dark/8 pt-4 space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                                    Alasan Penolakan <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" placeholder="Tuliskan alasan penolakan pendaftaran..." class="mt-1.5 w-full rounded-xl border border-rose-200 text-xs py-2.5 px-3 text-kerajaan-dark focus:border-kerajaan-red focus:ring-2 focus:ring-kerajaan-red/20 focus:outline-none"></textarea>
                            </div>
                            <button type="submit" name="action" value="reject" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-kerajaan-red px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-kerajaan-red/90 transition font-heading">
                                <i class="bi bi-trash"></i>
                                Konfirmasi Tolak Pendaftaran
                            </button>
                        </div>
                    </form>
                @else
                    <div class="mt-4 rounded-xl bg-kerajaan-cream border border-kerajaan-dark/8 p-4 text-center">
                        <div class="mx-auto flex size-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <i class="bi bi-check-all text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm font-bold text-kerajaan-dark font-heading">Pendaftaran Selesai Diproses</p>
                        <p class="mt-1 text-xs text-kerajaan-muted">Status saat ini adalah <strong>{{ $registration->status }}</strong>. Seluruh riwayat tersimpan di Audit Trail.</p>
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
