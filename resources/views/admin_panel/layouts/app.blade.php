<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name')}} - Панель администратора</title>
    {{--Bootstrap--}}
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/assets/template/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/assets/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/assets/template/css/AdminLTE.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/template/css/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">

    @yield('section_header')

</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard.index') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{{ config('app.name')}}</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    @if (false)
                        @yield('notifications')
                    @endif
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">{{ Auth::user()->email }}</span>
                        </a href="#">
                        <ul class="dropdown-menu">
                            <li class="user-body">
                                <div class="row">
                                    <div class="text-center">
                                        <a href="#">Вы авторизованы как {{ Auth::user()->email }}</a>
                                    </div>
                                </div>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <!-- <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Профиль</a>
                                </div>-->
                                <div class="pull-right">
                                    <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat">Выйти</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree" data-accordion="false">

                @if(Gate::allows('is-admin', auth()->user()))
                    <li class="treeview menu-open">
                            <a href="#">
                                <i class="fa fa-users"></i> <span>Пользователи</span>
                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu" style="display: block">
                                @if (Gate::allows('is-admin', auth()->user()))
                                <li><a href="{{route('admin.admins.index')}}"><i class="fa fa-circle-o"></i><span>Редакторы/Админы</span></a>
                                </li>
                                <li><a href="{{route('admin.users.index')}}"><i class="fa fa-circle-o"></i><span>Эксперты/Компании</span></a>
                                </li>
                                @endif
                                @if (Gate::allows('permission', 'messages'))
                                    <li><a href="{{route('admin.users.messages.index')}}"><i class="fa fa-circle-o"></i><span>Массовая рассылка</span></a>
                                    </li>
                                @endif
                            </ul>
                    </li>
                @endif

                @if (Gate::allows('permission', 'all_news') || Gate::allows('any-category') || Gate::allows('permission', 'news'))
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa  fa-bolt"></i> <span>Новости</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu" style="display: block">
                            @if (Gate::allows('permission', 'news'))
                                <li><a href="{{route('admin.category.index')}}"><i class="fa fa-circle-o"></i>Категории
                                        новостей</a></li>
                                <li><a href="{{route('admin.scene-group.index')}}"><i class="fa fa-circle-o"></i>Сюжетные
                                        группы</a></li>
                                <li><a href="{{route('admin.scene.index')}}"><i class="fa fa-circle-o"></i>Сюжеты</a>
                                </li>
                            @endif
                            @if (Gate::allows('any-category') || Gate::allows('permission', 'all_news'))
                                    <li><a href="{{route('admin.news.index')}}"><i class="fa fa-circle-o"></i>Новости</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('permission', 'shop'))
                <li class="treeview menu-open">
                    <a href="#">
                        <i class="fa  fa-bolt"></i> <span>Магазин исследований</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu" style="display: block">
                        @if (Gate::allows('is-admin', auth()->user()))
                            <li><a href="{{route('admin.shop.researches.category')}}"><i class="fa fa-circle-o"></i>Категории
                                    исследований</a></li>
                            <li><a href="{{route('admin.shop.researches.authors')}}"><i class="fa fa-circle-o"></i>Авторы исследований</a></li>
                            <li><a href="{{route('admin.shop.researches')}}"><i class="fa fa-circle-o"></i>Исследования</a></li>
                        @endif


                        @if (Gate::allows('permission', 'shop'))
                        <li><a href="{{route('admin.shop.researches.orders')}}"><i class="fa fa-circle-o"></i>Заказы</a></li>
                        @endif

                        @if (Gate::allows('is-admin', auth()->user()))
                        <li><a href="{{route('admin.shop.researches.buyers')}}"><i class="fa fa-circle-o"></i>Покупатели</a></li>
                        <li><a href="{{route('admin.shop.researches.settings.templates')}}"><i class="fa fa-circle-o"></i>Настройки шаблонов</a></li>
                        <li><a href="{{route('admin.shop.researches.settings.bank.detail')}}"><i class="fa fa-circle-o"></i>Реквизиты Русипотеки</a></li>
                        <li><a href="{{route('admin.shop.researches.settings.metatags')}}"><i class="fa fa-circle-o"></i>Метатеги</a></li>
                        @endif
                    </ul>
                </li>
                @endif


                @if (Gate::allows('is-admin', auth()->user()))
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa fa-envelope-o"></i> <span>Новостная рассылка</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu" style="display: block">
                            <li><a href="{{route('admin.newsletter.index')}}"><i class="fa fa-circle-o"></i>Настройки рассылки</a></li>
                            <li><a href="{{route('admin.newsletter.ads.offers')}}"><i class="fa fa-circle-o"></i>Объявления и предложения</a></li>
                            <li><a href="{{route('admin.newsletter.news.index')}}"><i class="fa fa-circle-o"></i>Новости блогов</a></li>
                            <li><a href="{{route('admin.newsletter.show')}}"><i class="fa fa-circle-o"></i>Предпросмотр следующей рассылки</a></li>
                            <li><a href="{{route('admin.newsletter.subscribers.index')}}"><i class="fa fa-circle-o"></i>Подписчики</a></li>
                            <li><a href="{{route('admin.newsletter.unsubscribed.index')}}"><i class="fa fa-circle-o"></i>Отписались</a></li>
                        </ul>
                    </li>
                @endif


                @if (Gate::allows('permission', 'blogs'))
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa  fa-bolt"></i> <span>Блоги</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu" style="display: block">
                            <li>
                                <a href="{{route('admin.blogs.index')}}">
                                    <i class="fa fa-file-text-o"></i><span>Блоги</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('admin.posts.all.index')}}"><i class="fa fa-file-text-o"></i>Записи
                                    блогов</a>
                            </li>
                            <li>
                                <a href="{{route('admin.comments.all.index')}}"><i class="fa fa-file-text-o"></i>Комментарии
                                    блогов</a>
                            </li>
                            <li>
                                <a href="{{route('admin.subscriber.index')}}"><i class="fa fa-file-text-o"></i>Подписчики
                                    блогов</a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if (Gate::allows('is-admin', auth()->user()))
                    <li>
                        <a href="{{route('admin.anons.index')}}">
                            <i class="fa  fa-bolt"></i> <span>Мероприятия</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.text.page.index')}}">
                            <i class="fa  fa-bolt"></i> <span>Текстовые страницы</span>
                        </a>
                    </li>
                @endif

                @if (Gate::allows('permission', 'topics'))
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa  fa-bolt"></i> <span>Тема</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu" style="display: block">
                            <li>
                                <a href="{{route('admin.topic.index')}}">
                                    <i class="fa fa-file-text-o"></i> <span>Все темы</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('admin.answer.index')}}">
                                    <i class="fa fa-file-text-o"></i> <span>Ответы</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('is-admin', auth()->user()))
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa fa-cogs"></i> <span>Баннеры и Виджеты</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu" style="display: block">
                            <li>
                                <a href="{{route('admin.banner.index')}}">
                                    <i class="fa  fa-bolt"></i><span>Баннеры</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('admin.banner.setting')}}"><i class="fa fa-circle-o"></i>Настройка</a>
                            </li>
                            <li>
                                <a href="{{route('admin.widgets.index')}}"><i class="fa fa-circle-o"></i>Виджеты</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('is-admin', auth()->user()))
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa fa-cogs"></i> <span>Настройки</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu" style="display: block">
                            <li>
                                <a href="{{ route('admin.resources.mail.templates') }}"><i class="fa fa-circle-o"></i>Шаблоны
                                    писем</a>
                            </li>
                            <li class="treeview menu-open">
                                <a href="#">
                                    <i class="fa fa-list"></i> <span>Справочники</span>
                                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu" style="display: block">
                                    <li><a href="{{route('admin.resources.company')}}"><i class="fa fa-circle-o"></i>Компании</a>
                                    </li>
                                    <li><a href="{{route('admin.resources.company.type')}}"><i class="fa fa-circle-o"></i>Сферы деятельности</a></li>
                                    <li><a href="{{route('admin.resources.countries')}}"><i class="fa fa-circle-o"></i>Страны/Регионы</a>
                                    </li>
                                    <li><a href="{{route('admin.resources.city')}}"><i class="fa fa-circle-o"></i>Города</a>
                                    </li>
                                    <li><a href="{{route('admin.resources.tags')}}"><i class="fa fa-circle-o"></i>Теги</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="{{route('admin.resources.debug.mode')}}">
                                    <i class="fa fa-circle-o"></i>Режим отладки</a>
                            </li>
                            <li>
                                <a href="{{route('admin.resources.data.template')}}"><i class="fa fa-circle-o"></i>Данные
                                    в шаблоне</a>
                            </li>
                            <li>
                                <a href="{{route('admin.resources.metatags')}}"><i class="fa fa-circle-o"></i>Метатеги</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('is-admin', auth()->user()))
                    <li class="treeview menu-open">
                        <a href="#">
                            <i class="fa fa-file-text-o"></i> <span>Обратная связь</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu" style="display: block">
                            <li>
                                <a href="{{route('admin.feedback.index')}}">
                                    <i class="fa fa-file-text-o"></i> <span>Темы сообщений</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">

            <h1>@yield('section_title')</h1>

            @yield('section_breadcrumbs')

        </section>

        <section class="content" style='height:100%'>
            <div class="row">

                @if ($errors->any() || session()->has('success'))
                    @include('admin_panel.admins.partials.errors-success')
                @endif

                @yield('content')

            </div>
        </section>
    </div>

    <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
{{--Bootstrap JS--}}
<script src="{{asset('/js/app.js')}}"></script>
{{--AdminLTE JS--}}
<script src="{{ asset('/assets/template/js/adminlte.min.js') }}"></script>

@yield('footer_js')
<script>
    $(document).ready(function() {
        $('.sidebar-menu').on('load.tree', function() {
            console.log('tree loaded');
        });
    });
</script>

</body>
</html>