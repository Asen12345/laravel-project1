@extends('admin_panel.layouts.app')

@section('section_header')

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
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.widgets.partials.widget_table', ['table' => $content['fields'], 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $banners->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order])->onEachSide(2)->links() }}
        </div>
    </div>
@endsection

@section('footer_js')

@endsection