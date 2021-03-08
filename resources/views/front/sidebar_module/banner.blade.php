<div class="sidebar-multi-wrap">
    <div class="sidebar-banner">
        @foreach ($bannersSideBar as $banner)
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
</div>