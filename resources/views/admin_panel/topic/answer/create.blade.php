@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
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
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.'. $content['page'] .'.store')}}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="topic" class="col-sm-3 col-form-label text-right">Тема</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" class="filter form-control js-example-basic-single" id="topic" name="topic_id">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user" class="col-sm-3 col-form-label text-right">Автор ответа</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" class="filter form-control js-example-basic-single" id="user" name="user_id">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="published" class="form-check-label col-sm-3 text-right">Опубликовано</label>
                        <div class="col-sm-7">
                            <input id="published" name="published" type="checkbox">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label text-right">Полный текст ответа</label>
                        <div class="col-sm-7">
                            <textarea id="text" name="text" class="form-control summernote" rows="4" cols="50"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="published_at" class="col-sm-3 col-form-label text-right">Дата публикации</label>
                        <div class="col-sm-2">
                            <input id="published_at" name="published_at" type="text" class="form-control datep" value="{{\Carbon\Carbon::now()->format('d-m-Y')}}">
                        </div>
                    </div>

                    <div class="box-footer text-center">
                        <button type="submit" class="btn btn-primary center-block">Сохранить</button>
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
    <script src="{{asset('/assets/summernote/summernote-image-attributes.js')}}"></script>
    <script src="{{asset('/assets/jquery-ui/datepicker-ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#text').summernote({
				imageAttributes:{
					icon:'<i class="note-icon-pencil"/>',
					removeEmpty:true, // true = remove attributes | false = leave empty if present
					disableUpload: true // true = don't display Upload Options | Display Upload Options
				},
                lang: 'ru-RU',
                popover: {
                    image: [
						['custom', ['imageAttributes']],
                        ['imagesize', ['resizeFull', 'resizeHalf', 'resizeQuarter']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']],
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
                        let bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    },
                    onImageUpload: function(image) {
                        uploadImage(image[0]);
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
            $('#url_ru').on('keyup', function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate.slug') }}",
                    data: {
                        _token:"{{ csrf_token() }}",
                        text: $(this).val()
                    },
                    success: function(data) {
                        $('.url_en').val(data)
                    },
                })

            });
            //Выбор даты
            $('.datep').datepicker($.extend({
                    //showMonthAfterYear: false,
                    dateFormat:'d MM, y'
                },
                $.datepicker.regional['ru']
            ));

            /*Topic*/
            $('#topic').select2({
                ajax: {
                    type: "POST",
                    url: "{{route('admin.answer.autocomplete')}}",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token:"{{ csrf_token() }}",
                            name: params.term,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.title,
                                    id: item.id,
                                }
                            })
                        };
                    }
                },
                language: {
                    noResults: function () {
                        return 'Ничего не найдено';
                    },
                    searching: function () {
                        return 'Поиск…';
                    },
                }
            }).trigger('change');
            /*User*/
            $('#user').select2({
                ajax: {
                    type: "POST",
                    url: "{{route('admin.answer.user.autocomplete')}}",
                    dataType: 'json',
                    data: function (params) {
                        let topic_id = $('#topic').val();
                        if (topic_id == null) {
                            alert('Выберите тему для ответа.')
                        }
                        return {
                            _token:"{{ csrf_token() }}",
                            name: params.term,
                            id: topic_id
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    }
                },
                language: {
                    noResults: function () {
                        return 'Ничего не найдено';
                    },
                    searching: function () {
                        return 'Поиск…';
                    },
                }
            }).trigger('change');
        });
		
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
    </script>
@endsection