@extends('front.layouts.app')
@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                @if (!empty($_COOKIE['payrbk']))
                    @if ($_COOKIE['payrbk'] == 'fall')
                        <div class="container" id="error">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-success">
                                        <ul class="m-auto">
                                            <li>Отслеживайте статус Вашего заказа в <a href="{{ route('front.setting.account', ['type' => 'purchase']) }}">Личном кабинете</a></li>
                                        </ul>
                                        <button type="button" class="close" onclick="$('#error').css('display', 'none')">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.cookie = 'payrbk=null';
                        </script>
                    @endif
                    @if ($_COOKIE['payrbk'] == 'success')
                        <div class="container" id="error">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-success">
                                        <ul class="m-auto">
                                            Оплата прошла успешно. Ожидайте письмо на ваш email.
                                        </ul>
                                        <button type="button" class="close" onclick="$('#error').css('display', 'none')">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <script>
                                document.cookie = 'payrbk=null';
                            </script>
                    @endif
                @endif
                <div class="col-12">
                    <div class="news-content">
                        <div class="title-block title-block--iconed">
                            <h1>Корзина</h1>
                        </div>
                        <div class="blog-post__body">
                            <div class="companies-list">
                                @if (!empty($researchesCart))
                                <table>
                                    <tbody>
                                    @forelse($researchesCart->purchases as $purchases)
                                        <tr class="companies-list__desktop-info">
                                            <td class="companies-list__avatar">
                                                <a href="#" class="companies-list__img">
                                                    <img src="{{ $purchases->research->image ?? '/img/no_picture.jpg'}}" alt="alt">
                                                </a>
                                            </td>
                                            <td class="companies-list__title">
                                                <h2><a href="{{route('front.page.shop.researches.category.entry', ['id' => $purchases->research->id])}}" class="li-link-blue">{{ $purchases->research->title }}</a></h2>
                                            </td>
                                            <td>
                                                <span class="research-card__price-val">{{ $purchases->research->price }} руб</span>
                                            </td>
                                            <td>
                                                <div class="research-card__buttons">
                                                    <form id="form_{{ $purchases->id }}" method="POST" action="{{ route('front.shop.researches.shopping.delete.cart', ['id' =>  $purchases->id]) }}">
                                                        @csrf
                                                    </form>
                                                    <a href="javascript:;" onclick="$('#form_{{ $purchases->id }}').submit();" class="button">удалить</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        Корзина пуста.
                                    @endforelse
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right pt-4"><span class="research-card__price-val">Итого: {{ $researchesTotal ?? '0' }} руб</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                @else
                                    Корзина пуста.
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (!empty($researchesCart))
                    <div class="research-card__buttons mt-3">
                        <div class="content-buttons">
                            <a href="javascript:" id="button_expert" class="button" data-tab="expert">Физическое лицо</a>
                            <a href="javascript:" id="button_company" class="button button-dark-blue" data-tab="company"> Юридическое лицо</a>
                        </div>
                        <div class="tab-expert active">
                            <label class="col-12">
                                <input name="agree_paid" id="agree_paid" type="checkbox">
                                <span><a target="_blank" href="/main/content/page/shop-offer">Условия договора-оферты принимаю</a><span class="li-form-required">*</span></span>
                            </label>
								<img src="/img/pay/mir.png" alt="Мир" style="width:50px;">
								<img src="/img/pay/mk.png" alt="Мастер кард" style="width:50px;">
								<img src="/img/pay/rbk.png" alt="РБК" style="width:50px;">
								<img src="/img/pay/visa.png" alt="Виза" style="width:50px;">
                            <label id="agree_error" class="error col-12" for="first_name" style="display: none">Необходимо согласиться с условиями.</label>
                            <input type="hidden" name="cart" value="{{ $researchesCart->id }}">
                            <div id="rbk" class="col-auto" style="width: -moz-max-content;">
                                <button class="button -green center" id="customButton">Оплатить с карты</button>
                            </div>
                            <script src="https://checkout.rbk.money/checkout.js"></script>
                        </div>
                        <div class="tab-company" style="display: none;">
                            <form action="{{ route('front.shop.researches.shopping.checkout', ['type' => 'company']) }}" class="li-form li-form--colored">
                                @csrf
                                <input type="hidden" name="shopping_cart_id" value="{{ $researchesCart->id }}">

                                <label class="li-form-label li-form-label--std-width-reg">

                                    <span class="li-form-label-name" id="company-label">Полное наименование компании<span class="li-form-required">*</span></span>
                                    <input name="company" id="company" type="text" class="li-form-input form-control" value="" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">Юридический адрес<span class="li-form-required">*</span></span>
                                    <input name="legal_address" id="legal_address" type="text" class="li-form-input form-control" value="" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">Почтовый адрес<span class="li-form-required">*</span></span>
                                    <input name="postal_code" id="postal_code" type="text" class="li-form-input form-control" value="" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">ИНН<span class="li-form-required">*</span></span>
                                    <input name="inn" id="inn" type="text" class="li-form-input form-control" value="" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">КПП<span class="li-form-required">*</span></span>
                                    <input name="kpp" id="kpp" type="text" class="li-form-input form-control" value="" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">ФИО контактного лица<span class="li-form-required">*</span></span>
                                    <input name="name" id="name" type="text" class="li-form-input form-control" value="{{ auth()->user()->name ?? ''}}" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">Должность<span class="li-form-required">*</span></span>
                                    <input name="position" id="position" type="text" class="li-form-input form-control" value="{{ auth()->user()->socialProfile->company_post ?? '' }}" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">Телефон с кодом города<span class="li-form-required">*</span></span>
                                    <input name="phone" id="phone" type="text" class="li-form-input form-control" value="{{ auth()->user()->socialProfile->mobile_phone ?? ''}}" required="">
                                </label>
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="email-label">E-Mail<span class="li-form-required">*</span></span>
                                    <input name="email" id="email" type="email" class="li-form-input form-control" value="{{ auth()->user()->email }}" required="">
                                </label>
                                    <label class="h-mt-20">
                                        <input name="agree" type="checkbox" value="1" required="">
                                        <span id="error-agree"><a target="_blank" href="/main/content/page/shop-offer">Условия договора-оферты принимаю</a><span class="li-form-required">*</span></span>
                                    </label>
								<br />
								<img src="/img/pay/mir.png" alt="Мир" style="width:50px;">
								<img src="/img/pay/mk.png" alt="Мастер кард" style="width:50px;">
								<img src="/img/pay/rbk.png" alt="РБК" style="width:50px;">
								<img src="/img/pay/visa.png" alt="Виза" style="width:50px;">
                                <div class="h-clearfix h-mt-20">
                                    <button class="button button-dark-blue">Оплатить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
            @if(!empty($researchesCart))
            const checkout = RbkmoneyCheckout.configure({
                invoiceID: '{{trim($rbkInvoiceId)}}',
                invoiceAccessToken: '{{trim($rbkInvoiceToken)}}',
                name: '{{ config('app.name') }}',
                description: 'Заказ № И-{{ $researchesCart->id }}',
                popupMode: false,
                opened: function () {

                },
                closed: function () {
                    document.cookie = 'payrbk=fall';
                    location.reload();
                },
                finished: function () {
                    document.cookie = 'payrbk=success';
                    location.reload();
                }
            });
            document.getElementById('customButton').addEventListener('click', function() {
                $.ajax({
                    type: "POST",
                    url: '{{route('front.shop.researches.shopping.check_paid')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        cart: '{{ $researchesCart->id }}',
                    },
                    success: function (data) {
                        checkout.open();
                    }
                });

            });
            window.addEventListener('popstate', function() {
                checkout.close();
            });
            @endif
            $(".content-buttons a").on('click', function (event) {
                let index = $(event.target).data('tab');
                $("div.active").css('display', 'none').removeClass('active');
                $(".tab-" + index).addClass('active').css('display', 'block');
            });
            let agre = $('#agree_paid');
            checkBox(agre);
            agre.on('change', function () {
                checkBox($(this));
            });
            function checkBox(val) {
                if (val.is(':checked') === true) {
                    $('#customButton').attr('disabled', false).css('z-index', '1')
                } else {
                    $('#customButton').attr('disabled', true).css('z-index', '-1')
                }
            }
            $('#rbk').on('click', function () {
                if (agre.is(':checked') === true)  {
                    $('#agree_error').hide()
                } else {
                    $('#agree_error').show()
                }

            });
            $('#email').on('keyup', function(e) {
                validateCustom($(this), 'email')
            });
            function validateCustom(val, type){
                let error = '<label class="error_custom">Поле заполнено некорректно.</label>';
                let reg = '';
                if (type === 'email') {
                    reg = /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/;
                } else if (type === 'site')  {
                    reg = /^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i;
                }
                if(reg.test(val.val()) === false){
                    if (val.parent().find(".error_custom")[0] == null) {
                        $(error).insertAfter(val);
                        //$('button[type=submit]').attr('disabled', true)
                    }
                    return false;
                } else {
                    val.parent().find(".error_custom").remove();
                    //$('button[type=submit]').attr('disabled', false);
                    return true;
                }

            }
            $("form.li-form--colored").validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    if (element.is(":checkbox")) {
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));  // custom placement example
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        })

    </script>
@endsection