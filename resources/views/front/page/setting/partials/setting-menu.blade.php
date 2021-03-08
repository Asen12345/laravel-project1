<ul class="tabs__list mnu-standart-style-reset">
    <li class="tabs__item {{$active == 'main' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account')}}" class="menu_account">
            <span>Настройки</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'privacy' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'privacy'])}}" class="menu_account">
            <span>Приватность</span>
        </a>
    </li>
    @if ($user->permission == 'company')
        <li class="tabs__item {{$active == 'services' ? 'tabs__item--active' : ''}}">
            <a href="{{route('front.setting.account', ['page' => 'services'])}}" class="menu_account">
                <span>Услуги</span>
            </a>
        </li>
    @endif
    <li class="tabs__item {{$active == 'message' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'message'])}}" class="menu_account">
            <span>Сообщения {{$messages_not_reads_count !== 0 ? '(' . $messages_not_reads_count . ')' : ''}}</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'comments' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'comments'])}}" class="menu_account">
            <span>Комментарии</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'friends' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'friends'])}}" class="menu_account">
            <span>Друзья</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'blog' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'blog'])}}" class="menu_account">
            <span>Мой блог</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'news' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'news'])}}" class="menu_account">
            <span>Мои новости</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'topic' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'topic'])}}" class="menu_account">
            <span>Мои темы</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'purchase' ? 'tabs__item--active' : ''}}">
        <a href="{{ route('front.setting.account', ['page' => 'purchase']) }}" class="menu_account">
            <span>Мои покупки</span>
        </a>
    </li>
    <li class="tabs__item {{$active == 'subscriptions' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.setting.account', ['page' => 'subscriptions'])}}" class="menu_account">
            <span>Мои подписки</span>
        </a>
    </li>
</ul>