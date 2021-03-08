<div class="container footer-top__wrapper">
    <div class="footer-cookies">
        <div class="sp-icon ico-close-wt"></div><span>Мы используем «куки»</span><a href="#">Что это?</a>
    </div>
    <div class="row footer-top__columns">
        <div class="footer-column">
            <div class="footer-column__title">ЛюдиИпотеки.рф</div>
            <ul class="footer-column__menu mnu-standart-style-reset">
                <li><a href="/main/content/page/about">О нас</a></li>
                <li><a href="{{ route('front.site.map') }}">Карта сайта</a></li>
                <li><a href="#">Аудитория</a></li>
                <li><a href="#">Сотрудничество</a></li>
                <li><a href="{{ route('front.page.feedback') }}">Обратная связь</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <div class="footer-column__title">магазин исследований</div>
            <ul class="footer-column__menu mnu-standart-style-reset">
                @if (!empty($footer['category_researches']))
                    @foreach($footer['category_researches'] as $category_researches)
                        @if ($category_researches->parent_id == 0)
                            {{--Url news category without child--}}
                            <li><a href="{{route('front.page.shop.researches.category', ['url_section' => $category_researches->url_en])}}">{{$category_researches->name}}</a></li>
                        @else
                            {{--Url news category have child--}}
                        <li><a href="{{route('front.page.shop.researches.category', ['url_section' => $category_researches->parent->url_en, 'url_sub_section' => $category_researches->url_en])}}">{{$category_researches->name}}</a></li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="footer-column">
            <div class="footer-column__title">новости</div>
            <ul class="footer-column__menu mnu-standart-style-reset">
                @if (!empty($footer['category_news']))
                    @foreach($footer['category_news'] as $category_news)
                        {{--Category only first step--}}
                        <li><a href="{{route('front.page.news.category', ['url_section' => $category_news->url_en])}}">{{$category_news->name}}</a></li>
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="footer-column">
            <div class="footer-column__title">навигация</div>
            <ul class="footer-column__menu mnu-standart-style-reset">
                <li><a href="{{ route('front.page.people.experts.index') }}">Эксперты</a></li>
                <li><a href="{{ route('front.page.people.companies.index') }}">Компании</a></li>
                <li><a href="{{ route('front.page.blogs.all') }}">Блоги</a></li>
                <li><a href="{{ route('front.page.anons') }}">Мероприятия</a></li>
            </ul>
        </div>
        <div class="footer-column footer-column--vk">
		<script type="text/javascript" src="https://vk.com/js/api/openapi.js?167"></script>
		<!-- VK Widget -->
		<div id="vk_groups"></div>
		<script type="text/javascript">
		VK.Widgets.Group("vk_groups", {mode: 3, no_cover: 1}, 53650211);
		</script>
		</div>
        <div class="footer-column footer-column--vk">
		<div id="fb-root"></div>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v6.0&appId=282629052348773"></script>
		<div class="fb-page" data-href="https://www.facebook.com/ludiipoteki/" data-tabs="" data-width="226" data-height="210" data-small-header="true" data-adapt-container-width="false" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/ludiipoteki/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/ludiipoteki/">ЛюдиИпотеки.рф</a></blockquote></div>
		</div>
    </div>
</div>