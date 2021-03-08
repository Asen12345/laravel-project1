@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
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
            </div>
            <div class="box-body">
                <div class="box-body">
                    <form class="form-horizontal" method="POST" action="{{route('admin.users.messages.store')}}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label text-right">Тема</label>
                            <div class="col-sm-7">
                                <input id="subject" name="subject" type="text" class="form-control" value="{{old('subject')}}" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="text" class="col-sm-3 col-form-label text-right">Текст сообщения</label>
                            <div class="col-sm-7">
                                <textarea id="text" name="text" class="form-control summernote" rows="4" cols="50"
                                          required="">{{old('text')}}</textarea>
                                <span id="error-text"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_all" class="form-check-label col-sm-3 text-right">Отправить всем</label>
                            <div class="col-sm-2">
                                <input id="add_all" name="add_all" type="checkbox" {{old('add_all') == 'on' ? 'checked' : ''}}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_all_experts" class="form-check-label col-sm-3 text-right">Отправить всем экспертам</label>
                            <div class="col-sm-2">
                                <input id="add_all_experts" name="add_all_experts" type="checkbox" {{old('add_all_experts') == 'on' ? 'checked' : ''}}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_all_company" class="form-check-label col-sm-3 text-right">Отправить всем компаниям</label>
                            <div class="col-sm-2">
                                <input id="add_all_company" name="add_all_company" type="checkbox" {{old('add_all_company') == 'on' ? 'checked' : ''}}>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 col-form-label text-right">Выбор по сферам деятельности</div>
                            <div class="col-sm-6">
                                <select class="filter form-control js-example-basic-single" id="company_type" name="company_type" autocomplete="off">
                                    <option value=""></option>
                                    @foreach($company_types as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <a href="javascript:;" id="add_by_company_type" class="btn btn-primary pull-right">Добавить</a>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 col-form-label text-right">Пользователи для отправки</div>
                            <div class="col-sm-7">
                                <select class="filter form-control js-example-basic-multiple" id="users" name="users_id[]" multiple="multiple" required autocomplete="off">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 col-form-label text-right">Добавить файл</div>
                            <div class="col-sm-7">
                                <input type="file" class="form-control-file" name="file" id="file">
                            </div>
                        </div>

                        <div class="text-center h-mt-20">
                            <button type="submit" class="btn btn-primary btn-flat">Отправить</button>
                        </div>
                    </form>
                </div>
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
    <script src="{{asset('/assets/jquery-ui/datepicker-ru.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#text').summernote({
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
                errorPlacement: function(error, element) {
                    if (element.hasClass("summernote")){
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));
                    } else if (element.attr('name') === 'users_id[]') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $('#add_all').on('change', function() {
                let checkbox = $(this);
                if (checkbox.is(":checked") === true) {
                    $('.js-example-basic-multiple').val(null).trigger('change');
                    $('#users').empty();
                    $('#users, #company_type').prop("disabled", true);
                } else {
                    $('#users, #company_type').prop("disabled", false);
                }
            });
            $('#add_all_experts').on('change', function() {
                let checkbox = $(this);
                if (checkbox.is(":checked") === true) {
                    findUsers(null, 'expert')
                } else {
                    $('#users').find("option[class='expert']").remove();
                }
            });
            $('#add_all_company').on('change', function() {
                let checkbox = $(this);
                if (checkbox.is(":checked") === true) {
                    findUsers(null, 'company')
                } else {
                    $('#users').find("option[class='company']").remove();
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
            $('.js-example-basic-multiple#users').select2({
                //tags: true,
                theme: "flat",
                overflow: scroll,
                //maximumSelectionLength : 4,
                ajax: {
                    type: "POST",
                    url: "{{route('admin.users.message.autocomplete.users')}}",
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
            }).trigger('change').on('change', function (e) {
                if ($(this).val() !== null) {
                    $('#users-error').remove();
                }
            });
            $('.js-example-basic-single#company_type').select2({
                //tags: true,
                minimumResultsForSearch: 2,
                theme: "custom",
            });
            $('#add_by_company_type').on('click', function () {
                let company = $('#company_type').val();
                findUsers(company);
            });
            function findUsers(id = null, type = null) {
                $.ajax({
                    type: "POST",
                    url: '{{route('admin.users.message.autocomplete.company')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                        type: type
                    },
                    success: function(data) {
                        $.each(data , function (index, elem) {
                            appended(elem.id, elem.name, type)
                        });
                    },
                });
                function appended (id, name, type) {
                    if ($('#users').find("option[value='" + id + "']").length) {
                        //$('#users').val(id).trigger('change');
                    } else {
                        let opt = $('<option>').val(id).text(name).addClass(type).attr('selected', true);
                        $('#users').append(opt).trigger('change');
                    }

                }
            }
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