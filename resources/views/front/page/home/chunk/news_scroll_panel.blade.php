<div class="title-block title-block--iconed">
    <div class="h1-no-title">Новости</div>
    <div class="title-block__icons">
		<div class="title-block__icon title-block__icon--telegram ico-telegram-mini"></div>
        <a href="{{route('front.news.rss')}}" class="title-block__icon title-block__icon--rss"><i class="icon-rss"></i></a>
    </div>
</div>
<div class="news-items scroll-pane">
    @foreach ($newsScroll as $news)
        <div class="news-item {{$news->vip == true ? 'news-item--important' : ''}}"><div class="news-item__icon sp-icon ico-telegram-mini"></div>
            <div class="news-item__title">
				{{$news->category->name}}
            </div>
            @if ($news->category->parent_id == 0)
                {{--Url news category without child--}}
                @if(!empty($news->scene->first()->image))
                    <img src="{{ $news->scene->first()->image }}" alt="{{ $news->name }}">
                @endif
                <a class="news-item__text" href="{{route('front.page.news.category.entry', ['url_section' => $news->category->url_en, 'url_news' => $news->url_en])}}">{{ $news->name }}</a>
            @else
                {{--Url news category have child--}}
                @if(!empty($news->scene->first()->image))
                    <img src="{{ $news->scene->first()->image }}" alt="{{ $news->name }}">
                @endif
                <a class="news-item__text" href="{{route('front.page.news.sub_category.entry', ['url_section' => $news->category->parent->url_en, 'url_sub_section' => $news->category->url_en, 'url_news' => $news->url_en])}}">{{ $news->name }}</a>
            @endif
        </div>
    @endforeach
</div>
<div class="more-container text-right"><a href="{{route('front.page.news.all')}}" class="li-red-link">Все новости</a></div>