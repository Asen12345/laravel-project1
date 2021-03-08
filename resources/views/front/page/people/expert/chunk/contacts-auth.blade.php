@if (($user->socialProfile->work_phone && Gate::allows('work-phone-show', $user)) ||
    ($user->socialProfile->mobile_phone && Gate::allows('mobile-phone-show', $user)) ||
    ($user->socialProfile->work_email && Gate::allows('work-email-show', $user)) ||
    ($user->socialProfile->personal_email && Gate::allows('personal-email-show', $user)) ||
    ($user->socialProfile->skype && Gate::allows('skype-show', $user)) ||
    ($user->socialProfile->web_site && Gate::allows('web-site-show', $user)))
    <div class="company-info__title">контакты</div>
@endif
@if ($user->socialProfile->work_phone && Gate::allows('work-phone-show', $user))
    <div>Рабочий телефон: {{$user->socialProfile->work_phone}}</div>
@endif
@if ($user->socialProfile->mobile_phone && Gate::allows('mobile-phone-show', $user))
    <div>Личный телефон: {{$user->socialProfile->mobile_phone}}</div>
@endif
@if ($user->socialProfile->work_email && Gate::allows('work-email-show', $user))
    <div>
        <span>Рабочий E-mail:</span>
        <a href="mailto:{{$user->socialProfile->work_email}}" class="company-info__mail li-link-blue">
            {{$user->socialProfile->work_email}}
        </a>
    </div>
@endif
@if ($user->socialProfile->personal_email && Gate::allows('personal-email-show', $user))
    <div>
        <span>Личный E-mail:</span>
        <a href="mailto:{{$user->socialProfile->personal_email}}" class="company-info__mail li-link-blue">
            {{$user->socialProfile->personal_email}}
        </a>
    </div>
@endif
@if ($user->socialProfile->skype && Gate::allows('skype-show', $user))
    <div>Skype: {{$user->socialProfile->skype}}</div>
@endif
@if ($user->socialProfile->web_site && Gate::allows('web-site-show', $user))
    <div>Сайт: <a target="_blank" href="{{ url($user->socialProfile->web_site) }}">{{ url($user->socialProfile->web_site) }}</a></div>
@endif