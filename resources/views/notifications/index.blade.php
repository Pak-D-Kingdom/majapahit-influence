@extends($role === 'superadmin' ? 'superadmin.layouts.app' : ($role === 'brand' ? 'brand.layouts.app' : 'kol.layouts.app'))

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#d57028] font-heading">Aktivitas & Pembaruan</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#421b13] font-heading">Notifikasi</h2>
            <p class="mt-1 text-sm text-[#765f58]">Pemberitahuan penting terkait assignment endorsement, review konten, dan pencairan komisi.</p>
        </div>

        @if ($notifications->isNotEmpty())
            <form method="POST" action="{{ route($role.'.notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn-majapahit-secondary text-xs">
                    <i class="bi bi-check2-all text-sm text-[#d57028]"></i>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        @endif
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 text-sm font-medium text-emerald-800 shadow-xs">
            <i class="bi bi-check-circle-fill text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Notifications List --}}
    <div class="space-y-3">
        @forelse ($notifications as $notification)
            <div class="flex items-start gap-4 rounded-2xl border p-5 transition duration-150 {{ $notification->is_read ? 'border-[#421b13]/8 bg-white opacity-85 hover:opacity-100' : 'border-[#d57028]/30 bg-[#fff9f4] shadow-xs' }}">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl {{ $notification->is_read ? 'bg-[#f7eee8] text-[#765f58]' : 'bg-gradient-to-br from-[#d57028] to-[#d5282d] text-white shadow-xs' }}">
                    <i class="bi {{ $notification->is_read ? 'bi-bell' : 'bi-bell-fill' }} text-lg"></i>
                </span>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="text-sm font-extrabold text-[#421b13] font-heading">
                            {{ $notification->title }}
                        </p>
                        <span class="text-[11px] text-[#765f58]">
                            {{ $notification->created_at->format('d M Y, H:i') }} ({{ $notification->created_at->diffForHumans() }})
                        </span>
                    </div>

                    <p class="mt-1 text-xs leading-relaxed text-[#765f58]">{{ $notification->body }}</p>

                    <div class="mt-3 flex items-center gap-3">
                        @if ($notification->target_url)
                            <a href="{{ route($role.'.notifications.read', $notification) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#d57028] hover:text-[#d5282d] font-heading">
                                <span>Buka Halaman Terkait</span>
                                <i class="bi bi-arrow-right text-[10px]"></i>
                            </a>
                        @endif

                        @if (! $notification->is_read)
                            <form method="POST" action="{{ route($role.'.notifications.read', $notification) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs font-bold text-[#765f58] hover:text-[#d57028] transition font-heading">
                                    Tandai sudah dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-[#421b13]/15 bg-white py-16 px-6 text-center shadow-xs">
                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-[#fff9f4] text-[#d57028]">
                    <i class="bi bi-bell-slash text-2xl"></i>
                </div>
                <h4 class="mt-4 text-base font-extrabold text-[#421b13] font-heading">Tidak ada notifikasi</h4>
                <p class="mt-1 text-xs text-[#765f58] max-w-sm mx-auto">
                    Semua pemberitahuan aktivitas akun, endorsement, dan komisi akan muncul di sini.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
@endsection

