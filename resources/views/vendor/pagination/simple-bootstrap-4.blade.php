@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 border border-gray-300 rounded cursor-not-allowed">
                    &lsaquo; Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                    &lsaquo; Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="flex gap-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-3 py-2 text-sm text-gray-700">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-2 text-sm bg-blue-600 text-white border border-blue-600 rounded">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                    Next &rsaquo;
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 border border-gray-300 rounded cursor-not-allowed">
                    Next &rsaquo;
                </span>
            @endif
        </div>
    </nav>
@endif
