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
                <h3 class="box-title">Редактирование Сюжетной группы</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.scene-group.update', ['id' => $scene_group->id])}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-6">
                            <input id="name" name="name" type="text" class="form-control" value="{{$scene_group->name}}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sort" class="col-sm-3 col-form-label text-right">Сортировка</label>
                        <div class="col-sm-6">
                            <input id="sort" name="sort" type="number" class="form-control" value="{{$scene_group->sort}}" required="">
                        </div>
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