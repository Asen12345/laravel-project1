<div class="blog-post-discussion">
    <div class="blog-post-discussion__header">
        <div class="blog-post-discussion__title">Обсуждения</div>
        @if (auth()->check())
            <a href="#" class="button button-micro button-dark-blue discussion-form-toggle scroll-to-comments-form">комментировать</a>
        @else
            <a href="#li-enter-popup" class="button button-micro button-dark-blue to-popup">Авторизоваться</a>
        @endif

    </div>
    <div class="blog-post-discussion__body">
        @forelse($comments as $comment)
        <div class="blog-post-discussion__row">
            <div class="li-blog-avatar">
                @if ($comment->anonym == true)
                    <div class="li-blog-avatar__img ico-avatar-default"></div>
                @else
                    <div class="li-blog-avatar__img">
                        <img src="{{$comment->socialProfileUser->image ?? '/img/no_picture.jpg'}}" alt="Аватар">
                    </div>
                @endif
            </div>
            <div class="blog-post-discussion__content">
                <div class="blog-post-discussion__info">
                    <div class="blog-row__date">
                        {{\Carbon\Carbon::parse($comment->created_at)->isoFormat("DD MMMM YYYY")}}<span class="blog-post-time">{{\Carbon\Carbon::parse($comment->created_at)->isoFormat("H:mm")}}</span>
                    </div>
                </div>
                <div class="blog-post-discussion__text">
						@if ($comment->user->permission == 'social')
                            <a target="_blank" href="{{ url($comment->socialProfileUser->web_site) ?? '#'}}">{{ $comment->user->name }}</a>
                        @else
                            <a href="{{ route('front.page.people.user', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
                        @endif
                    <p>{{$comment->text}}</p>
                </div>
            </div>
        </div>
        @empty
            <div class="blog-post-discussion__row">
                Комментарии отсутствуют
            </div>
        @endforelse
        @if (!empty($comments))
            <div>
                {{$comments->links('vendor.pagination.custom-front')}}
            </div>
        @endif
    </div>
</div>
@if (auth()->check())
<form action="{{route('front.page.post.discussion.send', ['post_id' => $post->id])}}" id="post-page-form" class="discussion-form li-form hidden" method="post">
    @csrf
    <label class="li-form-label">
        <span class="li-form-label-name">
            <span class="li-form-required">*</span> Текст комментария</span>
        <textarea name="text" cols="30" rows="10" class="li-form-area" required></textarea>
    </label>
    <div class="row">
        <div class="col-12">
            <label class="li-form-label companies-form__name">
                <input type="checkbox" name="anonym">
                <span class="li-form-label-name d-inline">Оставить комментарий анонимно</span>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="li-form-label companies-form__name">
                <input type="checkbox" name="notify_new_comment" {{!empty($user_setting->notify) ?? $user_setting->notify == true ? 'checked' : ''}}>
                <span class="li-form-label-name d-inline">Уведомлять о новых комментариях к записи</span>
            </label>
        </div>
    </div>
    <div class="li-form__buttons">
        <button type="submit" class="button button-mini button-l-blue">ОТПРАВИТЬ</button>
    </div>
</form>
@else
    <div class="discussion-form__top h-mt-20 text-center">
        <p>Для того чтобы оставлять комментарии вам нужно авторизоваться</p>
        <a href="#li-enter-popup" class="button button-micro button-dark-blue to-popup">Авторизоваться</a>
    </div>
@endif