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

    @include('admin_panel.shop.partials.researches_filter')

    <div class="col-12">
        <div class="box  box-primary">
			<div class="pull-right" style="margin-bottom:10px;margin-top:10px;margin-right:10px;">
				<a class="btn btn-primary btn-flat" href="{{route('admin.shop.researches.create')}}">Добавить</a>
			</div>
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.shop.partials.researches_table', ['table' => $content['fields'], 'researches' => $researches, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $researches->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.shop.researches.create')}}">Добавить</a>
        </div>
    </div>
@endsection

@section('footer_js')
    <script src="{{ asset('/assets/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('/assets/jquery-ui/datepicker-ru.js') }}"></script>
    <script src="{{ url('/js/datep.js') }}"></script>
@endsection