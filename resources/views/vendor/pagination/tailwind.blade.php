@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-3">
        {{-- Mobile View --}}
        <div class="flex justify-between items-center flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3.5 py-2 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl">
                    <i class="bi bi-chevron-left mr-1"></i> Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-3.5 py-2 text-xs font-bold text-[#071d49] bg-white border border-slate-200 rounded-xl hover:bg-blue-50 hover:text-[#0b64d4] hover:border-blue-200 transition">
                    <i class="bi bi-chevron-left mr-1"></i> Prev
                </a>
            @endif

            <span class="text-xs text-slate-500 font-medium">
                Halaman <span class="font-bold text-[#071d49]">{{ $paginator->currentPage() }}</span> dari <span class="font-bold text-[#071d49]">{{ $paginator->lastPage() }}</span>
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-3.5 py-2 text-xs font-bold text-[#071d49] bg-white border border-slate-200 rounded-xl hover:bg-blue-50 hover:text-[#0b64d4] hover:border-blue-200 transition">
                    Next <i class="bi bi-chevron-right ml-1"></i>
                </a>
            @else
                <span class="inline-flex items-center px-3.5 py-2 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl">
                    Next <i class="bi bi-chevron-right ml-1"></i>
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-bold text-[#071d49]">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-bold text-[#071d49]">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-bold text-[#071d49]">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 rounded-2xl p-1 bg-slate-50 border border-slate-200/80">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center justify-center size-8 rounded-xl text-slate-300 cursor-not-allowed" aria-hidden="true">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center size-8 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-blue-50 hover:text-[#0b64d4] hover:border-blue-200 transition shadow-2xs" aria-label="{{ __('pagination.previous') }}">
                            <i class="bi bi-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center justify-center size-8 text-xs font-bold text-slate-400 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center justify-center size-8 rounded-xl text-xs font-extrabold bg-[#0b64d4] text-white shadow-xs">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center size-8 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-[#0b64d4] hover:border-blue-200 transition shadow-2xs" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center size-8 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-blue-50 hover:text-[#0b64d4] hover:border-blue-200 transition shadow-2xs" aria-label="{{ __('pagination.next') }}">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center justify-center size-8 rounded-xl text-slate-300 cursor-not-allowed" aria-hidden="true">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
