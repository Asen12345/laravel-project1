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
        <form class="form-horizontal" method="POST" action="{{route('admin.resources.data.template.update')}}"
              enctype="multipart/form-data">
            @csrf
            <div class="box  box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Страница восстановления пароля</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box-body">
                        <div class="form-group row">
                            <label for="password_recovery_before" class="col-sm-3 col-form-label text-right">Текст до формы</label>
                            <div class="col-sm-7">
                                <textarea id="password_recovery_before" name="password_recovery_before" class="form-control summernote" rows="4"
                                          cols="50">{{$data_template['password_recovery']['before']}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_recovery_after" class="col-sm-3 col-form-label text-right">Текст после формы</label>
                            <div class="col-sm-7">
                                <textarea id="password_recovery_after" name="password_recovery_after" class="form-control summernote" rows="4"
                                          cols="50">{{$data_template['password_recovery']['after']}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box  box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Страница регистрации</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="box-body">
                        <div class="form-group row">
                            <label for="register_page_before" class="col-sm-3 col-form-label text-right">Текст до формы</label>
                            <div class="col-sm-7">
                                <textarea id="register_page_before" name="register_page_before" class="form-control summernote" rows="4"
                                          cols="50">{{$data_template['register_page']['before']}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="register_page_after" class="col-sm-3 col-form-label text-right">Текст после формы</label>
                            <div class="col-sm-7">
                                <textarea id="register_page_after" name="register_page_after" class="form-control summernote" rows="4"
                                          cols="50">{{$data_template['register_page']['after']}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center h-mt-20">
                <button type="submit" class="btn btn-primary btn-flat">Сохранить</button>
            </div>
        </form>
    </div>
@endsection

@section('footer_js')
    <script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-image-title.js')}}"></script>
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
        })
    </script>

@endsection