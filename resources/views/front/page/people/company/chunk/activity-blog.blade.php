<div class="company-activity">
    <div class="title-upcase-bl">Активность</div>
    <div class="li-tabs tabs-wrapper">
        <ul class="tabs__list mnu-standart-style-reset">
            <li class="tabs__item company-activity__item tabs__item--active">
                <a href="#" data-tab="1" class="tabs__link blog_posts__tab">
                    <span>Сообщений в блоге</span>
                    <span class="activity-count">{{$blog_post_count ?? '0'}}</span>
                </a>
            </li>
            <li class="tabs__item company-activity__item">
                <a href="#" data-tab="2" class="tabs__link news__tab">
                    <span>Размещенных новостей</span>
                    <span class="activity-count">{{$news_count ?? '0'}}</span>
                </a>
            </li>
            <li class="tabs__item company-activity__item">
                <a href="#" data-tab="3" class="tabs__link comments__tab">
                    <span>Оставленных комментариев</span>
                    <span class="activity-count">{{$comments_count ?? '0'}}</span>
                </a>
            </li>
        </ul>
        <ul class="tabs__content mnu-standart-style-reset">
            <li data-tab="1" class="tabs__item">
                @foreach ($blog_posts as $post)
                    <div class="company-block">
                        <h2><a href="{{route('front.page.post', ['permission' => 'company', 'blog_id' => $post->blog->id, 'post_id' => $post->id])}}" class="li-link li-link-blue">{{$post->title}}</a></h2>
                        <p class="company-block__text">{{$post->announce}}</p>
                        <div class="blog-row__date">{{\Carbon\Carbon::parse($post->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</div>
                    </div>
                @endforeach
                <div class="col-12">
                    {{$blog_posts->links('vendor.pagination.custom-front')}}
                </div>
                    <div class="company-activity__more">
                        @if (!empty($user->blog))
                            <a href="{{route('front.page.blog', ['permission' => 'expert', 'blog_id' => $user->blog->id])}}">
                                <span class="sp-icon ico-red-burger"></span>
                                <span>все сообщения</span>
                            </a>
                        @endif
                    </div>
            </li>
            <li data-tab="2" class="tabs__item hidden">
                @foreach($news as $row)
                    <div class="company-block">
                        @if ($row->category->parent_id == 0)
                            {{--Url news category without child--}}
                            <h2><a href="{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row_red' : ''}}">{{$row->name}}</a></h2>
                        @else
                            {{--Url news category have child--}}
                            <h2><a href="{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue {{$row->vip == true ? 'news-row_red' : ''}}">{{$row->name}}</a></h2>
                        @endif
                        <p class="company-block__text">{{$row->announce}}</p>
                        <div class="blog-row__date">{{\Carbon\Carbon::parse($row->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</div>
                    </div>
                @endforeach
                <div class="col-12">
                    {{$news->links('vendor.pagination.custom-front')}}
                </div>
            </li>
            <li data-tab="3" class="tabs__item hidden">
                @foreach($comments as $comment)
                    <div class="company-block">
                        <h2><a href="#" class="li-link li-link-blue">{{$comment->post->title}}</a></h2>
                        <p class="company-block__text">{{$comment->text}}</p>
                        <div class="blog-row__date">{{\Carbon\Carbon::parse($comment->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</div>
                    </div>
                @endforeach
            </li>
        </ul>
    </div>
</div>