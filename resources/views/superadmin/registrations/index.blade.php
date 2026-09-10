@extends('superadmin.layouts.app')

@section('title', 'Pendaftaran KOL | Superadmin Majapahit Influence')
@section('page-title', 'Pendaftaran KOL')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#421b13] font-heading">Pendaftaran KOL Baru</h1>
            <p class="text-xs text-[#765f58] mt-1">Review kandidat kreator yang mendaftar melalui halaman publik untuk bergabung dengan agensi.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.kol.index') }}" class="btn-majapahit-secondary text-xs">
                <i class="bi bi-people-fill"></i> Database KOL Aktif
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-[#421b13]/8 bg-white p-4 shadow-sm">
        <div class="min-w-[200px]">
            <select name="status" class="w-full rounded-xl border border-[#421b13]/15 text-xs py-2.5 px-3 text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-none">
                <option value="">Semua Status Pengajuan</option>
                <option value="pending_review" @selected(request('status') === 'pending_review')>Menunggu Review</option>
                <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
            </select>
        </div>
        <button type="submit" class="btn-majapahit-primary text-xs py-2 px-4">
            <i class="bi bi-funnel-fill"></i>
            <span>Filter</span>
        </button>
        @if (request()->hasAny(['status', 'search']))
            <a href="{{ route('superadmin.registrations.index') }}" class="btn-majapahit-secondary text-xs py-2 px-3">
                Reset
            </a>
        @endif
    </form>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-[#421b13]/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-xs">
                <thead class="bg-[#fbf7f4] border-b border-[#421b13]/8 text-[11px] font-bold uppercase tracking-wider text-[#765f58] font-heading">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold">No. Registrasi</th>
                        <th class="px-5 py-3.5 font-semibold">Kreator / Kontak</th>
                        <th class="px-5 py-3.5 font-semibold">Platform & Domisili</th>
                        <th class="px-5 py-3.5 font-semibold">Tanggal Daftar</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#421b13]/6">
                    @forelse ($registrations as $registration)
                        <tr class="hover:bg-[#fff9f4]/60 transition-colors">
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-[#d57028]/10 border border-[#d57028]/25 px-2.5 py-1 font-mono text-xs font-bold text-[#d57028]">
                                    {{ $registration->registration_number }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-[#421b13] font-heading text-sm">{{ $registration->full_name }}</p>
                                <p class="text-xs text-[#765f58]">{{ $registration->email }}</p>
                            </td>
                            <td class="px-5 py-4 text-[#421b13]">
                                <div class="font-semibold">{{ $registration->platforms_label }}</div>
                                <div class="text-[11px] text-[#765f58]">{{ $registration->city ?: 'Kota belum diisi' }}</div>
                            </td>
                            <td class="px-5 py-4 text-[#765f58] whitespace-nowrap">
                                {{ $registration->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-4">
                                <x-dashboard.status-badge :status="$registration->status"/>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('superadmin.registrations.show', $registration) }}" class="btn-majapahit-primary text-xs py-1.5 px-3">
                                    <i class="bi bi-eye"></i>
                                    <span>Review</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-[#765f58]">
                                <div class="flex size-12 mx-auto items-center justify-center rounded-2xl bg-[#f7eee8] text-[#765f58] mb-3">
                                    <i class="bi bi-person-slash text-xl"></i>
                                </div>
                                <p class="font-medium text-sm">Belum ada data pendaftaran KOL.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registrations->hasPages())
            <div class="border-t border-[#421b13]/8 px-5 py-4">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
