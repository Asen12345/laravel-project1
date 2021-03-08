<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ $content_page['meta']['meta_title'] ?? $content_page['title'] }}</title>
    <link rel="shortcut icon" href="{{asset('/img/favicon/favicon.ico')}}" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @if (!empty($content_page['meta']['meta_keywords']))
    <meta name="keywords" content="{{$content_page['meta']['meta_keywords'] ?? '' }}">
    @endif
    @if (!empty($content_page['meta']['meta_description']))
    <meta name="description" content="{{$content_page['meta']['meta_description'] ?? '' }}">
    @endif
    <meta property="og:title" content="{{$news->name ?? $content_page['meta']['meta_title'] ?? $content_page['title']}}">
    @if (!empty($content_page['meta']['meta_description']))
    <meta property="og:description" content="{{ $content_page['meta']['meta_description'] ?? ''}}">
    @endif
	
	@if (!empty($research))
	<meta property="og:image" content="{{ $content_page['meta']['og_image'] }}">
	@else
	<meta property="og:image" content="{{ $blog->user_social ?? 'http://www.ludiipoteki.ru/img/logosquad.jpg' }}">	
	@endif	
	
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    {{--main.css был здесь--}}
    <link rel="stylesheet" href="{{asset('/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('/js/libs/jquery-ui/jquery-ui.css')}}">
    @yield('header_style')
    <link rel="stylesheet" href="{{asset('/css/main.css')}}">
</head>
<body>
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-12 header-top-inner">
                    <div class="header-socials">
                        <ul class="mnu-standart-style-reset">
                            <li class="header-rss"><a href="/news/rss"><i class="icon-rss"></i></a></li>
                            <li><a href="https://www.facebook.com/ludiipoteki" target="_blank" rel="nofollow"><i class="icon-facebook"></i></a></li>
                            <li><a href="https://twitter.com/ludiipoteki" target="_blank" rel="nofollow"><i class="icon-twitter"></i></a></li>
                            <li><a href="https://twitter.com/ludiipoteki" target="_blank" rel="nofollow"><i class="icon-vkontakte"></i></a></li>
                            <li><a href="http://ok.ru/ipoteka" target="_blank" rel="nofollow"><i class="icon-odnoklassniki"></i></a></li>
                        </ul>
                    </div>
                    <ul class="header-top__menu header-top__menu--icons mnu-standart-style-reset">
                        <li class="header-sitemap"><a href="/main/sitemap">
                            <div class="sp-icon site-map"></div></a></li>
                        <li class="header-home"><a href="/">
                            <div class="sp-icon home"></div></a></li>
                    </ul>
                    <div class="header-top__menu-wrap">
                        <ul class="header-top__menu header-top__menu--text header-top__menu--not-autor mnu-standart-style-reset">
                            <li><a href="/main/content/page/about">О нас</a></li>
                            <li><a href="/main/content/page/advert">Сотрудничество</a></li>
                            <li><a href="{{route('front.page.feedback')}}">Обратная связь</a></li>
                        </ul><a href="#" class="toggle-mnu"><span></span></a>
                    </div>
                    @if (auth()->check())
                        <ul class="header-top__menu header-top__menu--reg mnu-standart-style-reset">
                            <li class="header-mail">
                                @if (auth()->user()->cart()->where('status', 'started')->where('remind', false)->exists())
                                <span class="cart text-red">{{ auth()->user()->cart()->where('status', 'started')->where('remind', false)->first()->purchases()->count() }}</span>
                                @endif
                                <a href="{{ route('front.shop.researches.shopping.cart') }}" class="header-menu-icon">
                                    <div class="sp-icon shopping-cart"></div>
                                </a>
                            </li>
                            <li class="header-mail">
                                <a href="{{ route('front.setting.account.alert') }}" class="header-menu-icon">
                                    @if ($messageBell)
                                        <div class="header-bell__dot"></div>
                                    @endif
                                    <div class="sp-icon mail"></div>
                                </a>
                            </li>
                            <li class="header-bell">
                                <a href="{{route('front.setting.account.alert')}}" class="header-menu-icon">
                                    @if ($bell)
                                        <div class="header-bell__dot"></div>
                                    @endif
                                    <div class="sp-icon bell"></div>
                                </a>
                            </li>
                        </ul>
                        <div class="header-enter header-enter--reg button">
                            <span>{{auth()->user()->name}}</span>
                            <span class="sp-icon chev-top"></span>
                            <ul class="header-enter__menu">
                                @if (Gate::denies('is-social'))
                                    <li><a href="{{route('front.setting.account')}}">Настройки</a></li>
                                    <li><a href="{{route('user.login.logout')}}">Выход</a></li>
                                @else
                                    <li><a href="{{route('user.login.logout')}}">Выход</a></li>
                                @endif

                            </ul>
                        </div>

                    @else
                        <a href="#li-enter-popup" class="header-enter button to-popup">ВХОД</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="container">
            <div class="row">
                <div class="col-12 header-middle__inner">
                    <a href="{{route('front.home')}}" class="header-logo"><img src="{{asset('/img/logo.jpg')}}" alt="ЛюдиИпотеки"></a>
                    @if (!empty($bannersHeader))
                        @foreach($bannersHeader as $banner)
                            {{--Views Counter banner--}}
                            @php(\ViewsCount::process($banner))
                        <div class="header-banner">
                            @php($type = pathinfo($banner->image, PATHINFO_EXTENSION))
                            @if ($type == 'swf')
                                @if (!empty($banner->link))
                                    <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                        <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                        </object>
                                    </a>
                                @else
                                    <object style="min-height: 200px" width="100%" height="100%" type="application/x-shockwave-flash" data="{{asset('/storage/' . $banner->image)}}">
                                    </object>
                                @endif
                            @else
                                @if (!empty($banner->link))
                                    <a class="banner_click" data-id="{{$banner->id}}" href="{{url($banner->link)}}">
                                        <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                    </a>
                                @else
                                    <img src="{{asset('/storage/' . $banner->image)}}" alt="alt">
                                @endif
                            @endif
                        </div>
                        @endforeach
                    @endif
                    <div class="header-right-mob">
                        <!--<div class="li-search">
                            <form action="{{ route('search.full') }}" class="li-form" method="get">
                                <input type="text" name="name" placeholder="Поиск по сайту" class="li-form-input">
                                <button class="li-search__submit" type="submit"></button>
                            </form>
                        </div>-->
                        <a href="{{route('front.page.news.add')}}" class="button button-green add-news">добавить свою новость</a>
                        <div class="news-subscribe">
                            <div class="news-subscribe__title">Подписка на новости</div>
                            <form action="#" class="news-subscribe__body">
                                <input type="text" name="jlsjd" placeholder="user@site.com">
                                <button class="button button-micro button-dark-blue">подписаться</button>
                            </form>
                        </div>
                    </div>
                    @if (!empty($dayTopic))
                        <div class="day-news">
                            <div class="day-news__inner height-covered">
                                <div class="day-news__title">Тема дня</div>
                                <div class="day-news__preamble">
                                    <a href="{{route('front.page.topic.page', ['url_en' => $dayTopic->url_en])}}">{{$dayTopic->title}}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    {{--Start Main Menu--}}
                    @include('front.partials.menu-main')
                    {{--End Main Menu--}}
                </div>
            </div>
        </div>
    </div>
