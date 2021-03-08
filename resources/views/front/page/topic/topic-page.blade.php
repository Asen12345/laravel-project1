@extends('front.layouts.app')

@section('header_style')
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
            <div class="container-fluid">
                <div class="row m-minus">
                    <div class="col-12 blog-page-container">
                        <div class="title-block title-block--socials">
                            <h2>Тема дня</h2>
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
                                <div data-background="none;" data-options="small,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,facebook,odnoklassniki,twitter,linkedin" class="pluso"></div>
                            </div>
                        </div>
                        <div class="event-row__header">
                            <div class="topic-title"><h1>{{$topic->title}}</h1></div>
                            <div class="announcements-item__date">{{\Carbon\Carbon::parse($topic->published_at)->isoFormat("DD MMMM YYYY")}}</div>
                        </div>
                        <div class="topic-text">
                            {!! $topic->text !!}
                        </div>
                        <div class="topic-cards">
                            @forelse($topic['answers'] as $answer)
                            <div class="topic-card li-card-block">
                                <div class="li-portrait"><img src="{{$answer->socialProfileUser->image ?? '/img/no_picture.jpg'}}" alt="alt"></div>
                                <div class="topic-card__header"><a href="{{route('front.page.people.user', ['user_id' => $answer->user->id])}}" class="li-link-blue li-person-name li-person-name--blue">{{$answer->user->name}}</a>
                                    <div class="li-person-info">
                                        <div class="li-person-info__role">{{$answer->socialProfileUser->company_post}}</div>
                                        @if (!empty($answer->user->company->name))
                                            @if (isset($answer->user->company->userCompany))
                                                <a href="{{ route('front.page.people.user', ['id' => $answer->user->company->userCompany->id]) }}" class="li-person-info__org">{{$answer->user->company->name ?? ''}}</a>

                                            @else
                                                <span class="li-person-info__org">{{$answer->user->company->name ?? ''}}</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="topic-card__content">
                                    <p class="topic-card__text">
                                        {!!$answer->text!!}
                                    </p>
                                    <div class="topic-card_bottom">
                                        @if (auth()->check())
                                            @if ($answer->user_id == auth()->user()->id)
                                                <a href="{{ route('front.page.topic.answer.edit', ['url_en' => $topic->url_en]) }}" class="button button-micro button-dark-blue float-left">Редактировать</a>
                                            @endif
                                        @endif
                                        <div class="blog-share">
                                            <span class="blog-share__title">поделитесь с друзьями</span>
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
                                            <div data-background="none;" data-options="small,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,facebook,odnoklassniki,twitter,linkedin" class="pluso" data-description="{{ $answer->text }}" data-url="{{ request()->url() . '#' . $answer->id }}" data-title="Ответ пользователя {{ $answer->user->name ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    {{$topic->answers->links('vendor.pagination.custom-front')}}
                </div>
                @if (auth()->check() && Gate::allows('is-subscriber-topic', $topic) && (!$answerer || $answerer === 'edit'))
                    <div class="topic-answer">
                        <div class="li-popup topic-answer__form">
                            <header class="li-popup__header">{{ $answerer === 'edit' ? 'обновить ответ' : 'написать ответ' }}
                                <div class="chev-top"></div>
                            </header>
                            <div class="li-popup__body">
                                <form id="form_topic" method="post" action="{{$answerer === 'edit' ? route('front.page.topic.update') : route('front.page.topic.send')}}" class="li-form">
                                    @csrf
                                    <input type="hidden" name="topic_id" value="{{$topic->id}}">
                                    <label class="li-form-label"><span class="li-form-label-name">Сообщение:</span>
                                        <textarea id="text" name="text" cols="30" rows="10" class="li-form-area summernote" required>{{ $answerer === 'edit' ? $userAnswer->text : '' }}</textarea>
                                        <span id="error-text"></span>
                                    </label>
                                    <div class="li-form__buttons text-center">
                                        <button type="submit" class="button button-mini button-l-blue">{{ $answerer === 'edit' ? 'обновить' : 'отправить' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
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
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
	    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
             $('#text').summernote({
                lang:'ru-RU',
				tabsize: 2,
                height: 250,
                toolbar: [
					['edit',['undo','redo']],
                    ['style', ['style']],
					['fontname', ['fontname', 'fontsize']],
                    ['font', ['bold', 'underline', 'italic', 'clear', 'strikethrough', 'superscript', 'subscript']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['hr', 'link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']],
                ],
                callbacks: {
                    onPaste: function (e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    },
                    onImageUpload: function(image) {
                        uploadImage(image[0]);
                    },
                }
            }).on('summernote.keyup', function() {
                let text = $(this).summernote("code").replace(/&nbsp;|<\/?[^>]+(>|$)/g, "").trim();
                if (text.length === 0) {
                    $(this).summernote('code', '');
                } else {
                    $("#text-error").remove();
                }
            });
            let blockQuoteButton = function (context) {
                let ui = $('#text').ui;
                let button = ui.button({
                    className: 'note-btn-blockquote',
                    contents: '<i class="fa fa-quote-right"></i>',
                    tooltip: 'Blockquote',
                    click: function () {
                        context.invoke('editor.formatBlock', 'Blockquote')
                    }
                });
                return button.render();
            };
            function uploadImage(image) {
                let data = new FormData();
                data.append("image", image);
                data.append("_token", '{{ csrf_token() }}');
                $.ajax({
                    url: '{!! route('front.resource.upload.custom-image') !!}',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    type: "post",
                    success: function(url) {
                        if (!url.location){
                            console.log('Error with upload image')
                        } else {
                            let image = $('<img>').attr('src', url.location);
                            $('#text').summernote("insertNode", image[0]);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
            $('#form_topic').validate({
                lang: 'ru',
                ignore: 'summernote *',
                errorPlacement: function(error, element) {
                    if (element.hasClass("summernote")){
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });


            @if($answerer === 'edit')
            $("html, body").animate({ scrollTop: $('.topic-answer').offset().top - 200 }, 1000);
            @endif
        })
    </script>


@endsection