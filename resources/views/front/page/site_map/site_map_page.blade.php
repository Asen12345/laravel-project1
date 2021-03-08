@extends('front.layouts.app')

@section('section_header')
@endsection

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="news-content">
                        <div class="title-block title-block--iconed">
                            <h1>{{$content_page['title']}}</h1>
                        </div>
                        <div class="h-mt-20">
                            <ul class="h-ml-10">
                                <h3><a href="{{ route('front.page.blogs.all') }}">Блоги</a></h3>
                                <h3><a href="{{ route('front.news.rss') }}">RSS-сервис</a></h3>
                                @if ($textPages->isNotEmpty())
                                    @foreach($textPages as $page)
                                        <h3><a href="{{ route('front.page.text.page', ['url_en' => $page->url_en]) }}">{{ $page->title }}</a></h3>
                                    @endforeach
                                @endif
                            </ul>

                            @if ($newsCategory->isNotEmpty())
                                <h2>Новости</h2>
                                <ul class="h-ml-10">
                                @foreach($newsCategory as $row)
                                        @if ($row->parent_id == 0)
                                            {{--Url news category without child--}}
                                            <h3><a href="{{route('front.page.news.category', ['url_section' => $row->url_en])}}">{{ $row->name }} ({{ $row->news_count }})</a></h3>
                                        @else
                                            {{--Url news category have child--}}
                                            <h3><a href="{{route('front.page.news.category', ['url_section' => $row->parent->url_en, 'url_sub_section' => $row->url_en])}}">{{ $row->name }} ({{ $row->news_count }})</a></h3>
                                        @endif

                                @endforeach
                                </ul>
                            @endif
                                @if ($shopCategoryResearches->isNotEmpty())
                                    <h2>Магазин исследований</h2>
                                    <ul class="h-ml-10">
                                        @foreach($shopCategoryResearches as $category)

                                            <h3><a href="{{route('front.page.shop.researches.category', ['url_section' => $category->url_en])}}">{{$category->name}}</a>

                                            @foreach($category->researches as $row)
                                                <li class="text-decoration-none">
                                                    <a href="{{route('front.page.shop.researches.category.entry', ['id' => $row->id])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{ $row->title }}</a>
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection