@extends('front.layouts.app')

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="title-block title-block--bl">
                        <h2>Исследования рынка</h2>
                    </div>
                    <div class="research-card li-card-block">
                        <div class="research-card__img">
                            <img src="{{ $research->image  ?? '/img/no_picture.jpg' }}" alt="{{ $research->title }}">
                        </div>
                        <div class="research-card__content">
                            <h1>{{ $research->title }}</h1>
                            <div class="research-card__columns">
                                @if (!empty($research->author))
                                    <div class="company-info__block">
                                        <div class="company-info__bage">Автор</div><a href="{{ route('front.page.shop.author', ['id' => $research->author->id]) }}" class="li-link">{{ $research->author->title }}</a>
                                    </div>
                                @endif
                                @if (!empty($research->page))
                                    <div class="company-info__block">
                                        <div class="company-info__bage">Количество страниц</div>
                                        <div class="company-info__descr">{{ $research->page }}</div>
                                    </div>
                                @endif
                                @if (!empty($research->language))
                                    <div class="company-info__block">
                                        <div class="company-info__bage">Язык</div>
                                        <div class="company-info__descr">{{ $research->language }}</div>
                                    </div>
                                @endif
                                @if (!empty($research->published_at))
                                <div class="company-info__block">
                                    <div class="company-info__bage">Дата выхода</div>
                                    <div class="company-info__descr">{{ \Carbon\Carbon::parse($research->published_at)->isoFormat('DD MMMM YYYY') }}</div>
                                </div>
                                @endif
                                @if (!empty($research->format))
                                <div class="company-info__block">
                                    <div class="company-info__bage">Формат</div>
                                    <div class="company-info__descr">{{ $research->format }}</div>
                                </div>
                                @endif
                            </div>
                            <div class="research-card__order-block">
                                <div class="research-card__price"><span>Стоимость:</span>
                                    @if (!empty($research->price))
                                        <span class="research-card__price-val">{{ $research->price }} <span class="li-rub">руб.</span>
                                        </span>
                                    @else
                                        <span class="research-card__price-val">Бесплатно</span>
                                    @endif
                                </div>
                                <div class="research-card__buttons">
                                    @if (!empty($research->price))
                                        @if (auth()->check())
                                            <form id="form_{{$research->id}}" method="POST" action="{{ route('front.shop.researches.shopping.add.cart', ['id' => $research->id]) }}">
                                                @csrf
                                            </form>
                                            <form id="form_demo_{{$research->id}}" method="POST" action="{{route('front.page.shop.researches.download.demo')}}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$research->id}}">
                                            </form>
                                            <a href="javascript:;" onclick="$('#form_{{$research->id}}').submit();" class="button">купить</a>
                                            @if ($research->demo_file)
                                                <a href="javascript:;" class="button button-dark-blue" onclick="$('#form_demo_{{$research->id}}').submit();">скачать демо-версию</a>
                                            @endif
                                        @else
                                            <a href="#li-enter-popup" class="button to-popup">купить</a>
                                            @if ($research->demo_file)
                                            <a href="#li-enter-popup" class="button button-dark-blue to-popup">скачать демо-версию</a>
                                            @endif
                                        @endif
                                    @else
                                        @if (auth()->check())
                                            <form id="form_free_{{$research->id}}" method="POST" action="{{route('front.page.shop.researches.download.free')}}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$research->id}}">
                                            </form>
                                            <a href="javascript:;" onclick="$('#form_free_{{$research->id}}').submit();" class="button button-dark-blue">скачать</a>
                                        @else
                                            <a href="#li-enter-popup" class="button button-dark-blue to-popup">скачать</a>
                                        @endif
                                    @endif

                                </div>
                            </div>
                            <div class="blog-share"><span class="blog-share__title">поделитесь с друзьями</span>
                                <script>
                                    (function() {
                                        if (window.pluso)if (typeof window.pluso.start == "function") return;
                                        if (window.ifpluso==undefined) { window.ifpluso = 1;
                                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                            s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                                            s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                                            var h=d[g]('body')[0];
                                            h.appendChild(s);
                                        }})();

                                </script>
                                <div data-background="none;" data-options="small,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,facebook,odnoklassniki,twitter,linkedin" class="pluso" data-description="{{ $research->title }}" data-title="{{ $research->annotation ?? '' }}"></div>
                            </div>
                        </div>
                    </div>
                    <div class="research-content">
                        <div class="li-tabs li-tabs--designed tabs-wrapper">
                            <ul class="tabs__list mnu-standart-style-reset">
                                <li class="tabs__item tabs__item--active"><a href="#" data-tab="1" class="tabs__link">Аннотация</a></li>
                                @if (!empty($research->content))
                                <li class="tabs__item"><a href="#" data-tab="2" class="tabs__link">Содержание</a></li>
                                @endif
                                @if (!empty($research->table))
                                <li class="tabs__item"><a href="#" data-tab="3" class="tabs__link">Графики и таблицы</a></li>
                                @endif
                                <li class="tabs__item"><a href="#" data-tab="4" class="tabs__link">Задать вопрос</a></li>
                            </ul>
                            <ul class="tabs__content">
                                <li data-tab="1" class="tabs__item">
                                    <div class="tabs-designed-content">{!! $research->annotation !!}</div>
                                </li>
                                @if (!empty($research->content))
                                    <li data-tab="2" class="tabs__item hidden">
                                        <div class="tabs-designed-content">
                                            {!! $research->content !!}
                                        </div>
                                    </li>
                                @endif
                                @if (!empty($research->table))
                                    <li data-tab="3" class="tabs__item hidden">
                                        <div class="tabs-designed-content">
                                            {!! $research->table !!}
                                        </div>
                                    </li>
                                @endif

                                <li data-tab="4" class="tabs__item hidden">
                                    <form method="post" action="{{ route('front.page.shop.researches.feedback', ['id' => $research->id]) }}" class="discussion-form li-form">
                                        @csrf
                                        <div class="discussion-form__top">
                                            <label class="li-form-label"><span class="li-form-label-name"><span class="li-form-required">*</span> Имя и Фамилия:</span>
                                                <input type="text" name="name" class="li-form-input" value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                                            </label>
                                            <label class="li-form-label"><span class="li-form-label-name"><span class="li-form-required">*</span> E-mail:</span>
                                                <input type="text" name="email" class="li-form-input" value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                                            </label>
                                        </div>
                                        <label class="li-form-label"><span class="li-form-label-name"><span class="li-form-required">*</span> Ваш вопрос</span>
                                            <textarea name="text" cols="30" rows="10" class="li-form-area" required></textarea>
                                        </label>
                                        <div class="li-form__buttons">
                                            <button type="submit" class="button button-mini button-l-blue">ОТПРАВИТЬ</button>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="research-similar">
                        <div class="title-upcase-bl">Похожие исследования</div>
                        <div class="li-tiles">
                            @forelse($relatedResearch as $research)
                            <div class="li-tile">
                                <div class="li-tile-img"><img src="{{ $research->image ?? '/img/no_picture.jpg' }}" alt="{{ $research->title }}"></div>
                                <div class="li-tile-content"><a href="{{ route('front.page.shop.researches.category.entry', ['id' => $research->id]) }}" class="li-tile-title li-link">{{ $research->title }}</a>
                                    <div class="li-tile-price">
                                        @if ($research->price == '0')
                                            бесплатно
                                        @else
                                            {{ $research->price }} <span>руб.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            Похожих исследований нет
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('successAddCart'))
    {{--modal success add cart--}}
    <div class="modal fade" id="successAddCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>{{ session()->get('successAddCart') }}</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" data-dismiss="modal">Продолжить выбор</button>
                    <a href="{{ route('front.shop.researches.shopping.cart') }}" class="button button-dark-blue">Оформить заказ</a>
                </div>
            </div>
        </div>
    </div>
    {{--modal success add cart--}}
    @endif
@endsection

@section('sidebar-right')
    @include('front.sidebar_module.shop-category-menu-block')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection

@section('js_footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            @if (session()->has('successAddCart'))
            $('#successAddCart').modal('show');
            @endif
            $('.discussion-form').validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
            });
        })
    </script>
@endsection