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
			<div class="pull-right" style="margin-bottom:10px;margin-top:10px;margin-right:10px;">
				<a class="btn btn-primary btn-flat" href="{{route('admin.banner.create')}}">Добавить</a>
			</div>
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.banner.partials.banner_table', ['table' => $content['fields'], 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $banners->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order])->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.banner.create')}}">Добавить</a>
        </div>
    </div>
@endsection

@section('footer_js')

@endsection