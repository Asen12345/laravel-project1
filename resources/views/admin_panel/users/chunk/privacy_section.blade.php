<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Приватность</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group row">
            <label for="about_show" class="col-md-4 col-form-label text-md-right">Описание видно</label>
            <div class="col-md-6">
                <select autocomplete="off" id="about_show" name="privacy[about_me_show]" class="form-control">
                    <option value="none" {{!empty($privacy->about_me_show) ? ($privacy->about_me_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->about_me_show) ? ($privacy->about_me_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->about_me_show) ? ($privacy->about_me_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->about_me_show) ? ($privacy->about_me_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        @if ($user->permission == 'expert')
        <div class="form-group row">
            <label for="phone_mobile_show" class="col-md-4 col-form-label text-md-right">Мобильный телефон виден</label>
            <div class="col-md-6">
                <select autocomplete="off" id="phone_mobile_show" name="privacy[mobile_phone_show]" class="form-control">
                    <option value="none" {{!empty($privacy->mobile_phone_show) ? ($privacy->mobile_phone_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->mobile_phone_show) ? ($privacy->mobile_phone_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->mobile_phone_show) ? ($privacy->mobile_phone_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->mobile_phone_show) ? ($privacy->mobile_phone_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        @endif
        @if ($user->permission == 'company')
        <div class="form-group row">
            <label for="address_show" class="col-md-4 col-form-label text-md-right">Адрес</label>
            <div class="col-md-6">
                <select autocomplete="off" id="address_show" name="privacy[address_show]" class="form-control">
                    <option value="none" {{!empty($privacy->address_show) ? ($privacy->address_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->address_show) ? ($privacy->address_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->address_show) ? ($privacy->address_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->address_show) ? ($privacy->address_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label for="phone_work_show" class="col-md-4 col-form-label text-md-right">Рабочий телефон виден</label>
            <div class="col-md-6">
                <select autocomplete="off" id="phone_work_show" name="privacy[work_phone_show]" class="form-control">
                    <option value="none" {{!empty($privacy->work_phone_show) ? ($privacy->work_phone_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->work_phone_show) ? ($privacy->work_phone_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->work_phone_show) ? ($privacy->work_phone_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->work_phone_show) ? ($privacy->work_phone_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="skype_show" class="col-md-4 col-form-label text-md-right">Skype виден</label>
            <div class="col-md-6">
                <select autocomplete="off" id="skype_show" name="privacy[skype_show]" class="form-control">
                    <option value="none" {{!empty($privacy->skype_show) ? ($privacy->skype_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->skype_show) ? ($privacy->skype_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->skype_show) ? ($privacy->skype_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->skype_show) ? ($privacy->skype_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="site_show" class="col-md-4 col-form-label text-md-right">Сайт виден</label>
            <div class="col-md-6">
                <select autocomplete="off" id="site_show" name="privacy[web_site_show]" class="form-control">
                    <option value="none" {{!empty($privacy->web_site_show) ? ($privacy->web_site_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->web_site_show) ? ($privacy->web_site_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->web_site_show) ? ($privacy->web_site_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->web_site_show) ? ($privacy->web_site_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="email_work_show" class="col-md-4 col-form-label text-md-right">Корпоративный email виден</label>
            <div class="col-md-6">
                <select autocomplete="off" id="email_work_show" name="privacy[work_email_show]" class="form-control">
                    <option value="none" {{!empty($privacy->work_email_show) ? ($privacy->work_email_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->work_email_show) ? ($privacy->work_email_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->work_email_show) ? ($privacy->work_email_show == 'auth_only' ? 'selected' : '') :''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->work_email_show) ? ($privacy->work_email_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        @if ($user->permission == 'expert')
        <div class="form-group row">
            <label for="email_private_show" class="col-md-4 col-form-label text-md-right">Личный email виден</label>
            <div class="col-md-6">
                <select autocomplete="off" id="email_private_show" name="privacy[personal_email_show]" class="form-control">
                    <option value="none" {{!empty($privacy->personal_email_show) ? ($privacy->personal_email_show == 'none' ? 'selected' : '') : ''}}>Никому</option>
                    <option value="all" {{!empty($privacy->personal_email_show) ? ($privacy->personal_email_show == 'all' ? 'selected' : '') : ''}}>Всем посетителям сайта</option>
                    <option value="auth_only" {{!empty($privacy->personal_email_show) ? ($privacy->personal_email_show == 'auth_only' ? 'selected' : '') : ''}}>Авторизованным посетителям сайта</option>
                    <option value="friends" {{!empty($privacy->personal_email_show) ? ($privacy->personal_email_show == 'friends' ? 'selected' : '') : ''}}>Моим друзьям</option>
                </select>
            </div>
        </div>
        @endif
    </div>
</div>