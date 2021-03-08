@extends('front.layouts.app')

@section('header_style')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="title-block title-block--iconed">
                        <h1>{{$content_page['title']}}</h1>
                    </div>
                    <p>
                    Вы можете связаться с Администрацией сайта, оставив сообщение по интересующей вас тематике. Обращаем
                    внимание, что неправильно выбранная тема, осложнит получение вами ответа. Вопросы, связанные с
                    размещением рекламных/заказных статей, а также размещением платных ссылок, будут игнорироваться.
                    </p>
                    <div class="content-form h-mt-10">
                        <form name="form" id="feedback-form" action="{{route('front.page.feedback.send')}}" method="post" class="li-form li-form--colored">
                            @csrf
                            @if (!auth()->check())
                                <label class="li-form-label li-form-label--std-width-reg">
                                <span class="li-form-label-name" id="last_name-label">Фамилия <span class="li-form-required">*</span></span>
                                    <input name="last_name" type="text" class="li-form-input" value="{{old('last_name')}}" required>
                                </label>

                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name" id="first_name-label">Имя <span class="li-form-required">*</span></span>
                                    <input name="first_name" type="text" class="li-form-input" value="{{old('first_name')}}" required>
                                </label>

                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name">Компания</span>
                                    <input name="company" type="text" class="li-form-input" value="{{old('company')}}">
                                </label>

                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name">E-mail <span class="li-form-required">*</span></span>
                                    <input name="email" id="email" type="text" class="li-form-input" value="{{old('email')}}" required>
                                </label>

                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name">Телефон (с кодом)</span>
                                    <input name="phone" id="phone" type="text" class="li-form-input" value="{{old('phone')}}">
                                </label>

                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name">Тема сообщения <span class="li-form-required">*</span></span>
                                    <select autocomplete="off" name="subject" class="li-form-select" id="ui-id-1" required>
                                        @forelse($subjects as $subject)
                                            <option value="{{$subject->id}}">{{old('$subject') ?? $subject->subject}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </label>

                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name">Сообщение <span class="li-form-required">*</span></span>
                                    <textarea id="text" name="text" class="form-control" rows="4" cols="60" required></textarea>
                                </label>

                            @else
                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name">Тема сообщения <span class="li-form-required">*</span></span>
                                    <select autocomplete="off" name="subject" class="li-form-select" id="ui-id-1">
                                        @forelse($subjects as $subject)
                                            <option value="{{$subject->id}}">{{$subject->subject}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </label>

                                <label class="li-form-label li-form-label--std-width-reg">
                                    <span class="li-form-label-name">Сообщение <span class="li-form-required">*</span></span>
                                    <textarea id="text" name="text" class="form-control" rows="4" cols="60" required>{{ old('text') }}</textarea>
                                </label>
                            @endif
                            {!! app('captcha')->display() !!}
                            <label id="recaptcha-error" style="display: none; color: red" for="recaptcha-error">Необходимо пройти капчу.</label>
                            <div class="h-clearfix h-mt-20">
                                <button type="submit" class="button button-dark-blue h-left">Отправить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection

@section('js_footer')
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/js/jquery.mask.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#phone').mask('+0(000)000-00-00');
            $.validator.addMethod("customemail",
                function(value, element) {
                    return /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/.test(value);
                },
                "Некорректный email адрес."
            );
            $("#feedback-form").validate({
                lang: 'ru',
                rules: {
                    email: {
                        required: true,
                        customemail: true,
                    },
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                invalidHandler: function(event, validator) {
                    let response = grecaptcha.getResponse();
                    //recaptcha failed validation
                    if (response.length == 0) {
                        $('#recaptcha-error').attr('style', 'color: red');
                        return false;
                    }
                    //recaptcha passed validation
                    else {
                        $('#recaptcha-error').attr('style', 'display: none; color: red');
                        return true;
                    }
                },
                submitHandler: function () {
                    let response = grecaptcha.getResponse();
                    //recaptcha failed validation
                    if (response.length == 0) {
                        $('#recaptcha-error').attr('style', 'color: red');
                        return false;
                    }
                    //recaptcha passed validation
                    else {
                        $('#recaptcha-error').attr('style', 'display: none; color: red');
                        return true;
                    }
                }
            });
        })
    </script>
@endsection