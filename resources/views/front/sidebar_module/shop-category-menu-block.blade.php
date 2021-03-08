@if ($type === 'widget')
    {!! $widget !!}
@else
    <div class="categories-menu-block">
        <div class="sidebar-standart-title">Исследования</div>
        <ul class="sf-menu sf-vertical categories-menu mnu-standart-style-reset">
            @forelse ($categories as $row)
                <li><a href="{{route('front.page.shop.researches.category', ['url_section' => $row->url_en])}}" class="categories-menu__link">{{$row->name}}</a>
                    @if ($row->sub_category->isNotEmpty())
                        <ul class="categories-menu__sub">
                            @foreach($row->sub_category as $sub)
                                <li class="categories-menu__item-sub">
                                    <a href="{{route('front.page.shop.researches.category', ['url_section' => $row->url_en, 'url_sub_section' => $sub->url_en])}}" class="categories-menu__sublink">{{$sub->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @empty
            @endforelse
            <li><a href="{{route('front.page.shop')}}" class="categories-menu__link">Все исследования</a></li>				
            <li>@include('front.sidebar_module.search', ['route' => route('front.page.shop.search'), 'search' => 'Поиск исследований'])</li>
        </ul>
    </div>

@endif