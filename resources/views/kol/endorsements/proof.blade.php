@extends('kol.layouts.app')

@section('title', 'Upload Bukti Konten')
@section('page-title', 'Upload Bukti Konten')

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('kol.endorsements.show', $endorsement) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#0b64d4] hover:text-[#0c3685] transition font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Detail Endorsement</span>
        </a>
        <div class="mt-3">
            <span class="rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-[#0b64d4] border border-blue-200 font-heading">
                {{ $endorsement->campaign->brand->name }}
            </span>
            <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#071d49] font-heading">
                Upload Bukti Konten
            </h2>
            <p class="mt-1 text-sm text-slate-500">Campaign: <strong class="text-[#071d49]">{{ $endorsement->campaign->name }}</strong> ({{ str($endorsement->content_type)->replace('_', ' ')->title() }})</p>
        </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/80 p-5 text-sm text-rose-800 shadow-xs">
            <div class="flex items-center gap-2 font-bold font-heading">
                <i class="bi bi-exclamation-octagon-fill text-rose-600"></i>
                <span>Terdapat kesalahan pada formulir:</span>
            </div>
            <ul class="mt-2 list-inside list-disc text-xs space-y-1 text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" enctype="multipart/form-data" action="{{ route('kol.endorsements.proof.store', $endorsement) }}" class="max-w-2xl space-y-6">
        @csrf

        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="grid gap-6 sm:grid-cols-2">
                {{-- Tanggal Posting --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Tanggal Posting <span class="text-rose-500">*</span>
                    </label>
                    <input type="date"
                           name="posted_at"
                           value="{{ old('posted_at', now()->toDateString()) }}"
                           required
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                {{-- Link Posting --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Link / URL Posting
                    </label>
                    <input type="url"
                           name="post_url"
                           value="{{ old('post_url') }}"
                           placeholder="https://instagram.com/p/... atau https://tiktok.com/..."
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">
                </div>

                {{-- Catatan --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        Catatan Tambahan
                    </label>
                    <textarea name="notes"
                              rows="3"
                              placeholder="Tuliskan catatan tambahan untuk tim Admin (misal: jam tayang, performa insight, dll)..."
                              class="mt-2 w-full rounded-xl border border-slate-200 bg-white p-3.5 text-sm text-slate-800 placeholder:text-slate-400 transition focus:border-[#0b64d4] focus:outline-hidden focus:ring-2 focus:ring-[#0b64d4]/20">{{ old('notes') }}</textarea>
                </div>

                {{-- File Bukti --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#071d49] font-heading">
                        File Bukti Konten / Screenshot <span class="text-rose-500">*</span>
                    </label>
                    <div class="mt-2 rounded-2xl border-2 border-dashed border-[#0b64d4]/30 bg-slate-50/70 p-6 text-center transition hover:bg-slate-50 hover:border-[#0b64d4]">
                        <div class="mx-auto flex size-12 items-center justify-center rounded-xl bg-white text-[#0b64d4] shadow-xs">
                            <i class="bi bi-cloud-arrow-up text-2xl"></i>
                        </div>
                        <p class="mt-3 text-sm font-bold text-[#071d49] font-heading">Pilih file bukti konten</p>
                        <p class="mt-1 text-xs text-slate-500">1–5 file, JPG, PNG, atau PDF (maks. 5MB per file)</p>
                        <input type="file"
                               name="files[]"
                               multiple
                               required
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-4 block w-full text-xs text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-gradient-to-r file:from-[#0b64d4] file:to-[#1698f6] file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-white file:shadow-xs hover:file:brightness-105 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('kol.endorsements.show', $endorsement) }}" class="btn-kerajaan-secondary">
                Batal
            </a>
            <button type="submit" class="btn-kerajaan-primary">
                <i class="bi bi-send-fill"></i>
                <span>Kirim untuk Direview</span>
            </button>
        </div>
    </form>
@endsection
