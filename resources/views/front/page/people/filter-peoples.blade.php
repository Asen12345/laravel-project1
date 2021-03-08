<form action="{{route('front.page.people.filter.company')}}" method="get" class="li-form companies-form">
    <label class="li-form-label companies-form__name"><span class="li-form-label-name">Имя/Фамилия:</span>
        <input type="text" name="name" class="li-form-input" value="{{request()->name}}">
    </label>
    <label class="li-form-label companies-form__sphere"><span class="li-form-label-name">Сфера деятельности:</span>
        <select name="company_type_id" class="li-form-select">
            <option value=""></option>
            @foreach ($filter_content['company_types'] as $type)
                <option value="{{$type->id}}" {{request()->company_type_id == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
            @endforeach
        </select>
    </label>
    <label class="li-form-label companies-form__country"><span class="li-form-label-name">Страна:</span>
        <select name="country_id" class="li-form-select">
            <option value=""></option>
            @foreach ($filter_content['geo_country'] as $country)
                <option value="{{$country->id}}" {{request()->country_id == $country->id ? 'selected' : ''}}>{{$country->title}}</option>
            @endforeach
        </select>
    </label>
    <label class="li-form-label companies-form__location">
        <span class="li-form-label-name">Город:</span>
        <select id="city" name="city_id" class="js-data-example-ajax" style="width: 100%">
            <option value="{{request()->city_id}}" selected>{{$city_name ?? 'Нет'}}</option>
        </select>
    </label>
    <button class="button button-micro button-l-blue">найти</button>
</form>