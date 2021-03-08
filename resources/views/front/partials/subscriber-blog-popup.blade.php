<div id="li-popup-blog-sbscr-{{ $blog->id }}" class="li-popup li-popup-blog-sbscr mfp-hide">
    <header class="li-popup__header">Подписка на новые сообщения в блоге:<br/> {{$blog->subject}}</header>
    <div class="li-popup__body">
        <form action="{{route('front.blog.subscribe', ['blog_id' => $blog->id])}}" method="post" class="li-form">
            @csrf
            <label class="li-form-label"><span class="li-form-label-name">E-mail:</span>
                <input type="text" name="email" class="li-form-input">
            </label>
            <div class="li-form__buttons text-right">
                <button type="submit" class="button button-mini button-l-blue">OK</button>
            </div>
        </form>
    </div>
</div>