{{--
@if ($paginator->hasPages())

    <div class="li-pagination">
        --}}
{{-- Previous Page Link --}}{{--

        @if ($paginator->onFirstPage())
            <div class="li-pagination__prev disabled col-4" aria-disabled="true">

            </div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="li-pagination__prev col-4">
                <div class="sp-icon ico-pager-prev"></div>
                <span>предыдущая страница</span>
            </a>
        @endif

        --}}
{{-- Pagination Elements --}}{{--

        @foreach ($elements as $element)
            --}}
{{-- "Three Dots" Separator --}}{{--

            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            --}}
{{-- Array Of Links --}}{{--

            @if (is_array($element))
                <ul class="li-pagination__list mnu-standart-style-reset col-4 text-center">
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
                </ul>
            @endif
        @endforeach

        --}}
{{-- Next Page Link --}}{{--

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="li-pagination__prev col-4">
                <span>следующая страница</span>
                <div class="sp-icon ico-pager-next"></div>
            </a>
        @else
            <div class="li-pagination__prev disabled col-4" aria-disabled="true">

            </div>
        @endif
    </div>
@endif
--}}

@if ($paginator->hasPages())
    <div class="li-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div class="li-pagination__prev disabled col-3" aria-disabled="true">

            </div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="li-pagination__prev">
                <div class="sp-icon ico-pager-prev"></div>
                <span>предыдущая страница</span>
            </a>
        @endif

        <ul class="li-pagination__list mnu-standart-style-reset">
            @if ($paginator->currentPage() > 4)
                <li class="active" aria-current="page"><span>....</span></li>
            @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Array Of Links --}}
            @if (is_array($element))
                    @php $start = $paginator->currentPage() - 4; $end = $paginator->currentPage() + 4; @endphp
                    @foreach ($element as $page => $url)
                        @if ($page > $start && $page < $end)
                            @if ($page == $paginator->currentPage())
                                <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ str_replace("/?", '&', $url) }}">{{ $page }}</a></li>
                            @endif
                        @endif
                    @endforeach
            @endif
        @endforeach
                @if ($paginator->lastPage() - $end >= 0)
                    <li class="active" aria-current="page"><span>....</span></li>
                @endif
            </ul>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="li-pagination__prev">
                <span>следующая страница</span>
                <div class="sp-icon ico-pager-next"></div>
            </a>
        @else
            <div class="li-pagination__prev disabled col-3" aria-disabled="true">
            </div>
        @endif
    </div>
@endif

