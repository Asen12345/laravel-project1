<div id="li-popup-blog-sbscr_{{ $row->id }}" class="li-popup li-popup-blog-sbscr mfp-hide">
    <header class="li-popup__header">Подписаться на исследования автора:<br /> {{$row->title}}</header>
    <div class="li-popup__body">
        <form action="{{ route('front.page.shop.researches.author.subscribe.form', ['id' => $row->id]) }}" method="post" class="li-form">
            @csrf
            <label class="li-form-label"><span class="li-form-label-name">E-mail:</span>
                <input type="text" name="email" class="li-form-input">
            </label>
            <div class="li-form__buttons text-right">
                <button class="button button-mini button-l-blue">OK</button>
                <button class="button button-mini button-l-blue">ОТМЕНА</button>
            </div>
        </form>
    </div>
</div>