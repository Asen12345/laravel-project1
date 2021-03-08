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
                <h3 class="box-title">{{$content['title']}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route($content['post_route'], ['id' => $comment->id])}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <span class="col-form-label col">{{$comment->post->title}}</span>
                    </div>
                    <div class="form-group row">
                        <label for="text" class="col-sm-3 col-form-label text-right">Текст комментария</label>
                        <div class="col-sm-7">
                            <textarea id="text" name="text" class="form-control" rows="4" cols="50" required="">{{old('text') ?? $comment['text']}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="source_name" class="col-sm-3 col-form-label text-right">Автор комментария</label>
                        <span class="col-form-label col">{{$comment->user->name}}</span>
                    </div>
                    <div class="form-group row">
                        <label for="published" class="form-check-label col-sm-3 text-right">Опубликовано</label>
                        <div class="col-sm-7">
                            <input id="published" name="published" type="checkbox" {{$comment->published == true ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="anonym" class="form-check-label col-sm-3 text-right">Анонимность</label>
                        <div class="col-sm-7">
                            <input id="anonym" name="anonym" type="checkbox" {{$comment->anonym == true ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="published_at" class="col-sm-3 col-form-label text-right">Дата добавления</label>
                        <span class="col-form-label col">{{\Carbon\Carbon::parse($comment->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</span>
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

@endsection