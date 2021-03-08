@extends('front.layouts.app')

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="title-block">
                        <h1>Исследования рынка @if(!empty($categoryName)) - {{ $categoryName }}@endif</h1>
                    </div>
                    <div class="researhes-list">
                        @if (!empty($researches))
                        @foreach($researches as $row)
                        <div class="research-item li-card-block"><a href="{{route('front.page.shop.researches.category.entry', ['id' => $row->id])}}" class="research-item__img"><img src="{{url($row->image ?? '/img/no_picture.jpg')}}" alt="{{ $row->title }}"></a>
                            <div class="research-item__header">
                                <div class="height-covered">

                                    <h2><a href="{{route('front.page.shop.researches.category.entry', ['id' => $row->id])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{ $row->title }}</a></h2>

                                    <div class="height-cover"></div>
                                </div>
                            </div>
                            <div class="research-item__body">
                                <div class="research-item__price li-person-name">{{ $row->price ? $row->price . ' руб' : 'бесплатно' }}</div>
                                <div class="research-item__bottom li-gray-body">
                                    @if (!empty($row->author))
                                        <div class="company-info__block">
                                            <div class="company-info__bage">Автор</div><a href="{{ route('front.page.shop.author', ['id' => $row->author->id]) }}" class="li-link">{{ $row->author->title }}</a>
                                        </div>
                                    @endif
                                    @if (!empty($row->published_at))
                                        <div class="company-info__block">
                                            <div class="company-info__bage">Дата выхода</div>
                                            <div class="company-info__descr">{{ \Carbon\Carbon::parse($row->published_at)->isoFormat('DD MMMM YYYY') }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                            Исследований нет
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    {{$researches->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar-right')
    @include('front.sidebar_module.shop-category-menu-block')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection