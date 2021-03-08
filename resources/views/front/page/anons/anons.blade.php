@extends('front.layouts.app')

@section('content')

<div class="col-xl-9 col-lg-8 main-content">
    <div class="container-fluid">
        <div class="row m-minus">
            <div class="col-12">
                <div class="news-content">
                    <div class="title-block title-block--iconed">
                        <h1>{{$content_page['title']}}</h1>
                        <div class="title-block__icons">
                            <a href="{{ route('front.anons.rss') }}" class="title-block__icon title-block__icon--rss"><i class="icon-rss"></i></a>
                        </div>
                    </div>
                    <div class="events-list">
                    @forelse($anons as $row)
                        <div class="event-row">
                            <div class="event-row__header">
                                <h2><a href="{{route('front.page.anons.page', ['anons_id' => $row->id])}}" class="li-link-blue">{{$row->title}}</a></h2>
                                <div class="announcements-item__date">{{\Carbon\Carbon::parse($row->date)->isoFormat("DD MMMM YYYY")}}</div>
                            </div>
                            <div class="event-row__body li-gray-body">
                                <div class="company-info__block">
                                    <div class="company-info__bage">Место проведения</div>
                                    <div class="company-info__descr">{{$row->place}}</div>
                                </div>
                                <div class="company-info__block">
                                    <div class="company-info__bage">Организатор</div>
                                    <div class="company-info__descr">{{$row->organizer}}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="news-row active col-12">
                            <h2>Мероприятий нет.</h2>
                        </div>
                    @endforelse
                    </div>
                </div>
            </div>
            <div class="col-12">
                {{$anons->links('vendor.pagination.custom-front')}}
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