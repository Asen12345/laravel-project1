@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/timepicker/css/timepicki.css')}}" rel="stylesheet">
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

                @if (!empty($footer))
                    <hr/>
                    {!! $footer->footer_text !!}
                    <hr/>
                    {!! str_replace('[ссылка]', '<a href="#">по ссылке</a>', $footer->unsubscribe_text) !!}
                @endif

            </div>
        </div>
        <a href="{{ route('admin.newsletter.index') }}" class="ml-3 btn button btn-primary text-white float-left">Назад</a>
    </div>
@endsection