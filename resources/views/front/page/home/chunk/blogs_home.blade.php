<div class="blogs-previews">
    <div class="title-block title-block--iconed">
        <div class="h1-no-title">{{ $type }}</div>
        <div class="title-block__icons"><a href="{{ route('front.blogs.type.rss', ['type' => $type_rss]) }}" class="title-block__icon title-block__icon--rss"><i class="icon-rss"></i></a></div>
    </div>
    <div class="li-tabs tabs-wrapper">
        <ul class="tabs__list mnu-standart-style-reset">
            <li class="tabs__item tabs__item--active"><a href="#" data-tab="1" class="tabs__link">Актуальные</a></li>
            <li class="tabs__item"><a href="#" data-tab="2" class="tabs__link">Новые</a></li>
            <li class="tabs__item"><a href="#" data-tab="3" class="tabs__link">Читаемые</a></li>
            <li class="tabs__item"><a href="#" data-tab="4" class="tabs__link">Обсуждаемые</a></li>
        </ul>
        <ul class="tabs__content mnu-standart-style-reset">
            <li data-tab="1" class="tabs__item">
                <div class="personal-blogs__content">
                    @forelse($blogs['actual'] as $blog)
                        @if (!empty($blog->last_post))
                            <div class="blogs-previews__item col-sm-6 col-md-4 col-lg-4">
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-portrait">
                                    <img src="{{$blog->user->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="{{$blog->user->name}}"></a>
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-person-name">{{ $blog->user->name }}</a>
                                <div class="blogs-previews__item-info">
                                    <div class="blogs-previews__views">
                                        <div class="ico-eye"></div>
                                        <div class="blogs-previews__views-count">{{ $blog->blogViewsCount() }}</div>
                                    </div>
                                    <div class="blogs-previews__comments">
                                        <div class="ico-quote-mini"></div>
                                        <div class="blogs-previews__comments-conunt">{{ $blog->count_comments }}</div>
                                    </div>
                                </div><a href="{{route('front.page.post', ['permission' => $blog->last_post->user->permission, 'blog_id' => $blog->last_post->blog->id, 'post_id' => $blog->last_post->id])}}" class="blogs-previews__item-text li-paragraph-blue">{{ $blog->last_post->title ?? '' }}</a>
                            </div>
                        @endif
                    @empty
                    @endforelse

                </div>
            </li>
            <li data-tab="2" class="tabs__item hidden">
                <div class="personal-blogs__content">
                    @forelse($blogs['newest'] as $blog)
                        @if (!empty($blog->last_post))
                            <div class="blogs-previews__item col-sm-6 col-md-4 col-lg-4">
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-portrait">
                                    <img src="{{$blog->user->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="{{$blog->user->name}}"></a>
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-person-name">{{$blog->user->name}}</a>
                                <div class="blogs-previews__item-info">
                                    <div class="blogs-previews__views">
                                        <div class="ico-eye"></div>
                                        <div class="blogs-previews__views-count">{{ $blog->blogViewsCount() }}</div>
                                    </div>
                                    <div class="blogs-previews__comments">
                                        <div class="ico-quote-mini"></div>
                                        <div class="blogs-previews__comments-conunt">{{$blog->count_comments}}</div>
                                    </div>
                                </div><a href="{{route('front.page.post', ['permission' => $blog->last_post->user->permission, 'blog_id' => $blog->last_post->blog->id, 'post_id' => $blog->last_post->id])}}" class="blogs-previews__item-text li-paragraph-blue">{{$blog->last_post->title ?? ''}}</a>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>
            </li>
            <li data-tab="3" class="tabs__item hidden">
                <div class="personal-blogs__content">
                    @forelse($blogs['popular'] as $blog)
                        @if (!empty($blog->last_post))
                            <div class="blogs-previews__item col-sm-6 col-md-4 col-lg-4">
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-portrait">
                                    <img src="{{$blog->user->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="{{$blog->user->name}}"></a>
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-person-name">{{$blog->user->name}}</a>
                                <div class="blogs-previews__item-info">
                                    <div class="blogs-previews__views">
                                        <div class="ico-eye"></div>
                                        <div class="blogs-previews__views-count">{{ $blog->blogViewsCount() }}</div>
                                    </div>
                                    <div class="blogs-previews__comments">
                                        <div class="ico-quote-mini"></div>
                                        <div class="blogs-previews__comments-conunt">{{$blog->count_comments}}</div>
                                    </div>
                                </div><a href="{{route('front.page.post', ['permission' => $blog->last_post->user->permission, 'blog_id' => $blog->last_post->blog->id, 'post_id' => $blog->last_post->id])}}" class="blogs-previews__item-text li-paragraph-blue">{{$blog->last_post->title ?? ''}}</a>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>
            </li>
            <li data-tab="4" class="tabs__item hidden">
                <div class="personal-blogs__content">
                    @forelse($blogs['discussed'] as $blog)
                        @if (!empty($blog->last_post))
                            <div class="blogs-previews__item col-sm-6 col-md-4 col-lg-4">
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-portrait">
                                    <img src="{{$blog->user->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="{{$blog->user->name}}"></a>
                                <a href="{{route('front.page.people.user', ['id' => $blog->user->id])}}" class="li-person-name">{{$blog->user->name}}</a>
                                <div class="blogs-previews__item-info">
                                    <div class="blogs-previews__views">
                                        <div class="ico-eye"></div>
                                        <div class="blogs-previews__views-count">{{ $blog->blogViewsCount() }}</div>
                                    </div>
                                    <div class="blogs-previews__comments">
                                        <div class="ico-quote-mini"></div>
                                        <div class="blogs-previews__comments-conunt">{{$blog->count_comments}}</div>
                                    </div>
                                </div><a href="{{route('front.page.post', ['permission' => $blog->last_post->user->permission, 'blog_id' => $blog->last_post->blog->id, 'post_id' => $blog->last_post->id])}}" class="blogs-previews__item-text li-paragraph-blue">{{$blog->last_post->title ?? ''}}</a>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>
            </li>
        </ul>
    </div>
    @if($type_rss == 'expert')
        <div class="personal-blogs__bottom more-container text-right"><a href="{{ route('front.page.blogs.all', ['sort' => 'expert']) }}" class="li-red-link">Все блоги</a></div>
    @else
        <div class="personal-blogs__bottom more-container text-right"><a href="{{ route('front.page.blogs.all', ['sort' => 'company']) }}" class="li-red-link">Все блоги</a></div>
    @endif
    {{--n1 было здесь--}}
</div>
{{--n1 перенес сюда start--}}
@if (!empty($banners))
    @if ($banners->count() > 1)
        <div class="li-banners-wrapper li-banners-wrapper--double">
            @foreach($banners as $banner)
                <div class="li-banner-wrap li-banner-wrap count_{{$banners->count()}}">
                    {{--Views Counter banner--}}
                    @php(\ViewsCount::process($banner))
                    @php($type = pathinfo($banner->image, PATHINFO_EXTENSION))
                    @if ($type == 'swf')
                        @if (!empty($banner->link))
                            <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                <object style="height: 61px;" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                </object>
                            </a>
                        @else
                            <object style="height: 61px;" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                            </object>
                        @endif
                    @else
                        @if (!empty($banner->link))
                            <div class="li-banner-wrap">
                                <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                    <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                </a>
                            </div>
                        @else
                            <div class="li-banner-wrap">
                                <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="blogs-previews__banner">
            @foreach ($banners as $banner)
                <div class="li-banners-wrapper">
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
                </div>
            @endforeach
        </div>
    @endif
@endif
{{--n1 перенес сюда end--}}