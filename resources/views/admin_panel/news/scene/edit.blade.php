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
                <h3 class="box-title">Добавление Сюжета</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.scene.update', ['id' => $scene->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-6">
                            <input id="name" name="name" type="text" class="form-control" value="{{old('name') ?? $scene->name}}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="news_scene_group_id" class="col-sm-3 col-form-label text-right">Сюжетная группа</label>
                        <div class="col-sm-6">
                            <select autocomplete="off" class="filter form-control" id="news_scene_group_id" name="news_scene_group_id">
                                <option></option>
                                @foreach($scene_groups as $group)
                                    <option value="{{$group->id}}" {{(old('news_scene_group_id') ==  $group->id  ? 'selected' : '') | ($group->id == $scene->news_scene_group_id ? 'selected' : '')}}>{{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @include('admin_panel.components.picture_upload.picture_upload_modal')

                    <div class="form-group row">
                        <label for="image" class="col-sm-3 col-form-label text-right">Пиктограмма</label>
                        <div class="col-sm-6">
                            <img id="user_avatar" class="img-fluid" src="{{ $scene->image ? url($scene->image) : url('/img/no_picture.jpg') }}" alt="Image">
                            <input type="button" class="btn btn-default" value="Сменить" onclick="select_avatar()">
                            <input type="hidden" name="image" id="picture" value="{{$scene->image ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="meta_title" class="col-sm-3 col-form-label text-right">Meta Title</label>
                        <div class="col-sm-6">
                            <input id="meta_title" name="meta_title" type="text" class="form-control" value="{{old('meta_title') ?? $scene->meta_title}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_keywords" class="col-sm-3 col-form-label text-right">Meta Keywords</label>
                        <div class="col-sm-6">
                            <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{old('meta_keywords') ?? $scene->meta_keywords}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_description" class="col-sm-3 col-form-label text-right">Meta Description</label>
                        <div class="col-sm-6">
                            <input id="meta_description" name="meta_description" type="text" class="form-control" value="{{old('meta_description') ?? $scene->meta_description}}">
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
    @include('admin_panel.components.picture_upload.picture_upload_js', ['type' => 'admin'])
@endsection