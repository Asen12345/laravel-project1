<div id="li-message-person-popup" class="li-popup li-popup-message mfp-hide">
    <header class="li-popup__header">{{ $user->name }} - отправить сообщение</header>
    <div class="li-popup__body">
        <form id="message-popup" action="{{route('front.resource.message.send.first')}}" method="post" class="li-form">
            @csrf
            <input type="hidden" name="user_to" value="">
            <label class="li-form-label"><span class="li-form-label-name">Тема сообщения:</span>
                <input type="text" name="subject" class="li-form-input" required>
            </label>
            <label class="li-form-label"><span class="li-form-label-name">Сообщение:</span>
                <textarea name="body" cols="30" rows="10" class="li-form-area" required></textarea>
            </label>
            <div class="li-form__buttons text-center">
                <button type="submit" class="button button-mini button-l-blue">отправить</button>
            </div>
        </form>
    </div>
</div>