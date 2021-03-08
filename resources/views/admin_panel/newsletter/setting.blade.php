@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/timepicker/css/timepicki.css')}}" rel="stylesheet">
@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{$content['title']}}</h3>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.newsletter.update')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="weekdays" class="col-sm-3 col-form-label text-right">Дни недели</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" class="filter form-control js-example-basic-multiple" id="weekdays" name="weekdays[]" multiple="multiple" required>
                                @foreach($content['weekday'] as $weekday)
                                    <option value="{{ $loop->iteration }}" @if (!empty($settings))
                                        {{ in_array($loop->iteration, $settings->weekdays) ? 'selected' : ''}}@endif >{{ $weekday }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="timepicker" class="col-sm-3 col-form-label text-right">Время рассылки</label>
                        <div class="col-sm-2">
                            <input id="timepicker" name="send_time" type="text" class="form-control timepicker" value="{{ $settings->send_time ?? '' }}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="url_ru" class="col-sm-3 col-form-label text-right">Адрес для уведомлений</label>
                        <div class="col-sm-7">
                            <input id="email" name="email" type="text" class="form-control" value="{{ $settings->email ?? '' }}" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="footer_text" class="col-sm-3 col-form-label text-right">Футер рассылки</label>
                        <div class="col-sm-7">
                            <textarea id="footer_text" name="footer_text" class="form-control summernote" rows="4" cols="50" required>{{ $settings->footer_text ?? ''}}</textarea>
                            <span id="error-footer_text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unsubscribe_text" class="col-sm-3 col-form-label text-right">Текст об отписке от рассылки</label>
                        <div class="col-sm-7">
                            <textarea id="unsubscribe_text" name="unsubscribe_text" class="form-control summernote" rows="4" cols="50" required>{{ $settings->unsubscribe_text ?? ''}}</textarea>
                            <span id="error-unsubscribe_text"></span>
                        </div>

                    </div>

                    <div class="box-footer text-center">
                        <a href="{{ route('admin.newsletter.template.show') }}" class="btn button btn-primary text-white float-left">Предпросмотр шаблона</a>
                        <a href="{{ route('admin.newsletter.show') }}" class="ml-3 btn button btn-primary text-white float-left">Предпросмотр рассылки</a>
                        <button type="submit" class="btn btn-primary center-block float-right">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-image-title.js')}}"></script>
    <script src="{{asset('/assets/timepicker/js/timepicki.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                imageTitle: {
                    specificAltField: true,
                },
                lang: 'ru-RU',
                popover: {
                    image: [
                        ['imagesize', ['resizeFull', 'resizeHalf', 'resizeQuarter']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']],
                        ['custom', ['imageTitle']],
                    ],
                },
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
                    $('#' + $(this).attr('id') + '-error').remove();
                }
            });

            $(".form-horizontal").validate({
                lang: 'ru',
                ignore: 'summernote *',
                errorPlacement: function(error, element) {
                    if (element.hasClass("summernote")){
                        error.insertAfter($('#error-' + element.attr('id')));
                    } else if (element.attr('name') === 'weekdays[]') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else {
                        error.insertAfter(element);
                    }
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
                            $('.summernote').summernote("insertNode", image[0]);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
            $('.js-example-basic-multiple').select2({
                tags: false,
                theme: "flat",
            });
            //Выбор даты
            $('#timepicker').timepicki({
                show_meridian:false,
                min_hour_value:0,
                max_hour_value:23,
                step_size_minutes:15,
                overflow_minutes:true,
                increase_direction:'up',
                disable_keyboard_mobile: true
            });
            $('#email').on('input change keyup', function(e) {
                emailCheck($(this));
            });
            function emailCheck(val){
                let email = val;
                if(email.val() === ""){
                    email.addClass('is-invalid');
                    return false;
                }else{
                    let regMail =  /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/;
                    if(regMail.test(email.val()) === false){
                        email.addClass('is-invalid');
                        $('button[type=submit]').prop('disabled', true);
                        return false;
                    }else{
                        $('button[type=submit]').prop('disabled', false);
                        email.removeClass('is-invalid');
                    }

                }
            }
        })
    </script>
@endsection