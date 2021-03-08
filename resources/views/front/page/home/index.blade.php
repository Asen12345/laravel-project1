@extends('front.layouts.app')

@section('content')

<div class="col-xl-9 col-lg-8 main-content">
    <div class="container-fluid">
        <div class="row m-minus">
            <div class="col-xl-4 order-sm-3">
                <div class="news-block">

                    <div class="news-subscribe">
                        <div class="news-subscribe__title">Подписка на новости</div>
                        <form action="{{route('front.newsletter.subscribe')}}" method="post" class="news-subscribe__body">
                            @csrf
                            <input type="text" name="email" placeholder="user@site.com">
                            <button type="submit" class="button button-micro button-dark-blue">подписаться</button>
                        </form>
                    </div>

                    {{--Slider news--}}
                    @if(!empty($newsScroll))
                        @include('front.page.home.chunk.news_scroll_panel', ['newsScroll' => $newsScroll])
                    @endif
                </div>
            </div>
            <div class="col-12">
                <div class="day-theme">
                    {{--Day Topic Answer Slider--}}
                    @if(!empty($dayTopicAnswers))
                        @include('front.page.home.chunk.day_topic_answers_slider', ['dayTopicAnswers' => $dayTopicAnswers])
                    @endif
                </div>
            </div>
            <div class="col-xl-8">
                {{--Expert Blog Block--}}
                @if (!empty($blogs['experts']))
                    @include('front.page.home.chunk.blogs_home', ['blogs' => $blogs['experts'], 'type' => 'Личные блоги', 'banners' => $bannersHomeExpert, 'type_rss' => 'expert'])
                @endif
                {{--Company Blog Blog--}}
                @if (!empty($blogs['companies']))
                    @include('front.page.home.chunk.blogs_home', ['blogs' => $blogs['companies'], 'type' => 'Корпоративные блоги',  'banners' => $bannersHomeCompany, 'type_rss' => 'company'])
                @endif

                {{--Announces Blog--}}
                @if (!empty($announces))
                    @include('front.page.home.chunk.announce_home', ['announces' => $announces])
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
    @include('front.sidebar_module.shop-category-menu-block')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner', ['banners' => $bannersSideBar])
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection

@section('js_footer')
    <script>
        $(document).ready(function () {
            /*// subscribe form submit
            <div id="li-popup-email" class="li-popup li-popup-email mfp-hide">
                <header class="li-popup__header">Данный email уже подписан на новостную рассылку</header>
            </div>
            $('.news-subscribe__body').submit(function () {
                $.magnificPopup.open({
                    items: {
                        src: $('#li-popup-email')
                    },
                    type: 'inline'
                });

                return false;
            });*/
            // end subscribe form submit
        })
    </script>
@endsection