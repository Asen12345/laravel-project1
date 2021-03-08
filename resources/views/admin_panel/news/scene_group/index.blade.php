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
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.news.partials.scene_group_table', ['table' => $content['fields'], 'scene_groups' => $scene_groups, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $scene_groups->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order])->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.scene-group.create')}}">Добавить</a>
        </div>
    </div>
@endsection