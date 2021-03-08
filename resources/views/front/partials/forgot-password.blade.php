<div id="login_forgot_popup" class="li-popup li-popup-enter mfp-hide">
    <header class="li-popup__header">Восстановление пароля</header>
    <div class="li-popup__body">
        <form class="li-form" method="POST" action="{{route('user.login.forgot.password')}}" aria-label="Login">
            @csrf
            <p>{!! $text->before !!}</p>
            <label class="li-form-label"><span class="li-form-label-name">E-mail:</span>
                <input type="text" name="email" class="li-form-input" required>
            </label>
            <p>{!! $text->after !!} </p>
            <div class="flex">
                <button type="submit" class="button button-mini button-l-blue">ОТПРАВИТЬ</button>
            </div>
        </form>
        <span class="modal_close btn-login-forgot-close"></span>
    </div>
</div>
