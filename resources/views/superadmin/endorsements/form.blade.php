@extends('superadmin.layouts.app')

@section('title', 'Edit Endorsement | Superadmin')
@section('page-title', 'Edit Endorsement')

@section('content')
<div class="space-y-6 max-w-3xl">
    {{-- Header --}}
    <div>
        <a href="{{ route('superadmin.endorsements.show', $endorsement) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#d57028] hover:text-[#b86021] font-heading">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Detail Endorsement</span>
        </a>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-[#421b13] font-heading">Edit Parameter Endorsement</h2>
        <p class="mt-1 text-xs text-[#765f58]">
            Campaign: <strong class="text-[#421b13]">{{ $endorsement->campaign->name }}</strong> &bull;
            Kreator: <strong class="text-[#421b13]">{{ $endorsement->kolProfile->user->name }}</strong>
        </p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-[#d5282d]/20 bg-[#d5282d]/10 p-4 text-xs text-[#d5282d]">
            <p class="font-bold font-heading">Periksa kembali data formulir berikut:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('superadmin.endorsements.update', $endorsement) }}" class="rounded-2xl border border-[#421b13]/8 bg-white p-6 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-[#421b13] font-heading">
                    Format Konten <span class="text-[#d5282d]">*</span>
                </label>
                <select name="content_type" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                    @foreach (['reels' => 'Instagram Reels', 'video' => 'TikTok Video', 'feed_post' => 'Feed Post', 'story' => 'Instagram Story'] as $key => $label)
                        <option value="{{ $key }}" @selected($endorsement->content_type === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#421b13] font-heading">Status Pengerjaan</label>
                <select name="status" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
                    @foreach (['assigned' => 'Ditugaskan', 'in_progress' => 'Sedang Berjalan', 'content_submitted' => 'Konten Diajukan', 'content_approved' => 'Konten Disetujui', 'content_rejected' => 'Perlu Revisi', 'selesai' => 'Selesai'] as $status => $label)
                        <option value="{{ $status }}" @selected($endorsement->status === $status)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#421b13] font-heading">
                    Fee Endorsement (Rp) <span class="text-[#d5282d]">*</span>
                </label>
                <input type="number" name="fee" min="0" value="{{ old('fee', $endorsement->fee) }}" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#421b13] font-heading">
                    Batas Deadline <span class="text-[#d5282d]">*</span>
                </label>
                <input type="date" name="deadline" value="{{ old('deadline', $endorsement->deadline->format('Y-m-d')) }}" required class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-[#421b13] font-heading">Tanggal Mulai Pengerjaan</label>
                <input type="date" name="start_date" value="{{ old('start_date', $endorsement->start_date?->format('Y-m-d')) }}" class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-[#421b13] font-heading">Catatan Penugasan Khusus</label>
                <textarea name="notes" rows="4" placeholder="Instruksi tambahan atau penyesuaian teknis untuk kreator..." class="mt-2 w-full rounded-xl border border-[#421b13]/15 bg-white px-3.5 py-2.5 text-sm text-[#421b13] focus:border-[#d57028] focus:ring-2 focus:ring-[#d57028]/20 focus:outline-hidden">{{ old('notes', $endorsement->notes) }}</textarea>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 border-t border-[#421b13]/5 pt-4">
            <a href="{{ route('superadmin.endorsements.show', $endorsement) }}" class="rounded-xl border border-[#421b13]/15 bg-white px-5 py-2.5 text-xs font-bold text-[#421b13] transition hover:bg-[#f7eee8] font-heading">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-gradient-to-r from-[#d57028] to-[#b86021] px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:from-[#b86021] hover:to-[#934510] font-heading">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
