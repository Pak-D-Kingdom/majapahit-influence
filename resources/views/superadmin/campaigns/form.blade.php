@extends('superadmin.layouts.app')

@section('title', ($mode === 'create' ? 'Tambah Campaign Baru' : 'Edit Campaign') . ' | Superadmin')
@section('page-title', $mode === 'create' ? 'Tambah Campaign Baru' : 'Edit Campaign')

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Header --}}
    <div>
        <a href="{{ route('superadmin.campaigns.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-kerajaan-orange hover:text-kerajaan-brown font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Campaign</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-kerajaan-dark font-heading">
            {{ $mode === 'create' ? 'Buat Campaign Baru' : 'Edit Parameter Campaign' }}
        </h2>
        <p class="mt-1 text-xs text-kerajaan-muted">
            {{ $mode === 'create' ? 'Tentukan objektif, alokasi budget, dan panduan brief endorsement.' : 'Perbarui periode, budget, atau instruksi konten campaign.' }}
        </p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-kerajaan-red/20 bg-kerajaan-red/10 p-4 text-xs text-kerajaan-red">
            <p class="font-bold font-heading flex items-center gap-1.5">
                <i class="bi bi-exclamation-triangle-fill"></i> Periksa kembali data formulir berikut:
            </p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" enctype="multipart/form-data" action="{{ $mode === 'create' ? route('superadmin.campaigns.store') : route('superadmin.campaigns.update', $campaign) }}" class="space-y-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        {{-- Section 1: Informasi Utama --}}
        <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-kerajaan-dark/5 pb-3 font-extrabold text-kerajaan-dark font-heading">Informasi Dasar Campaign</h3>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Nama Campaign <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required placeholder="Contoh: Ramadhan Glow Campaign 2026" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Brand Partner <span class="text-kerajaan-red">*</span>
                    </label>
                    <select name="brand_id" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                        <option value="">-- Pilih Brand Klien --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(old('brand_id', $campaign->brand_id) == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Status Campaign</label>
                    <select name="status" class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                        @foreach (['draft' => 'Draft', 'aktif' => 'Aktif', 'selesai' => 'Selesai'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $campaign->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Tanggal Mulai <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date', $campaign->start_date?->format('Y-m-d') ?: date('Y-m-d')) }}" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Tanggal Selesai <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date', $campaign->end_date?->format('Y-m-d') ?: date('Y-m-d', strtotime('+30 days'))) }}" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Total Alokasi Budget (Rp) <span class="text-kerajaan-red">*</span>
                    </label>
                    <input type="number" min="0" step="50000" name="budget" value="{{ old('budget', (int)($campaign->budget ?? 10000000)) }}" required class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm font-bold text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        File Dokumen Brief <span class="text-xs font-normal text-kerajaan-muted">(PDF, DOCX, JPG, PNG - Maks 5 File)</span>
                    </label>
                    <input type="file" name="brief_files[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="mt-2 block w-full rounded-xl border border-dashed border-kerajaan-dark/20 p-2.5 text-xs text-kerajaan-muted file:mr-3 file:rounded-lg file:border-0 file:bg-kerajaan-orange/10 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-kerajaan-orange">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">
                        Deskripsi Singkat <span class="text-kerajaan-red">*</span>
                    </label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan gambaran umum tujuan campaign ini..." class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('description', $campaign->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 2: Panduan Konten --}}
        <div class="rounded-2xl border border-kerajaan-dark/8 bg-white p-6 shadow-xs">
            <h3 class="border-b border-kerajaan-dark/5 pb-3 font-extrabold text-kerajaan-dark font-heading">Instruksi & Panduan Kreatif</h3>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Content Requirements</label>
                    <textarea name="content_requirements" rows="4" placeholder="Poin-poin wajib: durasi minimal, tag akun wajib, hashtag, sound yang digunakan..." class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('content_requirements', $campaign->content_requirements) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-kerajaan-dark font-heading">Do's and Don'ts</label>
                    <textarea name="dos_and_donts" rows="4" placeholder="Hal yang boleh dan dilarang: dilarang menyebut merek pesaing, wajib pencahayaan natural..." class="mt-2 w-full rounded-xl border border-kerajaan-dark/15 bg-white px-3.5 py-2.5 text-sm text-kerajaan-dark focus:border-kerajaan-orange focus:ring-2 focus:ring-kerajaan-orange/20 focus:outline-hidden">{{ old('dos_and_donts', $campaign->dos_and_donts) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('superadmin.campaigns.index') }}" class="rounded-xl border border-kerajaan-dark/15 bg-white px-5 py-2.5 text-xs font-bold text-kerajaan-dark transition hover:bg-kerajaan-sand font-heading">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-kerajaan-orange to-kerajaan-brown px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-kerajaan-brown hover:to-[#934510] font-heading flex items-center gap-1.5">
                <i class="bi bi-check2-circle text-base"></i>
                <span>{{ $mode === 'create' ? 'Simpan Campaign' : 'Perbarui Campaign' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
