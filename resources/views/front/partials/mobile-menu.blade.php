

<div class="col-12 cat-mob-wrap">
    @if ($type === 'widget')
        {!! $widget !!}
    @else
        <div class="categories-menu-block categories-menu-block--mob">
            <div class="sidebar-standart-title">
                Исследования
            </div>
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
</div>
<script>
    // change widget html to mobile-menu logic
    let wrapper = document.getElementsByClassName('cat-mob-wrap')[0];
    let menu_wrapper = wrapper.children[0];
    menu_wrapper.classList.add('categories-menu-block--mob');
    let title_wrapper = menu_wrapper.children[0];
    let toggler = document.createElement('a');
    toggler.classList.add('toggle-mnu');
    toggler.setAttribute('href', '#');
    toggler.append(document.createElement('span'));
    title_wrapper.prepend(toggler);

</script>
