@extends('front.layouts.app')

@section('content')

<div class="col-xl-9 col-lg-8 main-content">
    <div class="container-fluid">
        <div class="row m-minus">
            <div class="col-12">
                <div class="news-content">
                    <div class="title-block title-block--iconed">
                        <h1>{{$content_page['title']}}</h1>
                    </div>
                    <div class="topics-content">
                    @forelse($topics as $row)
                        @php
                            $now = \Carbon\Carbon::now()->format('Y');
                            $year = \Carbon\Carbon::parse($row->published_at)->format('Y');
                            $next_year = \Carbon\Carbon::parse($topics[$loop->index + 1]['published_at'])->format('Y');
                        @endphp
                            @if ($loop->first && $now != $year)
                                <div class="li-date-line"><span>{{$year}} г.</span></div>
                            @endif
                            <div class="topics-block li-card-block">
                                <div class="topics-block__header">
                                    <div class="announcements-item__date announcements-item__date--vert">
                                        <div>{{\Carbon\Carbon::parse($row->published_at)->isoFormat("DD MMMM")}}</div>
                                    </div>
                                    <h2><a href="{{route('front.page.topic.page' , ['url_en' => $row->url_en])}}" class="li-link-blue">{{$row->title}}</a></h2>
                                </div>
                                <div class="topics-block__slider">
                                    <div class="day-theme__slider day-theme__slider--short">

                                        @forelse($row->answers as $record)
                                            <div class="day-theme__slide">
                                                <div class="li-portrait"><img src="{{$record->user->socialProfile->image}}" alt="alt"></div>
                                                <div class="day-theme__slide-text">
                                                    <div class="li-person-name">{{$record->user->name}}</div>
                                                    @if (!empty($record->socialProfile->company_post))
                                                        <div class="li-person-name">
                                                            {{ $record->socialProfile->company_post }}
                                                        </div>
                                                    @endif
                                                    <div class="li-quote height-covered">{!!$record->text!!}
                                                        <div class="height-cover height-cover--gray"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty

                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @if (!empty($next_year) && $year !== $next_year && !$loop->last)
                            <div class="li-date-line"><span>{{$next_year}} г.</span></div>
                        @endif

                    @empty
                        <div class="news-row active col-12">
                            <h2>Тем нет.</h2>
                        </div>
                    @endforelse
                    </div>
                </div>
            </div>
            <div class="col-12">
                {{$topics->links('vendor.pagination.custom-front')}}
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