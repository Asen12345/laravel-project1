<div class="categories-menu-block">
    <div class="sidebar-standart-title">Категории</div>
    <ul class="sf-menu sf-vertical categories-menu mnu-standart-style-reset">
        <li><a href="{{route('front.page.news.all')}}" class="categories-menu__link">Весь каталог</a></li>
        @forelse ($data as $row)
            <li><a href="{{route('front.page.news.category', ['url_section' => $row->url_en])}}" class="categories-menu__link">{{$row->name}}</a>
                @if ($row->sub_category->isNotEmpty())
                    <ul class="categories-menu__sub">
                        @foreach($row->sub_category as $sub)
                            <li class="categories-menu__item-sub">
                                <a href="{{route('front.page.news.category', ['url_section' => $row->url_en, 'url_sub_section' => $sub->url_en])}}" class="categories-menu__sublink">{{$sub->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @empty
        @endforelse
    </ul>
</div>