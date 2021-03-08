<div class="blog-card">
    <div class="li-portrait">
        <a class="blog-card__title li-link-blue" href="{{route('front.page.people.user', ['id' => $blog->user->id])}}"><img src="{{$blog->user_social ?? '/img/no_picture.jpg'}}" alt="{{$blog->user->name}}"></a>
    </div>
    <div class="blog-card__text">
        <header class="blog-card__header">
            <div class="blog-card__header-left">
                <a class="blog-card__title li-link-blue" href="{{route('front.page.people.user', ['id' => $blog->user->id])}}">{{$blog->user->name}}</a>

                @if(request()->route()->getName() == 'front.page.blog')
                    <p class="blog-card__descr">{{$blog->subject}}</p>
                @else
                    <a href="{{route('front.page.blog', ['permission' => $blog->user->permission, 'blog_id' => $blog->id])}}" class="blog-card__descr li-link-blue">{{$blog->subject}}</a>
                @endif
            </div>
            <div class="blog-card__header-right">
                <div class="blogs-previews__item-info">
                    <div class="blogs-previews__views">
                        <div class="ico-eye"></div>
                        <div class="blogs-previews__views-count">{{$blog->count_view ?? '0'}}</div>
                    </div>
                    <div class="blogs-previews__comments">
                        <div class="ico-quote-mini"></div>
                        <div class="blogs-previews__views-count">{{$blog->count_comments ?? '0'}}</div>
                    </div>
                </div>
                @if (auth()->check())
                    @if ($blog->subscribe == false)
                        <a href="javascript:;" data-id="{{$blog->id}}" data-type="subscribe" class="button button-micro button-dark-blue subscribe">подписаться на блог</a>
                    @else
                        <a href="javascript:;" data-id="{{$blog->id}}" data-type="unsubscribe" class="button button-micro button-dark-red subscribe">отписаться</a>
                    @endif
                @else
                    <a href="#li-popup-blog-sbscr-{{ $blog->id }}" class="button button-micro button-dark-blue to-popup">подписаться на блог</a>
                @endif
                <a href="{{route('front.blog.rss', ['blog_id' => $blog->id])}}" class="blog-card__header-icon header-rss"><i class="icon-rss"></i></a>
            </div>
        </header>
        <div class="blog-card__info-hidden">
            <div class="blogs-previews__item-info">
                <div class="blogs-previews__views">
                    <div class="ico-eye"></div>
                    <div class="blogs-previews__views-count">{{$blog->count_view ?? '0'}}</div>
                </div>
                <div class="blogs-previews__comments">
                    <div class="ico-quote-mini"></div><div class="blogs-previews__views-count">{{$blog->count_comments}}</div>
                </div>
            </div>
            @if (auth()->check())
                @if ($blog->subscribe == false)
                    <a href="javascript:;" data-id="{{$blog->id}}" data-type="subscribe" class="button button-micro button-dark-blue subscribe">подписаться на блог</a>
                @else
                    <a href="javascript:;" data-id="{{$blog->id}}" data-type="unsubscribe" class="button button-micro button-dark-red subscribe">отписаться</a>
                @endif
            @else
                <a href="#li-popup-blog-sbscr" class="button button-micro button-dark-blue to-popup">подписаться на блог</a>
            @endif
            <a href="{{route('front.blog.rss', ['blog_id' => $blog->id])}}" class="blog-card__header-icon header-rss"><i class="icon-rss"></i></a>
        </div>
        <div class="blog-card__body">
            <div class="block-card__stats">
                <div class="block-card__stat">
                    <div>Сообщения</div>
                    <div class="block-card__stat-count">{{$blog->active_count_blog}}</div>
                </div>
                <div class="block-card__stat">
                    <div>Подписчиков</div>
                    <div class="block-card__stat-count">{{$blog->subscribers->count()}}</div>
                </div>
            </div>
                <a href="{{route('front.page.post', ['permission' => $blog->last_post->user->permission, 'blog_id' => $blog->last_post->blog->id, 'post_id' => $blog->last_post->id])}}" class="blog-card__content height-covered">
                    <span class="blog-new-icon">new</span>{{ $blog->last_post->title ?? '' }}
                    <div class="height-cover height-cover--gray"></div>
                </a>
            <div class="blog-card__rating">
                <span>Рейтинг блога</span>
                <span class="blog-rating-count">{{$blog->votes_count?? 'Нет'}}</span>
            </div>
        </div>
    </div>
</div>
@if (!empty($blog))
    @include('front.partials.subscriber-blog-popup', ['blog' => $blog])
@endif
@section('js_footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.subscribe').on('click', function () {
                let val = $(this).data('id');
                let type = $(this).data('type');
                ajaxUpdate(type, val)
            });
            function ajaxUpdate(type = null, val = null) {
                $.ajax({
                    type: "POST",
                    url: '{{route('front.setting.account.subscriptions.update')}}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        val: val,
                        type: type,
                    },
                    success: function(data) {
                        if (type === 'unsubscribe') {
                            if (data.success) {
                                let elem = $(document).find("a[data-id='" + data.id + "'].subscribe");
                                elem.text('подписаться на блог')
                                .data('type', 'subscribe')
                                .toggleClass('button-dark-red button-dark-blue');
                                alert('Вы успешно отписались.')
                            } else {
                                alert('Не удалось отписаться.')
                            }
                        }
                        if (type === 'subscribe') {
                            if (data.success) {
                                let elem = $(document).find("a[data-id='" + data.id + "'].subscribe");
                                elem.text('отписаться')
                                    .data('type', 'unsubscribe')
                                .toggleClass('button-dark-blue button-dark-red');
                                alert('Вы успешно подписались.')
                            } else {
                                alert('Не удалось подписаться.')
                            }
                        }

                        if (type === 'notifications_subscribed' || type === 'invitations'){
                            if (data.success) {
                                alert('Настройки сохранены.')
                            } else {
                                alert('Не удалось сохранить настройки.')
                            }
                        }

                    }
                });
            }
            $('.blog-rating__handler .sp-icon').on('click', function (e) {
                e.preventDefault();
                let cl = $(this).attr('class');
                let type_cl = cl.split(' ')[1];
                let post_id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('front.post.votes') }}",
                    data: {
                        _token:"{{ csrf_token() }}",
                        type: type_cl,
                        post_id: post_id
                    },
                    success: function(data) {
                        if (data['success']) {
                            alert(data['success'])
                        }
                        if (data['error']) {
                            alert(data['error'])
                        }
                        if (data['total']) {
                            $('#rating_'+post_id).text(data['total'])
                        }
                    },
                })
            });

            $('.discussion-form').validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
            });
        })
    </script>
@endsection