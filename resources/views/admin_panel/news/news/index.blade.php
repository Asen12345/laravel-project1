@extends('admin_panel.layouts.app')

@section('section_header')
    <link rel="stylesheet" href="/assets/jquery-ui/jquery-ui.min.css">
@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    @include('admin_panel.news.partials.'.$content['page'].'_filter', ['page' => $content['page']])

    <div class="col-12">
        <div class="box  box-primary">
		 <div class="pull-right" style="margin-bottom:10px;margin-top:10px;margin-right:10px;">
            <a class="btn btn-primary btn-flat" href="{{route('admin.news.create')}}">Добавить</a>
		 </div>
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.news.partials.news_table', ['table' => $content['fields'], 'news' => $news, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $news->onEachSide(2)->links() }}
        </div>

    </div>
@endsection

@section('footer_js')
    <script src="{{ asset('/assets/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('/assets/jquery-ui/datepicker-ru.js') }}"></script>
    <script src="{{ url('/js/datep.js') }}"></script>
@endsection