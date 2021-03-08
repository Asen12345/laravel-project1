@extends('front.layouts.app')

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
            <div class="container-fluid">
                <div class="row m-minus">
                    <div class="col-12 blog-page-container">
                        <header class="blog-post-header">
                            <div class="blog-post-header__top">

                                <div class="blog-row__date">{{\Carbon\Carbon::parse($anons->date)->isoFormat("DD MMMM YYYY")}}</div>
                                <div class="blog-share"><span class="blog-share__title">поделитесь с друзьями</span>
                                    <script>
                                        (function() {
                                            if (window.pluso)if (typeof window.pluso.start == "function") return;
                                            if (window.ifpluso==undefined) { window.ifpluso = 1;
                                                var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                                s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                                                s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                                                var h=d[g]('body')[0];
                                                h.appendChild(s);
                                            }})();

                                    </script>
                                    <div data-background="none;" data-options="small,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,facebook,odnoklassniki,twitter,linkedin" class="pluso"></div>
                                </div>
                            </div>
                            <h1>{{$anons->title}}</h1>

                            <div class="event-row">
                                <div class="event-row__body li-gray-body">
                                    <div class="company-info__block">
                                        <div class="company-info__bage">Место проведения</div>
                                        <div class="company-info__descr">{{$anons->place}}</div>
                                    </div>
                                    <div class="company-info__block">
                                        <div class="company-info__bage">Организатор</div>
                                        <div class="company-info__descr">{{$anons->organizer}}</div>
                                    </div>
                                    <div class="company-info__block">
                                        <div class="company-info__bage">Стоимость</div>
                                        <div class="company-info__descr">{{$anons->price}}</div>
                                    </div>
                                    <div class="company-info__block--with-button">
                                        <a href="{{$anons->reg_linc}}" class="button button-micro button-dark-blue float-right">Регистрация</a>
                                    </div>
                                </div>
                            </div>
                            
                        </header>
                        @if (!$bannersAnnounce->isEmpty())
                        @if ($bannersAnnounce->count() > 2)
                            <div class="li-banners-wrapper li-banners-wrapper--double li-banners-wrapper--tripple li-banners-wrapper--padded">
                                @foreach($bannersAnnounce as $banner)
                                    {{--Views Counter banner--}}
                                    @php(\ViewsCount::process($banner))
                                    <div class="li-banner-wrap">
                                        @php($type = pathinfo($banner->image, PATHINFO_EXTENSION))
                                        @if ($type == 'swf')
                                            @if (!empty($banner->link))
                                                <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                                    <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                                    </object>
                                                </a>
                                            @else
                                                <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                                </object>
                                            @endif
                                        @else
                                            @if (!empty($banner->link))
                                                <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                                    <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                                </a>
                                            @else
                                                <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif ($bannersAnnounce->count() == 2 )
                            <div class="li-banners-wrapper li-banners-wrapper--double">
                                @foreach($bannersAnnounce as $banner)
                                    {{--Views Counter banner--}}
                                    @php(\ViewsCount::process($banner))
                                    <div class="li-banner-wrap">
                                        @php($type = pathinfo($banner->image, PATHINFO_EXTENSION))
                                        @if ($type == 'swf')
                                            @if (!empty($banner->link))
                                                <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                                    <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                                    </object>
                                                </a>
                                            @else
                                                <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                                </object>
                                            @endif
                                        @else
                                            @if (!empty($banner->link))
                                                <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                                    <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                                </a>
                                            @else
                                                <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="blogs-previews__banner">
                                @foreach ($bannersAnnounce as $banner)
                                    {{--Views Counter banner--}}
                                    @php(\ViewsCount::process($banner))
                                    @php($type = pathinfo($banner->image, PATHINFO_EXTENSION))
                                    @if ($type == 'swf')
                                        @if (!empty($banner->link))
                                            <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                                <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                                </object>
                                            </a>
                                        @else
                                            <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                            </object>
                                        @endif
                                    @else
                                        @if (!empty($banner->link))
                                            <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                                <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                            </a>
                                        @else
                                            <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @endif

                        <div class="blog-post__body">
                            {!!$anons->text!!}
                        </div>

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