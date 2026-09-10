@extends($role === 'superadmin' ? 'superadmin.layouts.app' : ($role === 'brand' ? 'brand.layouts.app' : 'kol.layouts.app'))

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#0b64d4] font-heading">Aktivitas & Pembaruan</p>
            <h2 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-[#071d49] font-heading">Notifikasi</h2>
            <p class="mt-1 text-sm text-slate-500">Pemberitahuan penting terkait assignment endorsement, review konten, dan pencairan komisi.</p>
        </div>

        @if ($notifications->isNotEmpty())
            <form method="POST" action="{{ route($role.'.notifications.read-all') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-xs transition hover:bg-slate-50 hover:border-slate-300 font-heading focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0b64d4]">
                    <i class="bi bi-check2-all text-sm text-[#0b64d4]"></i>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="space-y-3">
        @forelse ($notifications as $notification)
            <div class="flex items-start gap-4 rounded-2xl border p-5 transition duration-150 {{ $notification->is_read ? 'border-slate-200/80 bg-white opacity-90 hover:opacity-100 hover:bg-slate-50/50' : 'border-blue-200/90 bg-blue-50/30 shadow-xs hover:bg-blue-50/50' }}">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl {{ $notification->is_read ? 'bg-slate-100 text-slate-400' : 'bg-gradient-to-br from-[#0b64d4] to-[#1698f6] text-white shadow-xs shadow-blue-500/20' }}">
                    <i class="bi {{ $notification->is_read ? 'bi-bell' : 'bi-bell-fill' }} text-lg"></i>
                </span>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="text-sm font-extrabold text-[#071d49] font-heading">
                            {{ $notification->title }}
                        </p>
                        <span class="text-[11px] font-medium text-slate-400">
                            {{ $notification->created_at->format('d M Y, H:i') }} ({{ $notification->created_at->diffForHumans() }})
                        </span>
                    </div>

                    <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $notification->body }}</p>

                    <div class="mt-3 flex items-center gap-3">
                        @if ($notification->target_url)
                            <a href="{{ route($role.'.notifications.read', $notification) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0b64d4] hover:text-[#0c3685] transition font-heading">
                                <span>Buka Halaman Terkait</span>
                                <i class="bi bi-arrow-right text-[10px]"></i>
                            </a>
                        @endif

                        @if (! $notification->is_read)
                            <form method="POST" action="{{ route($role.'.notifications.read', $notification) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs font-bold text-slate-500 hover:text-[#0b64d4] transition font-heading">
                                    Tandai sudah dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-200 bg-white py-16 px-6 text-center shadow-xs">
                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-blue-50 text-[#0b64d4]">
                    <i class="bi bi-bell-slash text-2xl"></i>
                </div>
                <h4 class="mt-4 text-base font-extrabold text-[#071d49] font-heading">Tidak ada notifikasi</h4>
                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
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

