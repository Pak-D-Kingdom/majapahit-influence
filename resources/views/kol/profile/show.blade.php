@extends('kol.layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
    {{-- Top Action / Breadcrumbs Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#d57028] font-heading">Informasi Akun</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#421b13] font-heading">Profil Saya</h2>
            <p class="mt-1 text-sm text-[#765f58]">Kelola dan tinjau data profil, performa sosial media, rate card, dan rekening perbankan Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('kol.profile.edit') }}" class="btn-majapahit-secondary">
                <i class="bi bi-pencil-square text-[#d57028]"></i>
                <span>Edit Profil & Rate Card</span>
            </a>
        </div>
    </div>

    {{-- Hero Profile Card --}}
    <div class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-[#421b13] via-[#31140d] to-[#240e09] p-6 text-white shadow-xl shadow-[#421b13]/10 sm:p-8">
        {{-- Decorative glowing orbs --}}
        <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full bg-[#d57028]/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 right-32 size-48 rounded-full bg-[#d5282d]/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                {{-- Avatar --}}
                <div class="relative size-24 shrink-0 overflow-hidden rounded-2xl border-2 border-white/20 bg-gradient-to-br from-[#d57028] to-[#d5282d] shadow-lg shadow-[#d57028]/30 sm:size-28">
                    @if ($profile->photo_path)
                        <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->nickname ?: $profile->user->name }}" class="size-full object-cover">
                    @else
                        <div class="flex size-full items-center justify-center font-heading text-3xl font-extrabold text-white">
                            {{ str($profile->nickname ?: $profile->user->name)->substr(0, 2)->upper() }}
                        </div>
                    @endif
                    <span class="absolute bottom-1 right-1 size-4 rounded-full border-2 border-[#240e09] {{ $profile->status === 'aktif' ? 'bg-emerald-400' : 'bg-amber-400' }}" title="Status: {{ ucfirst($profile->status) }}"></span>
                </div>

                {{-- Name, Bio, and Tier --}}
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h3 class="text-2xl font-extrabold tracking-tight text-white font-heading">
                            {{ $profile->nickname ?: $profile->user->name }}
                        </h3>
                        @if ($profile->tier)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-[#fec200]/30 bg-[#fec200]/10 px-3 py-0.5 text-xs font-bold text-[#fec200] backdrop-blur-xs font-heading">
                                <i class="bi bi-award-fill"></i>
                                Tier {{ $profile->tier->name }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1 rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-xs font-semibold text-white/80 backdrop-blur-xs">
                            Komisi {{ $profile->effective_commission_pct }}%
                        </span>
                    </div>

                    @if ($profile->nickname && $profile->nickname !== $profile->user->name)
                        <p class="mt-1 text-xs text-white/60 font-body">Nama Lengkap: {{ $profile->user->name }}</p>
                    @endif

                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-white/80">
                        {{ $profile->bio ?: 'Belum ada bio. Tambahkan deskripsi diri Anda untuk menarik kolaborasi brand.' }}
                    </p>

                    {{-- Niches --}}
                    @if ($profile->niches && $profile->niches->isNotEmpty())
                        <div class="mt-3.5 flex flex-wrap items-center gap-1.5">
                            <span class="text-xs text-white/50 mr-1"><i class="bi bi-tags"></i> Niche:</span>
                            @foreach ($profile->niches as $niche)
                                <span class="rounded-lg bg-white/10 px-2.5 py-1 text-xs font-medium text-[#fec200] backdrop-blur-xs border border-white/5">
                                    #{{ $niche->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status & Meta Badge --}}
            <div class="flex flex-wrap items-center gap-3 lg:flex-col lg:items-end">
                <x-dashboard.status-badge :status="$profile->status" />
                <span class="text-xs text-white/60">
                    <i class="bi bi-calendar3 mr-1"></i> Bergabung {{ $profile->joined_at ? $profile->joined_at->translatedFormat('d M Y') : $profile->created_at->translatedFormat('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <x-dashboard.stat-card
            label="Endorsement Selesai"
            :value="number_format($stats['completedEndorsements'])"
            icon="bi-check2-all"
            hint="Total campaign sukses"
            accent="emerald"
        />
        <x-dashboard.stat-card
            label="Komisi Bulan Ini"
            :value="'Rp ' . number_format($stats['monthCommission'], 0, ',', '.')"
            icon="bi-calendar-event"
            hint="Total komisi bulan berjalan"
            accent="orange"
        />
        <x-dashboard.stat-card
            label="Total Komisi Diterima"
            :value="'Rp ' . number_format($stats['totalCommission'], 0, ',', '.')"
            icon="bi-wallet2"
            hint="Komisi telah dicairkan"
            accent="amber"
        />
    </div>

    {{-- Main Content Grid --}}
    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        {{-- Left Column: Personal Info & Bank Info --}}
        <div class="space-y-8">
            {{-- Personal Information Card --}}
            <div class="majapahit-card overflow-hidden">
                <div class="border-b border-[#421b13]/8 bg-[#fff9f4]/60 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-[#d57028]/15 text-[#d57028]">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#421b13] font-heading">Informasi Pribadi & Kontak</h3>
                            <p class="text-xs text-[#765f58]">Data identitas pribadi dan domisili</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Alamat Email</dt>
                            <dd class="mt-1 text-sm font-medium text-[#421b13] flex items-center gap-1.5">
                                <i class="bi bi-envelope text-[#d57028]"></i>
                                {{ $profile->user->email }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Jenis Kelamin</dt>
                            <dd class="mt-1 text-sm font-medium text-[#421b13]">
                                @if ($profile->gender === 'laki-laki' || $profile->gender === 'male')
                                    <span class="inline-flex items-center gap-1.5"><i class="bi bi-gender-male text-blue-500"></i> Laki-laki</span>
                                @elseif ($profile->gender === 'perempuan' || $profile->gender === 'female')
                                    <span class="inline-flex items-center gap-1.5"><i class="bi bi-gender-female text-pink-500"></i> Perempuan</span>
                                @else
                                    {{ $profile->gender ? ucfirst($profile->gender) : '-' }}
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Tanggal Lahir</dt>
                            <dd class="mt-1 text-sm font-medium text-[#421b13] flex items-center gap-1.5">
                                <i class="bi bi-calendar-heart text-[#d57028]"></i>
                                {{ $profile->date_of_birth ? $profile->date_of_birth->translatedFormat('d F Y') : '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Domisili (Kota / Provinsi)</dt>
                            <dd class="mt-1 text-sm font-medium text-[#421b13] flex items-center gap-1.5">
                                <i class="bi bi-geo-alt-fill text-[#d5282d]"></i>
                                {{ $profile->city ? $profile->city . ($profile->province ? ', ' . $profile->province : '') : '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Status Keanggotaan</dt>
                            <dd class="mt-1">
                                <x-dashboard.status-badge :status="$profile->status" />
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Tier & Skema Komisi</dt>
                            <dd class="mt-1 text-sm font-bold text-[#b86021] font-heading">
                                {{ $profile->tier?->name ?? 'Nano' }} ({{ $profile->effective_commission_pct }}%)
                            </dd>
                        </div>
                    </dl>

                    @if ($profile->status_reason)
                        <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50/70 p-3.5 text-xs text-amber-800">
                            <span class="font-bold">Catatan Status:</span> {{ $profile->status_reason }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Bank & Tax Information Card --}}
            <div class="majapahit-card overflow-hidden">
                <div class="border-b border-[#421b13]/8 bg-[#fff9f4]/60 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-[#d57028]/15 text-[#d57028]">
                            <i class="bi bi-bank2"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#421b13] font-heading">Rekening Bank & Pajak</h3>
                            <p class="text-xs text-[#765f58]">Digunakan untuk pencairan transfer komisi endorsement</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Nama Bank</dt>
                            <dd class="mt-1 text-sm font-bold text-[#421b13] font-heading">
                                {{ $profile->bank_name ?: 'Belum diisi' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Nomor Rekening</dt>
                            <dd class="mt-1 text-sm font-mono font-bold tracking-wide text-[#421b13]">
                                {{ $profile->bank_account_number ?: 'Belum diisi' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">Atas Nama Rekening</dt>
                            <dd class="mt-1 text-sm font-medium text-[#421b13]">
                                {{ $profile->bank_account_name ?: 'Belum diisi' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-[#765f58]">NPWP (Opsional)</dt>
                            <dd class="mt-1 text-sm font-mono font-medium text-[#421b13]">
                                {{ $profile->npwp ?: 'Tidak ada / Belum diisi' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-5 flex items-start gap-2.5 rounded-xl border border-[#421b13]/10 bg-[#fff9f4] p-3 text-xs text-[#765f58]">
                        <i class="bi bi-shield-check text-base text-[#d57028] shrink-0 mt-0.5"></i>
                        <span>Pastikan nama pemilik rekening sesuai dengan identitas Anda agar proses pencairan dana komisi berjalan lancar dan cepat.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Social Media & Rate Card --}}
        <div class="space-y-8">
            {{-- Social Media Accounts Card --}}
            <div class="majapahit-card overflow-hidden">
                <div class="border-b border-[#421b13]/8 bg-[#fff9f4]/60 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-[#d57028]/15 text-[#d57028]">
                            <i class="bi bi-share-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#421b13] font-heading">Akun Media Sosial</h3>
                            <p class="text-xs text-[#765f58]">Platform aktif, jangkauan pengikut, dan engagement rate</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if ($profile->socialMedia && $profile->socialMedia->isNotEmpty())
                        <div class="divide-y divide-[#421b13]/6">
                            @foreach ($profile->socialMedia as $sm)
                                @php
                                    $platformLower = strtolower($sm->platform);
                                    $icon = match (true) {
                                        str_contains($platformLower, 'insta') => ['class' => 'bi-instagram', 'bg' => 'bg-pink-500/10 text-pink-600 border-pink-200'],
                                        str_contains($platformLower, 'tik') => ['class' => 'bi-tiktok', 'bg' => 'bg-neutral-800/10 text-neutral-900 border-neutral-300'],
                                        str_contains($platformLower, 'you') => ['class' => 'bi-youtube', 'bg' => 'bg-red-500/10 text-red-600 border-red-200'],
                                        str_contains($platformLower, 'twit') || str_contains($platformLower, 'x') => ['class' => 'bi-twitter-x', 'bg' => 'bg-slate-900/10 text-slate-900 border-slate-300'],
                                        default => ['class' => 'bi-globe', 'bg' => 'bg-[#d57028]/10 text-[#d57028] border-[#d57028]/20'],
                                    };
                                @endphp

                                <div class="py-4 first:pt-0 last:pb-0 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-xl border {{ $icon['bg'] }} text-xl shadow-2xs">
                                            <i class="bi {{ $icon['class'] }}"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-sm font-bold text-[#421b13] capitalize font-heading">{{ $sm->platform }}</h4>
                                                @if ($sm->profile_url)
                                                    <a href="{{ $sm->profile_url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-[#d57028] hover:text-[#d5282d] transition" title="Buka Profil">
                                                        <i class="bi bi-box-arrow-up-right"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <p class="text-xs text-[#765f58]">
                                                {{ $sm->username ? (str_starts_with($sm->username, '@') ? $sm->username : '@' . $sm->username) : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6 pl-14 sm:pl-0">
                                        <div class="text-right">
                                            <p class="text-xs text-[#765f58]">Followers</p>
                                            <p class="text-sm font-extrabold text-[#421b13] font-heading">
                                                {{ number_format($sm->followers_count ?? 0) }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-[#765f58]">Engagement Rate</p>
                                            <p class="text-sm font-extrabold text-[#b86021] font-heading">
                                                {{ $sm->engagement_rate !== null ? number_format($sm->engagement_rate, 2) . '%' : '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-[#421b13]/15 bg-[#fff9f4]/40 p-8 text-center">
                            <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-[#d57028]/10 text-[#d57028]">
                                <i class="bi bi-share text-xl"></i>
                            </div>
                            <h4 class="mt-3 text-sm font-bold text-[#421b13] font-heading">Belum Ada Media Sosial</h4>
                            <p class="mt-1 text-xs text-[#765f58]">Tambahkan tautan akun media sosial Anda melalui menu edit profil.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Rate Card --}}
            <div class="majapahit-card overflow-hidden">
                <div class="border-b border-[#421b13]/8 bg-[#fff9f4]/60 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-[#d57028]/15 text-[#d57028]">
                            <i class="bi bi-tag-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-[#421b13] font-heading">Rate Card (Tarif Indikatif)</h3>
                            <p class="text-xs text-[#765f58]">Daftar estimasi tarif per platform & format konten</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if ($profile->rateCards && $profile->rateCards->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="border-b border-[#421b13]/8 bg-[#fff9f4] text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                                    <tr>
                                        <th class="px-4 py-2.5">Platform</th>
                                        <th class="px-4 py-2.5">Jenis Konten</th>
                                        <th class="px-4 py-2.5 text-right">Tarif</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#421b13]/6">
                                    @foreach ($profile->rateCards as $rc)
                                        <tr class="hover:bg-[#fff9f4]/50 transition">
                                            <td class="px-4 py-3 font-semibold text-[#421b13] capitalize font-heading">
                                                {{ $rc->platform }}
                                            </td>
                                            <td class="px-4 py-3 text-[#765f58]">
                                                {{ str($rc->content_type)->replace('_', ' ')->title() }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-extrabold text-[#b86021] font-heading">
                                                Rp {{ number_format($rc->rate, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-[#421b13]/15 bg-[#fff9f4]/40 p-8 text-center">
                            <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-[#d57028]/10 text-[#d57028]">
                                <i class="bi bi-cash-stack text-xl"></i>
                            </div>
                            <h4 class="mt-3 text-sm font-bold text-[#421b13] font-heading">Rate Card Belum Diatur</h4>
                            <p class="mt-1 text-xs text-[#765f58]">Atur tarif konten untuk tiap platform agar memudahkan penawaran kolaborasi.</p>
                        </div>
                    @endif

                    <div class="mt-5 rounded-xl border border-[#421b13]/8 bg-[#fff9f4] p-3.5 text-xs text-[#765f58]">
                        <p class="font-semibold text-[#421b13] flex items-center gap-1.5">
                            <i class="bi bi-info-circle-fill text-[#d57028]"></i> Catatan Penting
                        </p>
                        <p class="mt-1 leading-relaxed">
                            Rate card yang tertera bersifat <em>indikatif / negotiable</em>. Nilai fee endorsement aktual pada tiap tawaran campaign dapat disesuaikan berdasarkan kesepakatan spesifik bersama agensi & brand partner.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
