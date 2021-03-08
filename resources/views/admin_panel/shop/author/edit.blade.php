@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
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
                <h3 class="box-title">Редактирование автора</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.shop.researches.authors.update', ['id' => $author->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-6">
                            <input id="title" name="title" type="text" class="form-control" value="{{ $author->title }}" required="">
                        </div>
                    </div>

                    @include('admin_panel.components.picture_upload.picture_upload_modal')

                    <div class="form-group row">
                        <label for="image" class="col-sm-3 col-form-label text-right">Логотип</label>
                        <div class="col-sm-6">
                            <img id="user_avatar" class="img-fluid" src="{{ $author->image ? url($author->image) : url('/img/no_picture.jpg') }}" alt="Image">
                            <input type="button" class="btn btn-default" value="Сменить" onclick="select_avatar()">
                            <input type="hidden" name="image" id="picture" value="{{ $author->image ?? '' }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="brief_text" class="col-sm-3 col-form-label text-right">Краткое описание</label>
                        <div class="col-sm-6">
                            <textarea id="brief_text" name="brief_text" class="form-control" rows="4" cols="50" required="">{{ $author->brief_text ?? ''}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label text-right">Подробное описание</label>
                        <div class="col-sm-6">
                            <textarea id="text" name="text" class="form-control summernote" rows="4" cols="50">{{ $author->text ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sort" class="col-sm-3 col-form-label text-right">Сортировка</label>
                        <div class="col-sm-6">
                            <input id="sort" name="sort" type="number" class="form-control" value="{{$author->sort}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="meta_title" class="col-sm-3 col-form-label text-right">Meta Title</label>
                        <div class="col-sm-6">
                            <input id="meta_title" name="meta_title" type="text" class="form-control" value="{{ $author->meta_title }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_keywords" class="col-sm-3 col-form-label text-right">Meta Keywords</label>
                        <div class="col-sm-6">
                            <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{ $author->meta_keywords }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_description" class="col-sm-3 col-form-label text-right">Meta Description</label>
                        <div class="col-sm-6">
                            <input id="meta_description" name="meta_description" type="text" class="form-control" value="{{ $author->meta_description }}">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-image-attributes.js')}}"></script>
    @include('admin_panel.components.picture_upload.picture_upload_js', ['type' => 'admin.author'])
    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
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
            $(".form-horizontal").validate({
                lang: 'ru',
                ignore: 'summernote *',
                errorPlacement: function(error, element) {
                    if (element.hasClass("summernote")){
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));
                    } else if (element.attr('name') === 'users_id[]') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else if (element.attr('name') === 'blog_id') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
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
                        $('.summernote').summernote("insertNode", image[0]);
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    </script>
@endsection