@extends('front.layouts.app')

@section('header_style')

@endsection

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
            <div class="container-fluid">
                <div class="row m-minus">
                    <div class="col-12 blog-page-container">
                        <header class="blog-post-header">
                            <div class="blog-post-header__top">
                                <div class="blog-row__date">
                                    <div class="blogs-previews__views">
                                    {{\Carbon\Carbon::parse($news->created_at)->isoFormat("DD MMMM YYYY")}}
                                    </div>
                                    <div class="blogs-previews__views">
                                        <div class="ico-eye"></div>
                                        <div class="blogs-previews__views-count">{{$count_view ?? '0'}}</div>
                                    </div>
                                </div>

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
                            <h1>{{$news->name}}</h1>
                        </header>

                        <div class="publication-full__filter">
                            <div class="publication-full__subject">
                                <span class="publication-full__subject-label">В сюжетах:</span>
                                <div class="publication-full__subject-list">
                                    <ul class="post-tags__list">
                                        @forelse($news->scene as $scene)
                                            <li class="post-tags__item">
                                                <a href="{{ route('front.page.news.scene', ['id' => $scene->id]) }}" class="post-tags__link" title="{{$scene->name}}">{{$scene->name}}</a>
                                            </li>
                                        @empty
                                            нет
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if (!$bannerNewsUp->isEmpty())
                        @if ($bannerNewsUp->count() > 2)
                            <div class="li-banners-wrapper li-banners-wrapper--double li-banners-wrapper--tripple li-banners-wrapper--padded">
                                @foreach($bannerNewsUp as $banner)
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
                        @elseif ($bannerNewsUp->count() == 2 )
                            <div class="li-banners-wrapper li-banners-wrapper--double">
                                @foreach($bannerNewsUp as $banner)
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
                                @foreach ($bannerNewsUp as $banner)
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
                            {!!$news->text!!}
                        </div>

                        <div class="publication-full__links">
                            @if (!empty($news->source_name))
                                <span class="publication-full__links-item">Источник: <noindex> <a href="{{$news->source_url}}" rel="nofollow" target="_blank">{{$news->source_name}}</a> </noindex>
                            </span>
                            @endif
                            <span class="publication-full__links-item">Разместил: {{$who_posted}}</span>

                            @if ($news->author_text_val)
                                <span class="publication-full__links-item">Автор: {{$news->author_text_val}}</span>
                            @endif
                        </div>
                        @if (!$bannerNewsDown->isEmpty())
                        @if ($bannerNewsDown->count() > 2)
                            <div class="li-banners-wrapper li-banners-wrapper--double li-banners-wrapper--tripple li-banners-wrapper--padded">
                                @foreach($bannerNewsDown as $banner)
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
                        @elseif ($bannerNewsDown->count() == 2 )
                            <div class="li-banners-wrapper li-banners-wrapper--double">
                                @foreach($bannerNewsDown as $banner)
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
                                @foreach ($bannerNewsDown as $banner)
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

                        @include('front.page.news.chunk.similar-news')

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