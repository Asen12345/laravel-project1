<form action="{{route('front.register.user')}}" method="post" id="registrationFormExpert" class="li-form li-form--colored">
    @csrf
    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name" id="email-label">E-mail<span class="li-form-required">*</span> (является логином)</span>
        <input name="email" id="email_expert" class="li-form-input form-control" value="{{old('email')}}" required>
		<span class="li-form-label-name">Поскольку E-mail может быть изменен только Администраций сайта по вашему запросу, чтобы не было проблем при смене работы, вы можете указывать личный адрес.</span>
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name" id="firstname-label">Имя <span class="li-form-required">*</span></span>
        <input name="firstname" type="text" class="li-form-input form-control" value="{{old('firstname')}}" required>
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Фамилия <span class="li-form-required">*</span></span>
        <input name="lastname" type="text" class="li-form-input form-control" value="{{old('lastname')}}" required>
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Страна <span class="li-form-required">*</span></span>
        <select name="country" class="li-form-input form-control select2"  required="true">
            @forelse ($countries as $country)
                <option {{old('country') == $country->id ? 'selected' : ''}} value="{{$country->id}}">{{$country->title}}, {{$country->title_en}}</option>
            @empty
                <option>Данных нет</option>
            @endforelse
        </select>
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Город <span class="li-form-required">*</span></span>
        <select name="city_id" type="text" class="li-form-input form-control js-example-basic-single" required="true">
            <option value="{{ old('city_id') }}">{{ old('city_id') ? \App\Eloquent\GeoCity::find(old('city_id'))->title : ''}}</option>
        </select>
    </label>

    <div class="h-mt-20 is_not_comp">
        <input type="checkbox" id="private" name="private" {{old('private') == true ? "checked" : ''}}><span>Частное лицо (для временно не работающих)</span>
    </div>
    <div id="company_hide">
        <label class="li-form-label li-form-label--std-width-reg">
            <span class="li-form-label-name">Компания (русское написание) <span class="li-form-required">*</span></span>
            <input type="text" name="company_rus" class="company li-form-input" value="{{old('company_rus')}}" >
            <input type="hidden" name="company_id" value="{{old('company_id')}}">
        </label>

        <label class="li-form-label li-form-label--std-width-reg">
            <span class="li-form-label-name">Компания (английское написание)</span>
            <input name="company_en" type="text" class="company li-form-input" value="{{old('company_en')}}">
        </label>

        <label class="li-form-label li-form-label--std-width-reg company_type">
            <span class="li-form-label-name">Сфера деятельности компании <span class="li-form-required">*</span></span>
            <select name="company_type" class="li-form-input form-control select2">
                <option></option>
                @forelse($company_type as $type)
                    <option {{old('company_type') == $type->id ? 'selected' : ''}} value="{{$type->id}}">{{$type->name}}</option>
                @empty
                Нет данных
                @endforelse
            </select>
        </label>
        <label class="li-form-label li-form-label--std-width-reg">
            <span class="li-form-label-name">Должность <span class="li-form-required">*</span></span>
            <input name="company_post" type="text" class="li-form-input" value="{{old('company_post')}}">
        </label>
    </div>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Пароль <span class="li-form-required">*</span></span>
        <input name="password" type="password" class="li-form-input" required>
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Повторите пароль <span class="li-form-required">*</span></span>
        <input name="password_confirmation" type="password" class="li-form-input" required>
    </label>

    <label class="h-mt-20">
        <input name="agree" type="checkbox" value="1" required>
        <span id="error-agree">Я принимаю условия пользовательского соглашения<span class="li-form-required">*</span></span>
        <br>
        <input name="agree_data" type="checkbox" value="1" required>
        <span id="error-agree_data">Я даю согласие на обработку своих персональных данных<span class="li-form-required">*</span></span>
    </label>

    <div class="h-clearfix h-mt-20">
        <button type="submit" class="button button_color_red h-left">Отправить</button>
    </div>
</form>