<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Услуги</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        @forelse($userServices as $service)
            <div class="row offset-md-1 service-row {{$loop->last ? 'last' : ''}}" id="{{$loop->iteration}}">
                <div class="col-md-8">
                    <input class="form-control" name="services[{{$loop->iteration}}][name]" value="{{$service->name}}" required/>
                </div>
                <div class="button-del">
                    <a href="javascript:;" class="btn btn-danger row-del" data-button='{{$loop->iteration}}'>Удалить</a>
                </div>
                <div class="col-md-9 h-mt-20">
                    <textarea name="services[{{$loop->iteration}}][text]" cols="20" rows="10" class="form-control summernote" required>{{$service->text}}</textarea>
                    <span class="error-custom" id="error-services[{{$loop->iteration}}][text]"></span>
                </div>
            </div>
            <hr/>
        @empty
            <div class="row offset-md-1 service-row last" id="1">
                <div class="col-md-8">
                    <input class="form-control" name="services[1][name]" value="" required/>
                </div>
                <div class="button-del">
                    <a href="javascript:;" class="btn btn-danger row-del" data-button='1'>Удалить</a>
                </div>
                <div class="col-md-9 h-mt-20">
                    <textarea name="services[1][text]" cols="20" rows="10" class="form-control summernote" required></textarea>
                    <span class="error-custom" id="error-services[1][text]"></span>
                </div>
            </div>
            <hr/>
        @endforelse
        <div id="insert">

        </div>
        <div class="services-buttons float-left">
            <a href="javascript:;" class="btn btn-primary add-row">Добавить</a>
        </div>
    </div>
</div>

@section('footer_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-image-title.js')}}"></script>
    <script>
        $(document).ready(function () {
            summernoteRender('textarea[name^="services["]');
            $('#form').validate({
                lang: 'ru',
                ignore: 'summernote *',
                errorPlacement: function(error, element) {

                    if (element.hasClass("summernote")){
                        console.log(error, element);
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
                last_row.removeClass('last');
                count = parseInt(count) + 1;
                item.attr('id', count);
                item.find('input').attr('name', 'services['+ count +'][name]').val('');
                item.find('textarea.summernote').attr('name', 'services['+ count +'][text]').val('');
                item.find('span.error-custom').prop('id', 'error-services['+ count +'][text]');
                item.find('a').attr('data-button', count);
                item.find('.note-editor.note-frame.card').remove();
                item.appendTo("#insert");
                summernoteRender('textarea[name="services['+ count +'][text]"]');
            });
            function summernoteRender(name){
				$(name).summernote({
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
            $(document).on('click', '.row-del', function () {
                if(confirm('Вы действительно хотите удалить элемент? Не забудьте сохранить изменения.'))
                {
                    let count = $(this).data('button');
                    $('#'+count).remove();
                }

            });
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
        })
    </script>
@endsection