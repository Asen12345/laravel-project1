@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-custom.css')}}" rel="stylesheet">
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
                <form class="form-horizontal" method="POST"
                      action="{{route('admin.widgets.update', ['id' => $widget->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-7">
                            <input id="name" name="name" type="text" class="form-control" value="{{$widget->name}}"
                                   required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="published" class="form-check-label col-sm-3 text-right">Во всех блогах</label>
                        <div class="col-sm-7">
                            <input id="all_blogs" name="all_blogs"
                                   type="checkbox" {{$widget->all_blogs == true ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="published" class="form-check-label col-sm-3 text-right">Опубликовано</label>
                        <div class="col-sm-7">
                            <input id="published" name="published"
                                   type="checkbox" {{$widget->published == true ? 'checked' : ''}}>
                        </div>
                    </div>


                    <div class="form-group row" id="input_row">
                        <label for="input_group" class="col-sm-3 col-form-label text-right">Место размещения</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" class="filter form-control js-example-basic-multiple" id="blogs_id"
                                    name="blogs_id[]" multiple="multiple">
                                @if (!empty($blogs))
                                    @foreach($blogs as $blog)
                                        <option value="{{ $blog->id }}" selected>{{ $blog->subject }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label text-right">Код виджета</label>
                        <div class="col-sm-7">
                                <textarea id="text" name="text" class="form-control summernote" rows="4" cols="50"
                                          required="">{{ $widget->text }}</textarea>
                            <span id="error-text"></span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            let checkbox = $('#all_blogs');
            checkAllBlogs(checkbox);
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
			}).on('summernote.keyup', function() {
				let text = $(this).summernote("code").replace(/&nbsp;|<\/?[^>]+(>|$)/g, "").trim();
				if (text.length === 0) {
					$(this).summernote('code', '');
				} else {
					$("#text-error").remove();
				}
            });
            $(".form-horizontal").validate({
                lang: 'ru',
                ignore: 'summernote *',
                errorPlacement: function (error, element) {
                    if (element.hasClass("summernote")) {
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));
                    } else if (element.attr('name') === 'users_id[]') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            checkbox.on('change', function() {
                let checkbox = $(this);
                checkAllBlogs(checkbox);
            });
            function checkAllBlogs(checkbox) {
                if (checkbox.is(":checked") === true) {
                    $('.js-example-basic-multiple').val(null).trigger('change');
                    $('#blogs_id').empty()
                        .prop("disabled", true);
                } else {
                    $('#blogs_id').prop("disabled", false);
                }
            }

            $('.js-example-basic-multiple#blogs_id').select2({
                //tags: true,
                theme: "flat",
                //maximumSelectionLength : 4,
                ajax: {
                    type: "POST",
                    url: "{{route('admin.widgets.ajax.upload')}}",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token:"{{ csrf_token() }}",
                            value: params.term,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.subject,
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