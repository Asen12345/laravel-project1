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
                <form class="form-horizontal" method="POST" action="{{route('admin.'. $content['page'] .'.update', ['anons_id' => $anons->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="date" class="col-sm-3 col-form-label text-right">Дата мероприятия</label>
                        <div class="col-sm-2">
                            <input id="date" name="date" type="text" class="form-control datep" value="{{\Carbon\Carbon::parse($anons->date)->format('d-m-Y')}}" style="position: relative;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="will_end" class="col-sm-3 col-form-label text-right">Дата удаления</label>
                        <div class="col-sm-2">
                            <input id="will_end" name="will_end" type="text" class="form-control datep" value="{{\Carbon\Carbon::parse($anons->will_end)->format('d-m-Y')}}" style="position: relative;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-7">
                            <input id="title" name="title" type="text" class="form-control" value="{{$anons->title}}" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="place" class="col-sm-3 col-form-label text-right">Место</label>
                        <div class="col-sm-7">
                            <input id="place" name="place" type="text" class="form-control" value="{{$anons->place}}" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="organizer" class="col-sm-3 col-form-label text-right">Организатор</label>
                        <div class="col-sm-7">
                            <input id="organizer" name="organizer" type="text" class="form-control" value="{{$anons->organizer}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label text-right">Описание мероприятия</label>
                        <div class="col-sm-7">
                            <textarea id="text" name="text" class="form-control summernote" rows="4" cols="50">{{$anons->text}}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="reg_linc" class="col-sm-3 col-form-label text-right">Ссылка на регистрацию</label>
                        <div class="col-sm-7">
                            <input id="reg_linc" name="reg_linc" type="text" class="form-control" value="{{$anons->reg_linc}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="price" class="col-sm-3 col-form-label text-right">Стоимость</label>
                        <div class="col-sm-7">
                            <input id="price" name="price" type="text" class="form-control" value="{{$anons->price}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="main" class="form-check-label col-sm-3 text-right">На главной </label>
                        <div class="col-sm-7">
                            <input id="main" name="main" type="checkbox" {{$anons->main == true ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="meta_title" class="col-sm-3 col-form-label text-right">Meta Title</label>
                        <div class="col-sm-7">
                            <input id="meta_title" name="meta_title" type="text" class="form-control" value="{{$anons->meta_title}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="meta_keywords" class="col-sm-3 col-form-label text-right">Meta Keywords</label>
                        <div class="col-sm-7">
                            <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{$anons->meta_keywords}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="meta_description" class="col-sm-3 col-form-label text-right">Meta Description</label>
                        <div class="col-sm-7">
                            <input id="meta_description" name="meta_description" type="text" class="form-control" value="{{$anons->meta_description}}">
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
            //Выбор даты
            $('.datep').datepicker($.extend({
                    //showMonthAfterYear: false,
                    dateFormat:'d MM, y'
                },
                $.datepicker.regional['ru']
            ));
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