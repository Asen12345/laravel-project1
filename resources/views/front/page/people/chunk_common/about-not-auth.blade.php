@if ($user->socialProfile->about_me && $user->privacyNotAuth('about_me_show'))
    <div class="company-about"><a href="#" class="company-about__link">
            {{$user['permission'] == 'expert' || old('permission') == 'expert' ? 'Обо мне' : 'О компании'}}
        </a>
        <div class="company-about__hidden">
            {!!$user->socialProfile->about_me ?? ''!!}
            <div class="height-cover"></div>
        </div>
    </div>
@endif
@if ($user['permission'] == 'company' && !empty($user->services) && $user->services->isNotEmpty())
    <div class="company-services">
        <div class="title-upcase-bl">Услуги</div>
        <ul class="acordeon-list">
            @forelse($user->services as $service)
                <li class="acordeon-item acordeon-item-with-sublist company-services__acc-item">
                    <a href="#" class="acordeon-link company-services__toggle-link">{{ $service->name }}</a>
                    <ul class="acordeon-sublist" style="display: none;">
                        {!! $service->text !!}
                    </ul>
                </li>
            @empty
            @endforelse
        </ul>
    </div>
@endif