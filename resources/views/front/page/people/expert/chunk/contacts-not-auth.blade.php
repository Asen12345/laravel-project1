@if (($user->socialProfile->work_phone && $user->privacyNotAuth('work_phone_show')) ||
    ($user->socialProfile->mobile_phone && $user->privacyNotAuth('mobile_phone_show')) ||
    ($user->socialProfile->work_email && $user->privacyNotAuth('work_email_show')) ||
    ($user->socialProfile->personal_email && $user->privacyNotAuth('personal_email_show')) ||
    ($user->socialProfile->skype && $user->privacyNotAuth('skype_show')) ||
    ($user->socialProfile->web_site && $user->privacyNotAuth('web_site_show')))
    <div class="company-info__title">контакты</div>
@endif
@if ($user->socialProfile->work_phone && $user->privacyNotAuth('work_phone_show'))
    <div>Рабочий телефон: {{$user->socialProfile->work_phone}}</div>
@endif
@if ($user->socialProfile->mobile_phone && $user->privacyNotAuth('mobile_phone_show'))
    <div>Личный телефон: {{$user->socialProfile->mobile_phone}}</div>
@endif
@if ($user->socialProfile->work_email && $user->privacyNotAuth('work_email_show'))
    <div>
        <span>Рабочий E-mail:</span>
        <a href="mailto:{{$user->socialProfile->work_email}}" class="company-info__mail li-link-blue">
            {{$user->socialProfile->work_email}}
        </a>
    </div>
@endif
@if ($user->socialProfile->personal_email && $user->privacyNotAuth('personal_email_show'))
    <div>
        <span>Личный E-mail:</span>
        <a href="mailto:{{$user->socialProfile->personal_email}}" class="company-info__mail li-link-blue">
            {{$user->socialProfile->personal_email}}
        </a>
    </div>
@endif
@if ($user->socialProfile->skype && $user->privacyNotAuth('skype_show'))
    <div>Skype: {{$user->socialProfile->skype}}</div>
@endif
@if ($user->socialProfile->web_site && $user->privacyNotAuth('web_site_show'))
    <div>Сайт: <a target="_blank" href="{{ url($user->socialProfile->web_site) }}">{{ url($user->socialProfile->web_site) }}</a></div>
@endif