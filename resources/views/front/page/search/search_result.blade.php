@extends('front.layouts.app')

@section('content')

    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="news-content">
                        <div class="title-block title-block--iconed">
                            <h1>{{ $content_page['title'] }} {{ request()->title ? 'по запросу ' . request()->title : ''}}</h1>
                        </div>
                        <div class="search-results-filter">
						Сортировать результаты:
                            <div>
                                <div class="companies-list">
                                    <a href="{{ route('search.full', ['sort_order' => $sort_order , 'sort_by' => 'created_at', 'title' => request()->title]) }}"
                                       class="companies-list__sort {{($sort_order == 'desc' && request()->sort_by == 'created_at') ? '' : 'sort-desc'}}">По
                                        дате</a>
                                </div>
                            </div>
                            <div>
                                <div class="companies-list">
                                    <a href="{{ route('search.full', ['sort_order' => $sort_order , 'sort_by' => 'updated_at', 'title' => request()->title]) }}"
                                       class="companies-list__sort {{($sort_order == 'desc' && request()->sort_by == 'updated_at') ? '' : 'sort-desc'}}">По
                                        релевантности</a>
                                </div>
                            </div>
                        </div>
                        <div class="news-rows row">
                            @forelse($results as $row)
                                <div class="news-row active col-12">
                                    @include('front.page.search.partials.' . $row->getTable() , ['row' => $row])
                                </div>
                            @empty
                                <div class="news-row active col-12">
                                    <h2>Совпадений не найдено.</h2>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    {{ $results->links('vendor.pagination.filter-paginator') }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
    @include('front.sidebar_module.shop-category-menu-block')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection