@extends('front.layouts.app')

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12 blog-page-container">
                    <div class="blogs-top">
                        <div class="title-block title-block--iconed">
                            <h1>{{$blog->subject}}</h1>
                            <div class="title-block__icons"><a href="{{route('front.blog.rss', ['blog_id' => $blog->id])}}" class="title-block__icon title-block__icon--rss"><i class="icon-rss"></i></a></div>
                        </div>
                        <div class="li-tabs tabs-wrapper">
                            @include('front.page.blog.chunk.blogs-sort-menu', ['sort' => request()->sort])
                            @include('front.page.blog.chunk.blogs-steps')
                            <ul class="tabs__content mnu-standart-style-reset">
                                <li id="item1" class="tabs__item">
                                    @include('front.page.blog.chunk.blog-card', ['blog' => $blog])
                                    @if (!$bannersBlog->isEmpty())
                                        @include('front.banners.blog-top')
                                    @endif
                                    <div class="blog-rows">
                                        @foreach($posts as $post)
                                            @include('front.page.blog.chunk.post-row', ['post' => $post])
                                        @endforeach
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    {{$posts->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>
    @include('front.partials.subscriber-blog-popup')
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