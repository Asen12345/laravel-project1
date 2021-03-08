@extends('admin_panel.layouts.app')

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
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.news.partials.scene_table', ['table' => $content['fields'], 'scenes' => $scenes, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $scenes->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.scene.create')}}">Добавить</a>
        </div>
    </div>
@endsection