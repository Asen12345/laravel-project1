@extends('front.layouts.app')

@section('header_style')
@endsection

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12 blog-page-container">
                    <div class="title-block">
                        <h1 class="block-title__text">Добавить новость</h1>
                    </div>
					<p>Добавить новость на сайт Вы можете воспользовавшись формой ниже или прислать ее нам на адрес news(at)ludiipoteki.ru.</p>
					Выберите раздел новостной ленты: <span class="li-form-required">*</span>
                    <form id="newsAddForm" class="news-add-form li-form li-form--colored" action="{{route('front.page.news.store')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="row h-mt-10">
                        @foreach($content_page['categories_collection'] as $category)
                            <div class="col-sm-4">
                                <label class="li-form-label companies-form__name">
                                    <input type="radio" name="news_category_id" class="li-form-input" value="{{$category->id}}" {{old('news_category_id') == $category->id ? 'checked' : ''}}>
                                    <span>{{$category->name}}</span>
                                </label>
                            </div>
                            @if($loop->iteration % 3 == 0 && !$loop->last)</div><div class="row h-mt-10">@endif
                        @endforeach
                        </div>
                        <span id="news_category_id"></span>
						
                        <div class="row h-mt-20">
                            <div class="col-12">
                                <label for="name">Название<span class="li-form-required">*</span></label>
                            </div>
                            <div class="col-12">
                                <input id="name" type="text" name="name" style="width:100%" value="{{old('name')}}" required>
                            </div>
                        </div>


                        <div class="row h-mt-20">
                            <div class="col-12">
                                <label for="text">Текст новости<span class="li-form-required">*</span></label>
                            </div>
                            <div class="col-12">
                                <textarea rows='10' id="text" style="width:100%" name="text" required>{{old('text')}}</textarea>
                            </div>
                        </div>


                        <h3>Источник новости</h3>
                        <div class="row">
                            <div class="col-12">
                                <label for="source_name">Название источника</label>
                            </div>
                            <div class="col-12">
                                <input id="source_name" type="text" name="source_name" style="width:100%" value="{{old('source_name')}}">
                            </div>
                        </div>
                        <div class="row h-mt-20">
                            <div class="col-12">
                                <label for="source_url">Ссылка на источник (http://www.site.ru/istochnik/novosti/)</label>
                            </div>
                            <div class="col-12">
                                <input id="source_url" type="text" name="source_url" style="width:100%" value="{{old('source_url')}}">
                            </div>
                        </div>
                        <div class="row h-mt-20">
                            <div class="col-12">
                                <label for="author_text_val">Автор новости</label>
                            </div>
                            <div class="col-12">
                                <input id="author_text_val" type="text" name="author_text_val" style="width:100%" value="{{old('author_text_val')}}">
                            </div>
                        </div>
                        @if (auth()->check())
                            <div class="row h-mt-20">
                                <div class="col-12">
                                    <label class="li-form-label companies-form__name">
                                        <input type="checkbox" name="author_show" class="li-form-input">
                                        <span>Не указывать на сайте, что новость была размещена именно мной</span>
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="row h-mt-20">
                            <div class="col-12">
                                <label class="li-form-label companies-form__name">
                                    <input type="checkbox" name="agree" class="li-form-input" required>
                                    <span id="error-agree">Я согласен с <a href="/main/content/page/news-add-rules">правилами размещения новостей</a><span class="li-form-required">*</span></span>
                                </label>

                            </div>
                        </div>
                        {!! app('captcha')->display() !!}
                        <label id="recaptcha-error" style="display: none; color: red" for="recaptcha-error">Необходимо пройти капчу.</label>
                        <button type="submit" class="button button-dark-blue h-mt-20">Добавить новость</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
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
            $('#newsAddForm').validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    if (element.is(":checkbox")) {
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));  // custom placement example
                    } else if (element.attr('name') === 'news_category_id') {
                        error.insertAfter('#news_category_id')
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    source_url: {
                        required: false,
                        url: true
                    },
                    news_category_id: {
                        required: true
                    }
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