</header>

@include('front.partials.errors-success')

<main class="content">
    <div class="container">
        <div class="row">
            @if (!empty($content_page['crumbs']) && request()->route()->getName() !== 'front.home')
                @include('front.partials.breadcrumbs', ['crumbs' => $content_page['crumbs']])
            @endif
            @include('front.partials.mobile-menu')
        </div>
    </div>

    <div class="container main-content-contents">
        <div class="row">
            @yield('content')
            <aside class="right-sidebar col-xl-3 col-lg-4">
                @yield('sidebar-right')
            </aside>
        </div>
    </div>
</main>
<footer class="footer">
    <div class="footer-top">
        {{--Start Footer Menu--}}
        @include('front.partials.menu-footer')
        {{--End Footer Menu--}}
    </div>
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p>Редакция портала ЛюдиИпотеки.рф не несет ответственности за мнения размещенные в комментариях и информацию, размещенную в новостях. Мнения участников может не совпадать с мнением редакции. При перепечатке материалов портала ЛюдиИпотеки.рф необходимо указывать сайт в качестве источника с активной гиперссылкой.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row footer-bottom__inner">
                <div class="col-xl-5 col-lg-6 col-md-5 footer-copyright">
                    <div>© 2011-2020 ЛюдиИпотеки.рф — сообщество участников ипотечного рынка</div>
                    <div>Все права защищены</div>
                </div>
                <div class="footer-bottom__column">
                    <ul class="footer-bottom__menu mnu-standart-style-reset">
                        <li><a href="/main/content/page/privacy-policy">Политика конфиденциальности</a></li>
                        <li><a href="/main/content/page/terms">Пользовательское соглашение</a></li>
                        <li><a href="/main/content/page/news-add-rules">Правила размещения</a></li>
                    </ul>
                </div>
                <div class="footer-bottom__column">
                    <ul class="footer-bottom__menu mnu-standart-style-reset">
                        <li><a href="/main/content/page/advert">Реклама на сайте</a></li>
                        <li><a href="#">Аудитория и статистика</a></li>
                        <li><a href="/main/feedback">Контакты</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 footer-statistic">
					<noindex>
					 
					<!-- begin of Top100 code -->
					<script id="top100Counter" type="text/javascript" src="http://counter.rambler.ru/top100.jcn?2570189"></script>
					<noscript>
					<a href="http://top100.rambler.ru/navi/2570189/">
					<img src="http://counter.rambler.ru/top100.cnt?2570189" alt="Rambler's Top100" border="0"  style="margin:5px"/>
					</a>
					</noscript>
					<!-- end of Top100 code -->
					 

					<!--LiveInternet counter--><script type="text/javascript"><!--
					document.write("<a href='http://www.liveinternet.ru/click' "+
					"target=_blank><img src='//counter.yadro.ru/hit?t13.11;r"+
					escape(document.referrer)+((typeof(screen)=="undefined")?"":
					";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
					screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
					";"+Math.random()+
					"' alt='' title='LiveInternet: показано число просмотров за 24"+
					" часа, посетителей за 24 часа и за сегодня' "+
					"border='0' width='88' height='31'  style='margin:5px'><\/a>")
					//--></script><!--/LiveInternet-->

					 
					<!--Rating@Mail.ru counter-->
					<script language="javascript"><!--
					d=document;var a='';a+=';r='+escape(d.referrer);js=10;//--></script>
					<script language="javascript1.1"><!--
					a+=';j='+navigator.javaEnabled();js=11;//--></script>
					<script language="javascript1.2"><!--
					s=screen;a+=';s='+s.width+'*'+s.height;
					a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth);js=12;//--></script>
					<script language="javascript1.3"><!--
					js=13;//--></script><script language="javascript" type="text/javascript"><!--
					d.write('<a href="http://top.mail.ru/jump?from=2108678" target="_top">'+
					'<img src="http://dd.c2.b0.a2.top.mail.ru/counter?id=2108678;t=57;js='+js+
					a+';rand='+Math.random()+'" alt="Рейтинг@Mail.ru" border="0" '+
					'height="31" width="88"><\/a>');if(11<js)d.write('<'+'!-- ');//--></script>
					<noscript><a target="_top" href="http://top.mail.ru/jump?from=2108678">
					<img src="http://dd.c2.b0.a2.top.mail.ru/counter?js=na;id=2108678;t=57" 
					height="31" width="88" border="0" alt="Рейтинг@Mail.ru"></a></noscript>
					<script language="javascript" type="text/javascript"><!--
					if(11<js)d.write('--'+'>');//--></script>
					<!--// Rating@Mail.ru counter-->
					</noindex>    
				</div>
            </div>
        </div>
    </div>
