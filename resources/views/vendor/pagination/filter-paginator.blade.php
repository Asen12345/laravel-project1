@if ($paginator->hasPages())

    <div class="li-pagination row">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div class="li-pagination__prev disabled col-4" aria-disabled="true">

            </div>
        @else
            <a href="{{ route('search.full', ['sort_order' => request()->sort_order , 'sort_by' => request()->sort_by])  . '&page=' . ($paginator->currentPage() - 1)  }}" rel="prev" class="li-pagination__prev col-4">
                <div class="sp-icon ico-pager-prev"></div>
                <span>предыдущая страница</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                <ul class="li-pagination__list mnu-standart-style-reset col-4">
                    @php $start = $paginator->currentPage() - 4; $end = $paginator->currentPage() + 4; @endphp
                    @if ($paginator->currentPage() > 4)
                        <li class="active" aria-current="page"><span>....</span></li>
                    @endif
                    @foreach ($element as $page => $url)
                        @if ($page > $start && $page < $end)
                            @if ($page == $paginator->currentPage())
                                <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ route('search.full', ['sort_order' => request()->sort_order , 'sort_by' => request()->sort_by])  . str_replace("/?", '&', $url) }}">{{ $page }}</a></li>
                            @endif
                        @endif
                    @endforeach
                    @if ($paginator->lastPage() - $end >= 0)
                        <li class="active" aria-current="page"><span>....</span></li>
                    @endif
                </ul>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ route('search.full', ['sort_order' => request()->sort_order , 'sort_by' => request()->sort_by])  . '&page=' . $page  }}" rel="next" class="li-pagination__prev col-4">
                <span>следующая страница</span>
                <div class="sp-icon ico-pager-next"></div>
            </a>
        @else
            <div class="li-pagination__prev disabled col-4" aria-disabled="true">
            </div>
        @endif
    </div>
@endif
