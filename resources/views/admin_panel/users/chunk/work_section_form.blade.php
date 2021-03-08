<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Работа</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group row">
            <label for="ui-id-1" class="col-md-4 col-form-label text-md-right">
                <span class="li-form-label-name">Страна <span class="li-form-required">*</span></span>
            </label>
            <div class="col-md-6">
                <select autocomplete="off" name="country_id" class="form-control" required>
                    @forelse ($countries as $country)
                        <option {{old('country_id') == $country->id ? 'selected' : ''}} value="{{$country->id}}">{{$country->title}}, {{$country->title_en}}</option>
                    @empty
                        <option>Данных нет</option>
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="city_id" class="col-md-4 col-form-label text-md-right">
                <span class="li-form-label-name">Город <span class="li-form-required">*</span></span>
            </label>
            <div class="col-md-6">
                <select autocomplete="off" name="city_id" id="city_id" type="text" class="li-form-input form-control js-example-basic-single" required>
                    <option value="{{ $user->socialProfile->city_id ?? old('city_id') }}">{{ $user ? $user->cityName($user->socialProfile->city_id) : ''}}</option>
                </select>
            </div>
        </div>

        <div class="form-group row private">
            <label for="private" class="col-md-4 col-form-label text-md-right">Частное лицо</label>
            <div class="col-md-6">
                <input id="private" name="private" type="checkbox" class="form-form-check-input" {{$user['private'] == true || old('private') == true ? 'value=1  checked' : 'value=""'}}>
            </div>
        </div>
        <div class="form-group row">
            <label for="company_rus" class="col-md-4 col-form-label text-md-right">Компания (русское написание)</label>
            <div class="col-md-6">
                <input id="company_rus" type="text" name="company_rus" class="company form-control" value="{{ (!empty($user->company))? $user->company->name : old('company_rus') }}">
                <input type="hidden" name="company_id" value="{{$user->company->id ?? old('company_id')}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="company_en" class="col-md-4 col-form-label text-md-right">Компания (английское написание)</label>
            <div class="col-md-6">
                <input id="company_en" name="company_en" type="text" class="company form-control" value="{{ (!empty($user->company))? $user->company->name_en : old('company_en') }}">
                <label id="companyIsUser" class="error" for="first_name" style="display: none">Существующую компанию нельзя отредактировать</label>
            </div>
        </div>

        <div class="form-group row">
            <label for="ui-id-2" class="col-md-4 col-form-label text-md-right">Сфера деятельности компании</label>
            <div class="col-md-6">
                <select autocomplete="off" name="company_type" class="form-control" id="ui-id-2">
                    <option></option>
                    @forelse($company_type as $type)
                        <option {{old('company_type') == $type->id || (!empty($user->
                        company) ? ($user->company->type_id == $type->id) : '') ? 'selected' : ''}} value="{{$type->id}}">{{$type->name}}</option>
                    @empty
                        Нет данных
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group row work_div">
            <label for="company_post" class="col-md-4 col-form-label text-md-right">Должность</label>
            <div class="col-md-6">
                <input id="company_post" name="company_post" type="text" class="form-control" value="{{$social_profile->company_post ?? old('company_post')}}">
            </div>
        </div>
    </div>
</div>