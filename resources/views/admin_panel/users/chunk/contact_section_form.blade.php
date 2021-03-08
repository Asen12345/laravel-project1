<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Контакты</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group row">
            <label for="work_phone" class="col-md-4 col-form-label text-md-right">Рабочий телефон</label>
            <div class="col-md-6">
                <input id="work_phone" name="contacts[work_phone]" type="text" class="form-control" value="{{$social_profile->work_phone ?? ''}}" placeholder="+0(000)000-00-00">
            </div>
        </div>
        <div class="form-group row work_div">
            <label for="mobile_phone" class="col-md-4 col-form-label text-md-right">Мобильный телефон</label>
            <div class="col-md-6">
                <input id="mobile_phone" name="contacts[mobile_phone]" type="text" class="form-control" value="{{$social_profile->mobile_phone ?? ''}}" placeholder="+0(000)000-00-00">
            </div>
        </div>

        <div class="form-group row">
            <label for="skype" class="col-md-4 col-form-label text-md-right">Skype</label>
            <div class="col-md-6">
                <input id="skype" name="contacts[skype]" type="text" class="form-control" value="{{$social_profile->skype ?? ''}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="web_site" class="col-md-4 col-form-label text-md-right">Сайт</label>
            <div class="col-md-6">
                <input id="web_site" name="contacts[web_site]" type="text" class="form-control" value="{{$social_profile->web_site ?? ''}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="work_email" class="col-md-4 col-form-label text-md-right">Корпоративный email</label>
            <div class="col-md-6">
                <input id="work_email" name="contacts[work_email]" type="text" class="form-control" value="{{$social_profile->work_email ?? ''}}">
            </div>
        </div>

        <div class="form-group row work_div">
            <label for="personal_email" class="col-md-4 col-form-label text-md-right">Личный email</label>
            <div class="col-md-6">
                <input id="personal_email" name="contacts[personal_email]" type="text" class="form-control" value="{{$social_profile->personal_email ?? ''}}">
            </div>
        </div>

        @if ($user['permission'] == 'company')
        <div class="form-group row">
            <label for="address" class="col-md-4 col-form-label text-md-right">Адрес</label>
            <div class="col-md-6">
                <input id="address" name="contacts[address]" type="text" class="form-control" value="{{$social_profile->address ?? ''}}">
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label for="about_me" class="col-md-4 col-form-label text-md-right">{{$user['permission'] == 'expert' || old('permission') == 'expert' ? 'Обо мне' : 'О компании'}}</label>
            <div class="col-md-6">
                <textarea id="about_me" name="contacts[about_me]" class="form-control summernote" rows="4" cols="50">{{$social_profile->about_me ?? ''}}</textarea>
            </div>
        </div>
    </div>
</div>