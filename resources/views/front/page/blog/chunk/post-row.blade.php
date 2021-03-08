<div class="blog-row">
    <h2><a href="{{route('front.page.post', ['permission' => $post->user->permission, 'blog_id' => $post->blog->id, 'post_id' => $post->id])}}" class="li-link-blue">{{$post->title}}</a></h2>
    <div class="blog-row__content">
        <p>{!! preg_replace("/\r\n|\r|\n/",'<br/>',$post->announce) !!}</p>
    </div>
    <footer class="blog-row__footer">
        <div class="blog-row__stat">
            <div class="blog-rating"><span>Рейтинг читателей</span>
                <div class="blog-rating__handler">
                    <a href="javascript:;" data-id="{{$post->id}}" class="sp-icon ico-blog-like"></a>
                    <span id="rating_{{$post->id}}" class="blog-rating__likes-count">{{$post->getRating()}}</span>
                    <a href="javascript:;" data-id="{{$post->id}}" class="sp-icon ico-blog-dislike"></a>
                </div>
            </div>
            <div class="blogs-previews__item-info">
                <div class="blogs-previews__views">
                    <div class="ico-eye"></div>
                    <div class="blogs-previews__views-count">{{$post->count_view ?? ''}}</div>
                </div>
                <div class="blogs-previews__comments">
                    <div class="ico-quote-mini"></div>
                    <div class="blogs-previews__views-count">{{$post->count_comments ?? '0'}}</div>
                </div>
            </div>
            <div class="blog-row__date">{{\Carbon\Carbon::parse($post->created_at)->isoFormat("DD MMMM YYYY")}}</div>
        </div>
        @if (!$post->tags->isEmpty())
            <div class="blog-row__tags"><span>Теги:</span>
                @foreach($post->tags as $tag)
                    <div class="blog-row__tags-content">
                        <a href="{{route('front.page.posts.tag', ['name' => $tag->name])}}">{{$tag->name}}</a>
                    </div>
                @endforeach
            </div>
        @endif
    </footer>
</div>