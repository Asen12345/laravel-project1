<div class="categories-menu-block">
    <div class="sidebar-standart-title">Категории</div>
    <ul class="sf-menu sf-vertical categories-menu mnu-standart-style-reset">
        <li class="active"><a href="{{ route('front.page.blogs.all', ['sort' => 'expert']) }}" class="categories-menu__link">Блоги экспертов</a></li>
        <li><a href="{{ route('front.page.blogs.all', ['sort' => 'company']) }}" class="categories-menu__link">Корпоративные блоги</a></li>
        <li><a href="{{route('front.page.posts.all')}}" class="categories-menu__link">Все сообщения</a></li>
    </ul>
</div>