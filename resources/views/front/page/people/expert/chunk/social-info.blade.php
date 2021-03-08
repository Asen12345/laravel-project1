<div class="company-info">
    <div class="company-info__column">
        <div class="company-info__title">работа</div>
        <div class="company-info__block">
            <div class="company-info__bage">Компания</div>
            <div class="company-info__descr">
                {{$user->private == true ? 'Частное лицо' : $user->company->name ?? ''}}
            </div>
        </div>
        @if ($user->private == false)
            @if ($user->socialProfile->company_post)
                <div class="company-info__block">
                    <div class="company-info__bage">Должность</div>
                    <div class="company-info__descr">
                        {{$user->socialProfile->company_post}}
                    </div>
                </div>
            @endif
            @if ($user->company)
                <div class="company-info__block">
                    <div class="company-info__bage">Сфера деятельности компании</div>
                    <div class="company-info__descr">
                        <a class="li-link" href="{{ route('front.page.people.filter.company', ['id' => $user->company->typeCompany->id]) }}">{{$user->company->typeCompany->name}}</a>
                    </div>
                </div>
            @endif
        @endif
    </div>
    <div class="company-info__column">
        {{--For Gate because $user return fals every time--}}
        @if (auth()->check())
            @include('front.page.people.expert.chunk.contacts-auth', ['user' => $user])
        @else
            @include('front.page.people.expert.chunk.contacts-not-auth', ['user' => $user])
        @endif
    </div>
    <div class="company-info__column">
        @if (!empty($user->blog) || $user->socialProfile->face_book || $user->socialProfile->linked_in || $user->socialProfile->v_kontakte || $user->socialProfile->odnoklasniki)
            <div class="company-info__title">страницы в соцсетях</div>
        @endif
        @if (!empty($user->blog))
            <div class="personal-link">
                <div class="sp-icon ico-profile-doc"></div><a href="{{route('front.page.blog', ['permission' => 'expert', 'blog_id' => $user->blog->id])}}">Личный блог</a>
            </div>
        @endif
        @if ($user->socialProfile->face_book)
            <div class="personal-link">
                <div class="sp-icon ico-profile-fb"></div>
                <a target="_blank" href="{{$user->socialProfile->face_book}}">FaceBook</a>
            </div>
        @endif
        @if ($user->socialProfile->linked_in)
            <div class="personal-link">
                <div class="sp-icon ico-profile-in"></div>
                <a target="_blank" href="{{$user->socialProfile->linked_in}}">LinkedIn</a>
            </div>
        @endif
        @if ($user->socialProfile->v_kontakte)
            <div class="personal-link">
                <div class="sp-icon ico-profile-vk"></div>
                <a target="_blank" href="{{$user->socialProfile->v_kontakte}}">Вконтакте</a>
            </div>
        @endif
        @if ($user->socialProfile->odnoklasniki)
            <div class="personal-link">
                <div class="sp-icon ico-profile-ok"></div>
                <a target="_blank" href="{{$user->socialProfile->odnoklasniki}}">Одноклассники</a>
            </div>
        @endif
    </div>
</div>
@if (auth()->check())
    @include('front.page.people.chunk_common.about-auth', ['user' => $user])
@else
    @include('front.page.people.chunk_common.about-not-auth', ['user' => $user])
@endif