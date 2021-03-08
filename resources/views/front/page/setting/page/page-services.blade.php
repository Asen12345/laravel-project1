@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="tab-friend companies-list active">
        <form id="services" class="li-form" method="POST" action="{{route('front.setting.account.update', ['type' => 'services'])}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            @forelse($services as $service)
                <div class="form-group row service-row {{$loop->last ? 'last' : ''}}" id="{{$loop->iteration}}">
                    <div class="col-10">
                        <input class="li-form-input" name="content[{{$loop->iteration}}][name]" value="{{$service->name}}" required/>
                    </div>
                    <div class="button-del">
                        <a href="javascript:;" class="button button-micro button-l-red row-del" data-button='{{$loop->iteration}}'>Удалить</a>
                    </div>
                    <div class="col-12 h-mt-20">
                        <textarea name="content[{{$loop->iteration}}][text]" cols="30" rows="10" class="summernote li-form-area" required>{{$service->text}}</textarea>
                        <span class="error-custom" id="error-content[{{$loop->iteration}}][text]"></span>
                    </div>
                </div>
                <hr/>
            @empty
                <div class="form-group row service-row last" id="1">
                    <div class="col-10">
                        <input class="li-form-input" name="content[1][name]" value="" required/>
                    </div>
                    <div class="button-del">
                        <a href="javascript:;" class="button button-micro button-l-red row-del" data-button='1'>Удалить</a>
                    </div>
                    <div class="col-12 h-mt-20">
                        <textarea name="content[1][text]" cols="30" rows="10" class="summernote li-form-area" required></textarea>
                        <span class="error-custom" id="error-content[1][text]"></span>
                    </div>
                </div>
                <hr/>
            @endforelse
            <div id="insert">

            </div>
            <div class="content-buttons float-left">
                <a href="javascript:;" class="button button-dark-blue add-row">Добавить</a>
            </div>
            <div class="button_color_red float-right">
                <button type="submit" class="button">Сохранить изменения</button>
            </div>
        </form>
    </div>

@endsection

@section('js_footer_account')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
	    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script>
        $(document).ready(function () {
            summernoteRender('textarea');
            $('#services').validate({
                lang: 'ru',
                ignore: 'summernote *',
                errorPlacement: function(error, element) {
                    console.log(error, element);
                    if (element.hasClass("summernote")){
                        error.insertAfter(document.getElementById('error-' + element.attr('name')));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            $('.add-row').on('click', function () {
                let last_row = $(".last");
                let count = last_row.attr('id');
                let item = last_row.clone();
                /*удаляем редактор -> потом добавим*/
                item.find('.note-editor').remove();
                last_row.removeClass('last');
                count = parseInt(count) + 1;
                item.attr('id', count);
                item.find('input').attr('name', 'content['+ count +'][name]').val('');
                item.find('textarea').attr('name', 'content['+ count +'][text]').val('');
                item.find('span.error-custom').prop('id', 'error-content['+ count +'][text]');
                item.find('a').attr('data-button', count);
                item.find('.button-del').css('display', 'block');
                item.appendTo("#insert");
                summernoteRender('textarea[name="content['+ count +'][text]"]');
            });
            function summernoteRender(name){
                $(name).summernote({
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
                            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        },
                        onImageUpload: function(image) {
                            uploadImage(image[0], name);
                        },
                    }
                }).on('summernote.keyup', function() {
                    let text = $(this).summernote("code").replace(/&nbsp;|<\/?[^>]+(>|$)/g, "").trim();
                    if (text.length === 0) {
                        $(this).summernote('code', '');
                    } else {
                        document.getElementById($(this).attr('name') + "-error").remove();
                    }
                });
            }
            function uploadImage(image, name) {
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
                            $(name).summernote("insertNode", image[0]);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
            $(document).on('click', '.row-del', function () {
                if(confirm('Вы действительно хотите удалить элемент? Не забудьте сохранить изменения.'))
                {
                    let count = $(this).data('button');
                    $('#'+count).remove();
                }
            });
        })
    </script>
@endsection