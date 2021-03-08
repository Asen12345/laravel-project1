<div id="li-enter-popup" class="li-popup li-popup-enter mfp-hide">
    <header class="li-popup__header">АВТОРИЗАЦИЯ</header>
    <div class="li-popup__body">
        <form action="{{route('user.login')}}" class="li-form" method="post">
            @csrf
            <label class="li-form-label"><span class="li-form-label-name">E-mail:</span>
                <input type="text" name="email" class="li-form-input">
            </label>
            <label class="li-form-label"><span class="li-form-label-name">Пароль:</span>
                <input type="password" name="password" class="li-form-input">
            </label>
            <div class="li-form__reg">
                <a href="#login_forgot_popup" class="to-popup">Забыли пароль</a>
                <a href="{{route('front.register')}}">Подать заявку на регистрацию</a></div>
            <div class="li-form__socials">
                <p>Войти через социальную сеть (для комментариев)</p>
                <div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,country,last_name,email,photo_big;providers=facebook,vkontakte,twitter,linkedin,odnoklassniki,mailru;hidden=;redirect_uri=;mobilebuttons=0;callback=uloginCallback"></div>
            </div>
            <div class="li-form__buttons">
                <button type="submit" class="button button-mini button-l-blue">ОТПРАВИТЬ</button>
                <button type="button" onclick="$.magnificPopup.close();" title="Close (Esc)" class="button button-mini button-l-blue">ОТМЕНА</button>
            </div>
        </form>
    </div>
</div>