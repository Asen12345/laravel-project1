<form action="{{route('front.register.company')}}" method="post" id="registrationFormCompany" class="li-form li-form--colored">
    @csrf
    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name" id="email-label">E-mail<span class="li-form-required">*</span></span>
        <input name="email" id="email_company" class="li-form-input" value="{{old('email')}}" required>
		<span class="li-form-label-name">Чтобы исключить неофициальную регистрацию, принимаются только корпоративные адреса вида pr@company, office@company и аналогичные.</span>
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Компания (русское написание) <span class="li-form-required">*</span></span>
        <input type="text" name="company_rus" class="company li-form-input" value="{{old('company_rus')}}" reqrequired="true"uired>
        <input type="hidden" name="company_id" value="{{old('company_id')}}" required="true">
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Компания (английское написание)</span>
        <input name="company_en" type="text" class="company li-form-input" value="{{old('company_en')}}">
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
        <select name="city_id" type="text" class="li-form-input form-control js-example-basic-single" required>
            <option value="{{ old('city_id') }}">{{ old('city_id') ? \App\Eloquent\GeoCity::find(old('city_id'))->title : ''}}</option>
        </select>
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
        <span class="li-form-label-name">Пароль <span class="li-form-required">*</span></span>
        <input name="password" type="password" class="li-form-input" required>
    </label>

    <label class="li-form-label li-form-label--std-width-reg">
        <span class="li-form-label-name">Повторите пароль <span class="li-form-required">*</span></span>
        <input name="password_confirmation" type="password" class="li-form-input" required>
    </label>

    <label class="h-mt-20">
        <input name="agree" type="checkbox" value="1" required>
        <span id="company-error-agree">Я принимаю условия пользовательского соглашения<span class="li-form-required">*</span></span>
    </label>

    <div class="h-clearfix">
        <button type="submit" class="button button_color_red h-left">Отправить</button>
    </div>
</form>