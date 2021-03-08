<div class="header-bottom__inner">
    <ul class="header-menu header-menu--desk sf-menu sf-navbar">
        @foreach($header_menu as $key => $menu)
            <li class="{{$content_page['menu_active'] === $key ? 'active current' : ''}}">
                @if ($key == 'shop')
                <a href="{{route('front.page.shop.researches.author', ['id' => '1'])}}" class="header-menu__link">
                    <span class="header-menu__val">{{$menu['name']}}</span>
                    <span class="header-menu__count">{{$menu['total']}}</span>
                    <div class="sp-icon ico-mob-menu-chev"></div>
                </a>
                @else
                    <a href="{{route('front.'. $menu['route'])}}" class="header-menu__link">
                        <span class="header-menu__val">{{$menu['name']}}</span>
                        <span class="header-menu__count">{{$menu['total']}}</span>
                        <div class="sp-icon ico-mob-menu-chev"></div>
                    </a>
                @endif
                <ul>
                    @if ($key !== 'blogs' && $key !== 'shop')
                        @foreach($menu['sections'] as $section)
                            <li><a href="{{route('front.page.news.category', ['url_section' => $section['url_en']])}}" class="header-menu__sublink">{{$section['name'] ?? ''}}</a></li>
                        @endforeach
                    @else
                        @foreach($menu['sections'] as $section)
                            <li><a href="{{$section['url_en']}}" class="header-menu__sublink">{{$section['name'] ?? ''}}</a></li>
                        @endforeach
                    @endif

                </ul>
            </li>
        @endforeach
    </ul>
    <ul class="header-menu header-menu--mob sf-menu sf-navbar">
        @foreach($header_menu as $key => $menu)
            <li class="{{$content_page['menu_active'] === $key ? 'active current' : ''}}">
                @php $dataAtribute = '' @endphp
                @if (count($menu['sections']) > 0)
                    @php $dataAtribute = 'data-hover=hover-on-link' @endphp
                @endif
                @if ($key == 'shop')
                    <a href="{{route('front.page.shop.researches.author', ['id' => '1'])}}" {{$dataAtribute}} class="header-menu__link">
                        <span class="header-menu__val">{{$menu['name']}}</span>
                        <span class="header-menu__count">{{$menu['total']}}</span>
                        <div class="sp-icon ico-mob-menu-chev"></div>
                    </a>
                @else
                    <a href="{{route('front.'. $menu['route'])}}" {{$dataAtribute}} class="header-menu__link">
                        <span class="header-menu__val">{{$menu['name']}}</span>
                        <span class="header-menu__count">{{$menu['total']}}</span>
                        <div class="sp-icon ico-mob-menu-chev"></div>
                    </a>
                @endif
                <ul>
                    @if ($key !== 'blogs' && $key !== 'shop')
                        @foreach($menu['sections'] as $section)
                            <li><a href="{{route('front.page.news.category', ['url_section' => $section['url_en']])}}" class="header-menu__sublink">{{$section['name'] ?? ''}}</a></li>
                        @endforeach
                    @else
                        @foreach($menu['sections'] as $section)
                            <li><a href="{{$section['url_en']}}" class="header-menu__sublink">{{$section['name'] ?? ''}}</a></li>
                        @endforeach
                    @endif

                </ul>
            </li>
        @endforeach
    </ul>
    <a href="{{route('front.page.news.add')}}" class="button button-green add-news">добавить свою новость</a>
</div>