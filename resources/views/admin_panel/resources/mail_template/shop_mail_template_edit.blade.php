@extends('admin_panel.layouts.app')

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
                <form class="form-horizontal" method="POST" action="{{route('admin.shop.researches.settings.templates.update', [$template->id])}}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label text-right">Название шаблона</label>
                        <div class="col-sm-10">
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name') ?? $template['name'] }}" required>
                        </div>
                    </div>

                    @if ($template->template_id !== 'support_mail')
                        <div class="form-group row mt-3">
                            <label for="subject" class="col-sm-2 col-form-label text-right">Тема письма</label>
                            <div class="col-sm-10">
                                <input id="subject" name="subject" type="text" class="form-control" value="{{ old('subject') ?? $template['subject'] }}" required>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row mt-3">
                        <label for="text" class="col-sm-2 col-form-label text-right">Шаблон письма</label>
                        <div class="col-sm-10">
                            <div class="form-group">
                                <textarea id="text" class="form-control summernote" name="text" rows="5">{{ old('text') ?? $template['text'] }}</textarea>
                            </div>
                        </div>

                    </div>

                        <div class="alert alert-success" role="alert">
                            <span class="mr-2 un"><u>Вы можете использовать теги:</u> </span>
                            @foreach($content['tags_use'] as $key => $tag)
                                <span class="mr-2">{{$key}} : {{$tag}}</span>{{$loop->last !== true ? ';' : ''}}
                            @endforeach
                        </div>
                    <div class="box-footer row">
                        <div class="col-auto m-auto">
                            <button type="submit" class="btn btn-primary pull-right">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('footer_js')
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-image-title.js')}}"></script>
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
@stop