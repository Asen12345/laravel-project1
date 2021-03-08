<ul class="tabs__list mnu-standart-style-reset">
    <li class="tabs__item {{request()->sort == null || request()->sort == 'expert' || request()->sort == 'company' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.page.blogs.all')}}" class="tabs__link url">Актуальные</a>
    </li>
    <li class="tabs__item {{request()->sort == 'newest' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.page.blogs.all', ['sort' => 'newest'])}}" class="tabs__link url">Новые</a>
    </li>
    <li class="tabs__item {{request()->sort == 'popular' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.page.blogs.all', ['sort' => 'popular'])}}" class="tabs__link url">Читаемые</a>
    </li>
    <li class="tabs__item {{request()->sort == 'discussed' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.page.blogs.all', ['sort' => 'discussed'])}}" class="tabs__link url">Обсуждаемые</a>
    </li>
    <li class="tabs__item {{request()->sort == 'rate' ? 'tabs__item--active' : ''}}">
        <a href="{{route('front.page.blogs.all', ['sort' => 'rate'])}}" class="tabs__link url">Рейтинг читателей</a>
    </li>
</ul>