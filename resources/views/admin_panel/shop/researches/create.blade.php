@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
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
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.shop.researches.store')}}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-6">
                            <input id="title" name="title" type="text" class="form-control" value="{{ old('title') }}" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shop_category_id" class="col-sm-3 col-form-label text-right">Категория</label>
                        <div class="col-sm-6">
                            <select class="filter form-control js-example-basic-multiple" id="shop_category_id" name="shop_category_id[]" multiple="multiple" required autocomplete="off">
                                @forelse($categories as $category)
                                    @if($category->parent_id == 0)
                                        <option value="{{ $category->id }}">{{ $category->name}}</option>

                                        @forelse($categories as $subcategory)
                                            @if($subcategory->parent_id == $category->id)
                                                <option value="{{ $subcategory->id }}">{{ '-- ' . $subcategory->name}}</option>
                                            @endif
                                        @empty

                                        @endforelse
                                    @endif
                                @empty

                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="annotation" class="col-sm-3 col-form-label text-right">Аннотация</label>
                        <div class="col-sm-6">
                            <textarea id="annotation" name="annotation" class="form-control summernote" rows="4" cols="50" required="">{{old('annotation')}}</textarea>
                            <span id="error-annotation"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="content" class="col-sm-3 col-form-label text-right">Содержание</label>
                        <div class="col-sm-6">
                            <textarea id="content" name="content" class="form-control summernote" rows="4" cols="50">{{old('content')}}</textarea>
                            <span id="error-content"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="table" class="col-sm-3 col-form-label text-right">Графики и таблицы</label>
                        <div class="col-sm-6">
                            <textarea id="table" name="table" class="form-control summernote" rows="4" cols="50">{{old('table')}}</textarea>
                            <span id="error-table"></span>
                        </div>
                    </div>


                    @include('admin_panel.components.picture_upload.picture_upload_modal')

                    <div class="form-group row">
                        <label for="image" class="col-sm-3 col-form-label text-right">Обложка</label>
                        <div class="col-sm-6">
                            <img id="user_avatar" class="img-fluid" src="{{url('/img/no_picture.jpg')}}" alt="Image">
                            <input type="button" class="btn btn-default" value="Сменить" onclick="select_avatar()">
                            <input type="hidden" name="image" id="picture" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label text-right">Демо</div>
                        <div class="col-sm-7">
                            <input type="file" class="form-control-file" name="demo_researches" id="demo"  style="display:none" onchange="handleFiles(this.files, 'demo')">
                            <label for="demo" class="btn btn-primary center-block">Выберите файл</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label text-right">Исследование</div>
                        <div class="col-sm-7">
                            <input type="file" class="form-control-file" name="file_researches" id="file" required="" style="display:none" onchange="handleFiles(this.files, 'file')">
                            <label for="file" class="btn btn-primary center-block">Выберите файл</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="published_at" class="col-sm-3 col-form-label text-right">Дата публикации</label>
                        <div class="col-sm-2">
                            <input id="published_at" name="published_at" type="text" class="form-control datep" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="page" class="col-sm-3 col-form-label text-right">Количество страниц</label>
                        <div class="col-sm-6">
                            <input id="page" name="page" type="number" class="form-control" value="{{ old('page') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="format" class="col-sm-3 col-form-label text-right">Формат</label>
                        <div class="col-sm-6">
                            <input id="format" name="format" type="text" class="form-control" value="{{ old('format') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="language" class="col-sm-3 col-form-label text-right">Язык</label>
                        <div class="col-sm-6">
                            <input id="language" name="language" type="text" class="form-control" value="{{ old('language') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="price" class="col-sm-3 col-form-label text-right">Цена, руб.</label>
                        <div class="col-sm-6">
                            <input id="price" name="price" type="number" class="form-control" value="{{ old('price') }}" required="">
                        </div>
                        <div class="col-sm-6 offset-3"><small>Если бесплатное то = 0</small></div>
                    </div>
                    <div class="form-group row">
                        <label for="researches_author_id" class="col-sm-3 col-form-label text-right">Автор</label>
                        <div class="col-sm-6">
                            <select autocomplete="off" id="researches_author_id" name="researches_author_id" type="text" class="form-control">
                                @forelse($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->title }}</option>
                                @empty

                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_title" class="col-sm-3 col-form-label text-right">Meta Title</label>
                        <div class="col-sm-6">
                            <input id="meta_title" name="meta_title" type="text" class="form-control" value="{{old('meta_title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_keywords" class="col-sm-3 col-form-label text-right">Meta Keywords</label>
                        <div class="col-sm-6">
                            <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{old('meta_keywords')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_description" class="col-sm-3 col-form-label text-right">Meta Description</label>
                        <div class="col-sm-6">
                            <input id="meta_description" name="meta_description" type="text" class="form-control" value="{{old('meta_description')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="main" class="col-sm-3 form-check-label col-sm-3 text-right">На главной</label>
                        <div class="col-sm-6 form-check">
                            <input id="main" name="main" type="checkbox" class="form-form-check-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="download" class="col-sm-3 form-check-label col-sm-3 text-right">Количество скачиваний</label>
                        <div class="col-sm-3 form-check">
                            <input id="download" disabled name="download" type="number" class="form-form-check-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="main" class="col-sm-3 form-check-label col-sm-3 text-right">Количество просмотров</label>
                        <div class="col-sm-3 form-check">
                            <input id="main" disabled name="main" type="number" class="form-form-check-input">
                        </div>
                    </div>


                    <div class="box-footer text-center">
                        <button type="submit" name="save_and_stay" class="btn btn-primary center-block">Сохранить</button>
                        <button type="submit" class="btn btn-primary center-block">Сохранить и выйти</button>
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
    @include('admin_panel.components.picture_upload.picture_upload_js', ['type' => 'admin.researches'])
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2({
                //tags: true,
                theme: "flat",
                overflow: scroll,
            }).trigger('change').on('change', function (e) {
                if ($(this).val() !== null) {
                    $('#shop_category_id-error').remove();
                }
            });
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
					$("#" + $(this).attr('name') + "-error").remove();
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
                    } else if (element.attr('name') === 'shop_category_id[]') {
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
            //Выбор даты
            $('.datep').datepicker($.extend({
                    dateFormat:'d MM, y',
                    zIndexOffset: 10000

                },
                $.datepicker.regional['ru']
            ));
        });
        function handleFiles(file, type) {
            $('label[for=' + type + ']').text(file[0].name)
        }
    </script>
@endsection