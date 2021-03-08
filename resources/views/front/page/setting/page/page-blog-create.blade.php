@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="blog-page-container">
        <div class="title-block">
            <h1 class="block-title__text">Новый блог</h1>
        </div>
        <form class="news-add-form" action="{{route('front.setting.account.blog.store')}}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="row h-mt-20">
                <div class="col-12">
                    <label for="subject">Название блога</label>
                </div>
                <div class="col-12">
                    <input id="subject" type="text" name="subject" value="{{old('subject')}}" required>
                </div>
            </div>
            <div class="row h-mt-20">
                <div class="col-12">
                    <label for="title">Заголовок</label>
                </div>
                <div class="col-12">
                    <input id="title" type="text" name="title" value="{{old('title')}}" required>
                </div>
            </div>

            <div class="row h-mt-20">
                <div class="col-12">
                    <label for="announce">Анонс</label>
                </div>
                <div class="col-12">
                    <textarea id="announce" name="announce" class="form-control" rows="4" cols="50" required="">{{old('announce')}}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="tags">Теги</label>
                </div>
                <div class="col-12">
                    <select class="filter form-control js-example-basic-multiple" id="tags" name="tags[]" multiple="multiple">
                        @foreach($tags as $tag)
                            <option value="{{$tag->id}}">{{$tag->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row h-mt-20">
                <div class="col-12">
                    <label for="text">Текст записи</label>
                </div>
                <div class="col-12">
                    <textarea id="text" name="text" class="form-control summernote" rows="4" cols="50" required>{{old('text')}}</textarea>
                </div>
            </div>
            <button type="submit" class="button button-dark-blue h-mt-20">Добавить запись</button>
        </form>
    </div>

@endsection

@section('js_footer_account')
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
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
                        let bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    },
					onImageUpload: function(image) {
                        uploadImage(image[0]);
                    },
                    onKeyup: function (e) {
                        if ($('#text').summernote('isEmpty')) {

                        } else {
                            $('#text-error').remove();
                            //$('.note-editor.note-frame.card').attr('style', '');
                        }
                    }
                },
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
            $(".news-add-form").validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    if (element.attr('name') === 'text') {
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));  // custom placement example
                    } else {
                        if (element.attr('name') === 'tags[]') {
                            error.insertAfter(element.parent().children('.select2'))
                        } else {
                            error.insertAfter(element);
                        }
                    }
                }
            });
            $('.news-add-form').on('submit', function(e) {
                if($('#text').summernote('isEmpty')) {
                    //$('.note-editor.note-frame.card').attr('style', 'border: 1px solid #ff5376');
                    let errorElem = '<label id="text-error" class="error" for="text">Это поле необходимо заполнить.</label>';
                    $(errorElem).insertAfter('.note-editor.note-frame.card');
                    e.preventDefault();
                }
                else {
                    // do action
                }
            });
            $('.js-example-basic-multiple').select2({
                tags: true,
                theme: "flat",
                //maximumSelectionLength : 4
            }).on('change', function (e) {
                if ($(this).val() !== null) {
                    $('#tags-error').remove();
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