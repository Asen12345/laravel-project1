@extends('front.layouts.app')

@section('content')

<div class="col-xl-9 col-lg-8 main-content">
    <div class="container-fluid">
        <div class="row m-minus">
            <div class="col-12">
                <div class="news-content">
                    <div class="title-block title-block--iconed">
                        <h1>Новости</h1>

                    </div>
                    <div class="news-rows">
                    @forelse($vipNews as $row)
                        <div class="news-row active col-12">
						<div class="news-item__icon sp-icon ico-telegram-mini"></div>
                            @if ($row->scene->isNotEmpty() && $row->scene->first()->image !== null)
                                <div class="news-row__pict">
                                    <div><img src="{{$row->scene->first()->image}}" alt="alt"></div>
                                </div>
                            @endif
                            @if ($row->category->parent_id == 0)
                                {{--Url news category without child--}}
                                <h2><a href="{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->announce ?? $row->name}}</a></h2>
                            @else
                                {{--Url news category have child--}}
                                <h2><a href="{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->announce ?? $row->name}}</a></h2>
                            @endif

                            <div class="news-row__info">
                                <div class="blog-row__date">{{\Carbon\Carbon::parse($row->created_at)->format('d.m.Y')}}</div>
                                <div class="blogs-previews__views">
                                    <div class="ico-eye"></div>
                                    <div class="blogs-previews__views-count">{{$row->views_count ?? '0'}}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    @forelse($news as $row)
                        <div class="news-row active col-12">
						<div class="news-item__icon sp-icon ico-telegram-mini"></div>
                            @if ($row->scene->isNotEmpty() && $row->scene->first()->image !== null)
                                <div class="news-row__pict">
                                    <div><img src="{{$row->scene->first()->image}}" alt="alt"></div>
                                </div>
                            @endif
                            @if ($row->category->parent_id == 0)
                                {{--Url news category without child--}}
                                <h2><a href="{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->announce ?? $row->name}}</a></h2>
                            @else
                                {{--Url news category have child--}}
                                <h2><a href="{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{$row->announce ?? $row->name}}</a></h2>
                            @endif

                            <div class="news-row__info">
                                <div class="blog-row__date">{{\Carbon\Carbon::parse($row->created_at)->format('d.m.Y')}}</div>
                                <div class="blogs-previews__views">
                                    <div class="ico-eye"></div>
                                    <div class="blogs-previews__views-count">{{$row->views_count}}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="news-row active col-12">
                            <h2>Новостей нет.</h2>
                        </div>
                    @endforelse
                    </div>
                </div>
            </div>
            <div class="col-12">
                {{$news->appends(['author_user_id' => request()->author_user_id])->links('vendor.pagination.custom-front')}}
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