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
                <h3 class="box-title">Редактирование</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{ route('admin.shop.researches.settings.metatags.update', [$template->id]) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label text-right">Название шаблона</label>
                        <div class="col-sm-10">
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name') ?? $template['name'] }}" required>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label for="meta_title" class="col-sm-2 col-form-label text-right">Meta Title</label>
                        <div class="col-sm-10">
                            <input id="meta_title" name="meta_title" type="text" class="form-control" value="{{ old('meta_title') ?? $template['meta_title'] }}" required>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label for="meta_keywords" class="col-sm-2 col-form-label text-right">Meta Keyword</label>
                        <div class="col-sm-10">
                            <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{ old('meta_keywords') ?? $template['meta_keywords'] }}" required>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label for="meta_description" class="col-sm-2 col-form-label text-right">Meta Description</label>
                        <div class="col-sm-10">
                            <div class="form-group">
                                <textarea id="meta_description" class="form-control" name="meta_description" rows="5">{{ old('meta_description') ?? $template['meta_description'] }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer row">
                        <div class="col-auto m-auto">
                            <button type="submit" class="btn btn-primary pull-right">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection