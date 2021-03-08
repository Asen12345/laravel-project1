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
                <h3 class="box-title">Редактирование новости</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.news.update', ['id' => $news->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-7">
                            <input id="name" name="name" type="text" class="form-control" value="{{old('name') ?? $news['name']}}" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="published" class="form-check-label col-sm-3 text-right">Опубликовано</label>
                        <div class="col-sm-7">
                            <input id="published" name="published" type="checkbox" {{$news['published'] == true ? 'checked' : ''}}>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label text-right">Title</label>
                        <div class="col-sm-7">
                            <input id="title" name="title" type="text" class="form-control" value="{{old('title') ?? $news['title']}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="url_ru" class="col-sm-3 col-form-label text-right">Url ru</label>
                        <div class="col-sm-7">
                            <input id="url_ru" name="url_ru" type="text" class="form-control" value="{{old('url_ru') ?? $news['url_ru']}}" required="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="url_en" class="col-sm-3 col-form-label text-right">Url en</label>
                        <div class="col-sm-7">
                            <input disabled="" type="text" class="form-control url_en" value="{{old('url_en') ?? $news['url_en']}}">
                            <input id="url_en" name="url_en" type="hidden" class="form-control url_en" value="{{old('url_en') ?? $news['url_en']}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="announce" class="col-sm-3 col-form-label text-right">Анонс</label>
                        <div class="col-sm-7">
                            <textarea id="announce" name="announce" class="form-control" rows="4" cols="50">{!! old('announce') ?? $news['announce'] !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label text-right">Полный текст новости</label>
                        <div class="col-sm-7">
                            <textarea id="text" name="text" class="form-control summernote" rows="4" cols="50" required="">{!! old('text') ?? preg_replace("/\r\n|\r|\n/",'<br/>',$news['text']) !!}</textarea>
                            <span class="error-custom" id="error-text"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="news_category_id" class="col-sm-3 col-form-label text-right">Категория</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" class="filter form-control" id="news_category_id" name="news_category_id" required>
                                <option></option>
                                @foreach($categories as $category)
                                    @if($category->parent_id == 0)
                                        <option value="{{$category->id}}" {{$news['news_category_id'] ==  $category->id  ? 'selected' : ''}}>{{$category->name}}</option>

                                        @foreach($categories as $subcategory)
                                            @if($subcategory->parent_id == $category->id)
                                                <option value="{{$subcategory->id}}" {{$news['news_category_id'] ==  $subcategory->id  ? 'selected' : ''}}>{{$subcategory->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="news_scene_id" class="col-sm-3 col-form-label text-right">Сюжеты</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" class="filter form-control js-example-basic-multiple" id="news_scene_id" name="news_scene_id[]" multiple="multiple">
                                {{ $news_scene = $news->scene}}
                                @foreach($scenes as $scene)
                                    <option value="{{$scene->id}}" {{$news_scene->contains('id', $scene->id) ? 'selected' : ''}}>{{$scene->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="source_name" class="col-sm-3 col-form-label text-right">Название источника новости</label>
                        <div class="col-sm-7">
                            <input id="source_name" name="source_name" type="text" class="form-control" value="{{old('source_name') ?? $news['source_name']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="source_url" class="col-sm-3 col-form-label text-right">Ссылка на источник новости</label>
                        <div class="col-sm-7">
                            <input id="source_url" name="source_url" type="text" class="form-control" value="{{old('source_url') ?? $news['source_url']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="author_user_name" class="col-sm-3 col-form-label text-right">Разместивший новость</label>
                        <div class="col-sm-7">
                            <input id="author_user_name" name="author_user_name" type="text" class="form-control" value="{{$news->user->name ?? ''}}">
                            <input id="author_user_id" name="author_user_id" type="hidden" class="form-control" value="{{$news->user->id ?? ''}}">
                        </div>
                    </div>

                    @if (!empty('author_text_val'))
                        <div class="form-group row">
                            <label for="author_text_val" class="col-sm-3 col-form-label text-right">Автор новости</label>
                            <div class="col-sm-7">
                                <input id="author_text_val" name="author_text_val" type="text" class="form-control" value="{{$news->author_text_val ?? ''}}">
                            </div>
                        </div>
                    @endif


                    <div class="form-group row">
                        <label for="created_at" class="col-sm-3 col-form-label text-right">Дата добавления</label>
                        <div class="col-sm-2">
                            <input id="created_at" disabled name="created_at" type="text" class="form-control datep" value="{{ \Carbon\Carbon::parse($news->created_at)->format('d-m-Y') ?? ''}}">
                        </div>
                    </div>
					
					<div class="form-group row">
                        <label for="published_at" class="col-sm-3 col-form-label text-right">Дата редактирования</label>
                        <div class="col-sm-2">
                            <input id="published_at" disabled name="published_at" type="text" class="form-control datep" value="{{ \Carbon\Carbon::parse($news->published_at)->format('d-m-Y') ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="vip" class="form-check-label col-sm-3 text-right">Важная новость</label>
                        <div class="col-sm-7">
                            <input id="vip" name="vip" type="checkbox" {{$news['vip'] == true ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="author_show" class="form-check-label col-sm-3 text-right">Показывать кто разместил</label>
                        <div class="col-sm-7">
                            <input id="author_show" name="author_show" type="checkbox" {{$news['author_show'] == true ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="yandex" class="form-check-label col-sm-3 text-right">В яндекс</label>
                        <div class="col-sm-7">
                            <input id="yandex" name="yandex" type="checkbox" {{$news['yandex'] == true ? 'checked' : ''}}>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-image-attributes.js')}}"></script>
    <script src="{{asset('/assets/jquery-ui/datepicker-ru.js')}}"></script>
    {{-- <script src="{{asset('/assets/jquery-modal-master/jquery.modal.min.js')}}"></script>--}}
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
            $('form').validate({
                lang: 'ru',
                ignore: 'summernote *',
                rules: {
                    title: {
                        required: true,
                        maxlength: 170
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("summernote")){
                        error.insertAfter(document.getElementById('error-text'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            $('select').on('change', function(){
                $("form").validate().element('select');
            });
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
            $('.js-example-basic-multiple').select2({
                theme: "flat",
                maximumSelectionLength : 4
            });
            //Выбор даты
            $('.datep').datepicker($.extend({
                    //showMonthAfterYear: false,
                    dateFormat:'d MM, y'
                },
                $.datepicker.regional['ru']
            ));
            /*Company*/
            $('#author_user_name').autocomplete({
                minLength: 0,
                source: function( request, response ) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.news.autocomplete.author') }}",
                        data: {
                            _token:"{{ csrf_token() }}",
                            value: $('input#author_user_name').val(),
                        },
                        success: function(data) {
                            response( $.map( data, function( item ) {
                                return {
                                    label: item.name + '\u00A0\u00A0\u00A0 "' + item.email + '"',
                                    value: item.id,
                                    name: item.name
                                }
                            }));
                        },
                    })
                },
                focus: function( event, ui ) {
                    /*$('input[name="author_user_name"]').val( ui.item.name );
                    $('input[name="author_user_id"]').val( ui.item.value );*/
                    return false;
                },
                select: function( event, ui ) {
                    $('input[name="author_user_name"]').val( ui.item.name );
                    $('input[name="author_user_id"]').val( ui.item.value );
                    return false;
                }
            })
                .focus(function () {
                    $(this).autocomplete("search", $(this).val());
                })
                /*.keyup(function () {
                    $(this).autocomplete("search", $(this).val());
                });*/
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