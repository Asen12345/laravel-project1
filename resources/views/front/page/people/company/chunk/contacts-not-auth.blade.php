@if (($user->socialProfile->work_phone && Gate::allows('work-phone-show', $user)) ||
    ($user->socialProfile->mobile_phone && Gate::allows('mobile-phone-show', $user)) ||
    ($user->socialProfile->work_email && Gate::allows('work-email-show', $user)) ||
    ($user->socialProfile->personal_email && Gate::allows('personal-email-show', $user)) ||
    ($user->socialProfile->skype && Gate::allows('skype-show', $user)) ||
    ($user->socialProfile->web_site && Gate::allows('web-site-show', $user)))
    <div class="company-info__title">контакты</div>
@endif
@if ($user->socialProfile->work_phone && $user->privacy->work_phone_show !== 'auth_only' && $user->privacy->work_phone_show !== 'none')
    <div>Телефон: {{$user->socialProfile->work_phone}}</div>
@endif
@if ($user->socialProfile->address && $user->privacy->address_show !== 'auth_only' && $user->privacy->address_show !== 'none')
    <div>Адрес: {{$user->socialProfile->address}}</div>
@endif
@if ($user->socialProfile->work_email && $user->privacy->work_email_show !== 'auth_only' && $user->privacy->work_email_show !== 'none')
    <div>
        <span>E-mail корпоративный:</span>
        <a href="mailto:{{$user->socialProfile->work_email}}" class="company-info__mail li-link-blue">
            {{$user->socialProfile->work_email}}
        </a>
    </div>
@endif
@if ($user->socialProfile->skype && $user->privacy->skype_show !== 'auth_only' && $user->privacy->skype_show !== 'none')
    <div>Skype: {{$user->socialProfile->skype}}</div>
@endif
@if ($user->socialProfile->web_site && $user->privacy->web_site_show !== 'auth_only' && $user->privacy->web_site_show !== 'none')
    <div>Сайт: <a target="_blank" href="{{ url($user->socialProfile->web_site) }}">{{ url($user->socialProfile->web_site) }}</a></div>
@endif