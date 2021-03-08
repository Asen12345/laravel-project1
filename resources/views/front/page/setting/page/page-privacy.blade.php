@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <form name="form" action="{{route('front.setting.account.update', ['page' => 'privacy'])}}" method="post" class="li-form">
        @csrf
        <div class="blog-post__body">
            <div class="form-group row">
                <label for="about_show" class="col-md-4 col-form-label text-md-right">Описание видно</label>
                <div class="col-md-6">
                    <select id="about_show" name="privacy[about_me_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->about_me_show) ? ($user->privacy->about_me_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->about_me_show) ? ($user->privacy->about_me_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->about_me_show) ? ($user->privacy->about_me_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->about_me_show) ? ($user->privacy->about_me_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            @if ($user->permission == 'expert')
            <div class="form-group row">
                <label for="phone_mobile_show" class="col-md-4 col-form-label text-md-right">Мобильный телефон виден</label>
                <div class="col-md-6">
                    <select id="phone_mobile_show" name="privacy[mobile_phone_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->mobile_phone_show) ? ($user->privacy->mobile_phone_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->mobile_phone_show) ? ($user->privacy->mobile_phone_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->mobile_phone_show) ? ($user->privacy->mobile_phone_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->mobile_phone_show) ? ($user->privacy->mobile_phone_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            @endif
            @if ($user->permission == 'company')
            <div class="form-group row">
                <label for="address_show" class="col-md-4 col-form-label text-md-right">Адрес</label>
                <div class="col-md-6">
                    <select id="address_show" name="privacy[address_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->address_show) ? ($user->privacy->address_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->address_show) ? ($user->privacy->address_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->address_show) ? ($user->privacy->address_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->address_show) ? ($user->privacy->address_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label for="phone_work_show" class="col-md-4 col-form-label text-md-right">{{ $user->permission == 'expert' ? 'Рабочий телефон виден' : 'Телефон виден' }}</label>
                <div class="col-md-6">
                    <select id="phone_work_show" name="privacy[work_phone_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->work_phone_show) ? ($user->privacy->work_phone_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->work_phone_show) ? ($user->privacy->work_phone_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->work_phone_show) ? ($user->privacy->work_phone_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->work_phone_show) ? ($user->privacy->work_phone_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="skype_show" class="col-md-4 col-form-label text-md-right">Skype виден</label>
                <div class="col-md-6">
                    <select id="skype_show" name="privacy[skype_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->skype_show) ? ($user->privacy->skype_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->skype_show) ? ($user->privacy->skype_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->skype_show) ? ($user->privacy->skype_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->skype_show) ? ($user->privacy->skype_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="site_show" class="col-md-4 col-form-label text-md-right">Сайт виден</label>
                <div class="col-md-6">
                    <select id="site_show" name="privacy[web_site_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->web_site_show) ? ($user->privacy->web_site_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->web_site_show) ? ($user->privacy->web_site_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->web_site_show) ? ($user->privacy->web_site_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->web_site_show) ? ($user->privacy->web_site_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="email_work_show" class="col-md-4 col-form-label text-md-right">{{ $user->permission == 'expert' ? 'E-mail рабочий виден' : 'Корпоративный e-mail виден' }}</label>
                <div class="col-md-6">
                    <select id="email_work_show" name="privacy[work_email_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->work_email_show) ? ($user->privacy->work_email_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->work_email_show) ? ($user->privacy->work_email_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->work_email_show) ? ($user->privacy->work_email_show == 'auth_only' ? 'selected' : '') :''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->work_email_show) ? ($user->privacy->work_email_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            @if ($user->permission == 'expert')
            <div class="form-group row">
                <label for="email_private_show" class="col-md-4 col-form-label text-md-right">Личный e-mail виден</label>
                <div class="col-md-6">
                    <select id="email_private_show" name="privacy[personal_email_show]" class="li-form-select">
                        <option value="none" {{!empty($user->privacy->personal_email_show) ? ($user->privacy->personal_email_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                        <option value="all" {{!empty($user->privacy->personal_email_show) ? ($user->privacy->personal_email_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                        <option value="auth_only" {{!empty($user->privacy->personal_email_show) ? ($user->privacy->personal_email_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                        <option value="friends" {{!empty($user->privacy->personal_email_show) ? ($user->privacy->personal_email_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                    </select>
                </div>
            </div>
            @endif
        </div>
        <div class="h-clearfix h-mt-20">
            <button type="submit" class="button button-dark-blue float-right">Отправить</button>
        </div>
    </form>
@endsection

@section('js_footer_account')
@endsection