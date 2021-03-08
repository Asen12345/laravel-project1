<header class="blog-post-header">
    <div class="blog-post-header__top">
        <div class="blog-row__date">{{\Carbon\Carbon::parse($post->created_at)->isoFormat("DD MMMM YYYY")}}<span class="blog-post-time">{{\Carbon\Carbon::parse($post->created_at)->isoFormat("H:mm")}}</span></div>
        <div class="blog-share"><span class="blog-share__title">поделитесь с друзьями</span>
            <script type="text/javascript">(function() {
                    if (window.pluso)if (typeof window.pluso.start == "function") return;
                    if (window.ifpluso==undefined) { window.ifpluso = 1;
                        var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                        s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                        s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                        var h=d[g]('body')[0];
                        h.appendChild(s);
                    }})();</script>
            <div data-background="none;" data-options="small,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,facebook,odnoklassniki,twitter,linkedin" class="pluso"></div>
        </div>
    </div>
    <h1>{{$post->title}}</h1>
    <div class="blog-post-header__bottom">
        <div class="blog-row__stat">
            <div class="blog-rating"><span>Рейтинг читателей</span>
                <div class="blog-rating__handler">
                    <a href="javascript:;" data-id="{{$post->id}}" class="sp-icon ico-blog-like"></a>
                    <span id="rating_{{$post->id}}" class="blog-rating__likes-count">{{$post->rate}}</span>
                    <a href="javascript:;" data-id="{{$post->id}}" class="sp-icon ico-blog-dislike"></a>
                </div>
            </div>
            <div class="blogs-previews__item-info">
                <div class="blogs-previews__views">
                    <div class="ico-eye"></div>
                    <div class="blogs-previews__views-count">{{$post->count_view ?? '0'}}</div>
                </div>
                <div class="blogs-previews__comments">
                    <div class="ico-quote-mini"></div>
                    <div class="blogs-previews__views-count">{{ $post['count_comments'] }}</div>
                </div>
            </div>
        </div><a href="#" class="button button-micro button-dark-blue scroll-to-comments">комментарии <span>({{$post['count_comments'] }})</span></a>
    </div>
</header>
<div class="blog-post__body">
    {!! $post->text !!}
    @if (!$post->tags->isEmpty())
        <div class="blog-row__tags">
            <span>Теги:</span>
            @foreach($post->tags as $tag)
                <div class="blog-row__tags-content">
                    <a href="{{route('front.page.posts.tag', ['name' => $tag->name])}}">{{$tag->name}}</a>
                </div>
            @endforeach
        </div>
    @endif
</div>


