@if ($bannersBlog->count() > 2)
    <div class="li-banners-wrapper li-banners-wrapper--double li-banners-wrapper--tripple li-banners-wrapper--padded">
        @foreach($bannersBlog as $banner)
            @php(\ViewsCount::process($banner))
            <div class="li-banner-wrap li-banner-wrap count_{{$bannersBlog->count()}}">
                @php($type = pathinfo($banner->image, PATHINFO_EXTENSION))
                @if ($type == 'swf')
                    @if (!empty($banner->link))
                        <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                            <object style="min-height: 60px;" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
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
@elseif ($bannersBlog->count() == 2 )
    <div class="li-banners-wrapper li-banners-wrapper--double">
        @foreach($bannersBlog as $banner)
            {{--Views Counter banner--}}
            @php(\ViewsCount::process($banner))
            <div class="li-banner-wrap count_{{$bannersBlog->count()}}">
                @php($type = pathinfo($banner->image, PATHINFO_EXTENSION))
                @if ($type == 'swf')
                    @if (!empty($banner->link))
                        <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                            <object style="min-height: 60px;" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
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
        @foreach ($bannersBlog as $banner)
            {{--Views Counter banner--}}
            @php(\ViewsCount::process($banner))
            <div class="li-banner-wrap count_{{$bannersBlog->count()}}">
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
@endif