</footer>
@if (auth()->check() == false)
    @include('front.partials.auth-form')
    @include('front.partials.forgot-password')
@endif

<script src="{{asset('/js/app.js')}}"></script>
<script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{asset('/js/common.js')}}"></script>
<script src="{{asset('/js/libs/superfish/dist/js/hoverIntent.js')}}"></script>
<script src="{{asset('/js/libs/superfish/dist/js/superfish.js')}}"></script>
<script src="{{asset('/js/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('/js/libs/slick-master/slick.min.js')}}"></script>
<script src="{{asset('/js/libs/jsscrollpane/js/jquery.mousewheel.js')}}"></script>
<script src="{{asset('/js/libs/jsscrollpane/js/jquery.jscrollpane.min.js')}}"></script>
<script src="{{asset('/js/libs/wnumb/wNumb.js')}}"></script>
<script src="{{asset('/js/libs/noUiSlider/nouislider.min.js')}}"></script>
<script src="//ulogin.ru/js/ulogin.js"></script>
<script src="{{asset('/js/swfobject.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.banner_click').on('click', function () {
            let link = $(this).attr('href');
            let id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: '{{route('count.link')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    val: link,
                    id: id,
                },
            });
        });


    });
    function uloginCallback(token){
        $.getJSON("//ulogin.ru/token.php?host=" +
            encodeURIComponent(location.toString()) + "&token=" + token + "&callback=?",
            function(data){
                data=$.parseJSON(data.toString());
                if(!data.error){
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.login.ulogin') }}",
                        data: {
                            _token:"{{ csrf_token() }}",
                            data: data
                        },
                        success: function() {
                            location.reload();
                        },
                    });
                }
            });
    }
</script>
@yield('js_footer')
</body>
</html>