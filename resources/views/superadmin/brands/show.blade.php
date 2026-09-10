@extends('superadmin.layouts.app')

@section('title', $brand->name . ' | Detail Brand')
@section('page-title', 'Detail Brand')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('superadmin.brands.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Brand & Klien</span>
            </a>
            <div class="mt-3 flex items-center gap-4">
                <div class="flex size-14 items-center justify-center rounded-2xl bg-gradient-to-br from-kerajaan-orange to-kerajaan-red text-xl font-black text-white shadow-sm font-heading">
                    {{ str($brand->name)->substr(0, 1)->upper() }}
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-kerajaan-dark font-heading">{{ $brand->name }}</h2>
                    <p class="text-xs text-kerajaan-muted">{{ $brand->industry ?: 'Sektor industri belum ditentukan' }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-dashboard.status-badge :status="$brand->is_active ? 'aktif' : 'nonaktif'" />
            <a href="{{ route('superadmin.brands.edit', $brand) }}" class="inline-flex items-center gap-2 rounded-xl bg-kerajaan-dark px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#190906] font-heading">
                <i class="bi bi-pencil"></i>
                <span>Edit Brand</span>
            </a>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Contact Info --}}
        <section class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs lg:col-span-2">
            <div class="border-b border-kerajaan-dark/5 pb-4">
                <h3 class="font-extrabold text-kerajaan-dark font-heading">Informasi Kontak & PIC</h3>
                <p class="text-xs text-kerajaan-muted">Kontak perwakilan brand untuk koordinasi campaign</p>
            </div>

            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Nama PIC</dt>
                    <dd class="mt-1 text-sm font-semibold text-kerajaan-dark">{{ $brand->pic_name ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Jabatan / Posisi</dt>
                    <dd class="mt-1 text-sm font-semibold text-kerajaan-dark">{{ $brand->pic_title ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Email PIC</dt>
                    <dd class="mt-1 text-sm font-semibold text-kerajaan-dark">
                        @if ($brand->pic_email)
                            <a href="mailto:{{ $brand->pic_email }}" class="text-kerajaan-orange hover:underline">{{ $brand->pic_email }}</a>
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Nomor Telepon</dt>
                    <dd class="mt-1 text-sm font-semibold text-kerajaan-dark">
                        @if ($brand->pic_phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $brand->pic_phone) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-700 hover:underline">
                                <i class="bi bi-whatsapp"></i>
                                <span>{{ $brand->pic_phone }}</span>
                            </a>
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Alamat Kantor</dt>
                    <dd class="mt-1 text-sm leading-relaxed text-kerajaan-muted">{{ $brand->address ?: 'Alamat belum dilengkapi.' }}</dd>
                </div>
            </dl>
        </section>

        {{-- Brand Overview Stats --}}
        <section class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <div class="border-b border-kerajaan-dark/5 pb-4">
                <h3 class="font-extrabold text-kerajaan-dark font-heading">Statistik Portfolio</h3>
            </div>
            <div class="mt-5 space-y-4">
                <div class="rounded-xl border border-kerajaan-dark/5 bg-kerajaan-cream p-4 text-center">
                    <p class="text-3xl font-black text-kerajaan-dark font-heading">{{ $brand->campaigns->count() }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Total Campaign</p>
                </div>
                <div class="rounded-xl border border-kerajaan-dark/5 bg-kerajaan-cream p-4 text-center">
                    <p class="text-3xl font-black text-kerajaan-orange font-heading">{{ $brand->campaigns->where('status', 'aktif')->count() }}</p>
                    <p class="mt-1 text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">Campaign Berjalan</p>
                </div>
            </div>
        </section>
    </div>

    {{-- Related Campaigns Table --}}
    <section class="overflow-hidden rounded-2xl border border-kerajaan-dark/8 bg-white shadow-xs">
        <div class="flex items-center justify-between border-b border-kerajaan-dark/5 p-5">
            <div>
                <h3 class="font-extrabold text-kerajaan-dark font-heading">Campaign Terkait</h3>
                <p class="text-xs text-kerajaan-muted">Daftar program kampanye yang didaftarkan oleh {{ $brand->name }}</p>
            </div>
            <a href="{{ route('superadmin.campaigns.create') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
                <i class="bi bi-plus-lg"></i>
                <span>Buat Campaign</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-y border-kerajaan-dark/10 bg-kerajaan-cream text-xs font-bold uppercase tracking-wider text-kerajaan-muted font-heading">
                    <tr>
                        <th class="px-5 py-3.5">Nama Campaign</th>
                        <th class="px-5 py-3.5">Periode Pelaksanaan</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kerajaan-dark/5">
                    @forelse ($brand->campaigns as $campaign)
                        <tr class="transition hover:bg-kerajaan-sand/40">
                            <td class="px-5 py-4 font-bold text-kerajaan-dark font-heading">
                                <a href="{{ route('superadmin.campaigns.show', $campaign) }}" class="hover:text-kerajaan-orange">
                                    {{ $campaign->name }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-xs text-kerajaan-muted">
                                {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }} s/d {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <x-dashboard.status-badge :status="$campaign->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-xs text-kerajaan-muted">
                                Belum ada riwayat campaign untuk brand partner ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
