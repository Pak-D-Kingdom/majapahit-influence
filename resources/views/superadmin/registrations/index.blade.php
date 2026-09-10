@extends('superadmin.layouts.app')

@section('title', 'Pendaftaran KOL | Superadmin kerajaan Influence')
@section('page-title', 'Pendaftaran KOL')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-kerajaan-dark font-heading">Pendaftaran KOL Baru</h1>
            <p class="text-xs text-kerajaan-muted mt-1">Review kandidat kreator yang mendaftar melalui halaman publik untuk bergabung dengan agensi.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.kol.index') }}" class="btn-kerajaan-secondary text-xs">
                <i class="bi bi-people-fill"></i> Database KOL Aktif
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-kerajaan-dark/8 bg-white p-4 shadow-sm">
        <div class="min-w-[200px]">
            <select name="status" class="w-full rounded-xl border border-kerajaan-dark/15 text-xs py-2.5 px-3 text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-none">
                <option value="">Semua Status Pengajuan</option>
                <option value="pending_review" @selected(request('status') === 'pending_review')>Menunggu Review</option>
                <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
            </select>
        </div>
        <button type="submit" class="btn-kerajaan-primary text-xs py-2 px-4">
            <i class="bi bi-funnel-fill"></i>
            <span>Filter</span>
        </button>
        @if (request()->hasAny(['status', 'search']))
            <a href="{{ route('superadmin.registrations.index') }}" class="btn-kerajaan-secondary text-xs py-2 px-3">
                Reset
            </a>
        @endif
    </form>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-kerajaan-dark/8 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-xs">
                <thead class="bg-kerajaan-cream border-b border-kerajaan-dark/8 text-[11px] font-bold uppercase tracking-wider text-kerajaan-muted font-heading">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold">No. Registrasi</th>
                        <th class="px-5 py-3.5 font-semibold">Kreator / Kontak</th>
                        <th class="px-5 py-3.5 font-semibold">Platform & Domisili</th>
                        <th class="px-5 py-3.5 font-semibold">Tanggal Daftar</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kerajaan-dark/6">
                    @forelse ($registrations as $registration)
                        <tr class="hover:bg-kerajaan-cream/60 transition-colors">
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-kerajaan-orange/10 border border-kerajaan-orange/25 px-2.5 py-1 font-mono text-xs font-bold text-kerajaan-orange">
                                    {{ $registration->registration_number }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-kerajaan-dark font-heading text-sm">{{ $registration->full_name }}</p>
                                <p class="text-xs text-kerajaan-muted">{{ $registration->email }}</p>
                            </td>
                            <td class="px-5 py-4 text-kerajaan-dark">
                                <div class="font-semibold">{{ $registration->platforms_label }}</div>
                                <div class="text-[11px] text-kerajaan-muted">{{ $registration->city ?: 'Kota belum diisi' }}</div>
                            </td>
                            <td class="px-5 py-4 text-kerajaan-muted whitespace-nowrap">
                                {{ $registration->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-4">
                                <x-dashboard.status-badge :status="$registration->status"/>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('superadmin.registrations.show', $registration) }}" class="btn-kerajaan-primary text-xs py-1.5 px-3">
                                    <i class="bi bi-eye"></i>
                                    <span>Review</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-kerajaan-muted">
                                <div class="flex size-12 mx-auto items-center justify-center rounded-2xl bg-kerajaan-sand text-kerajaan-muted mb-3">
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
            <div class="border-t border-kerajaan-dark/8 px-5 py-4">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
