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
                <h3 class="box-title">Редактирование категории</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.category.update', ['id' => $category->id])}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-6">
                            <input id="name" name="name" type="text" class="form-control" value="{{$category->name}}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="url_ru" class="col-sm-3 col-form-label text-right">URL</label>
                        <div class="col-sm-6">
                            <input id="url_ru" name="url_ru" type="text" class="form-control" value="{{$category->url_ru}}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="url_en_show" class="col-sm-3 col-form-label text-right">URL(en)</label>
                        <div class="col-sm-6">
                            <input disabled="" type="text" class="form-control url_en" value="{{$category->url_en}}">
                            <input id="url_en" name="url_en" type="hidden" class="form-control url_en" value="{{$category->url_en}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_title" class="col-sm-3 col-form-label text-right">Meta Title</label>
                        <div class="col-sm-6">
                            <input id="meta_title" name="meta_title" type="text" class="form-control" value="{{$category->meta_title}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_keywords" class="col-sm-3 col-form-label text-right">Meta Keywords</label>
                        <div class="col-sm-6">
                            <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" value="{{$category->meta_keywords}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="meta_description" class="col-sm-3 col-form-label text-right">Meta Description</label>
                        <div class="col-sm-6">
                            <input id="meta_description" name="meta_description" type="text" class="form-control" value="{{$category->meta_description}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="parent_id" class="col-sm-3 col-form-label text-right">Родительская категория</label>
                        <div class="col-sm-6">
                            <select autocomplete="off" name="parent_id" id="parent_id" class="form-control">
                                <option value="0">Нет</option>
                                @if($category->child->isEmpty())
                                    @foreach($categories as $row)
                                        <option value="{{$row->id}}" {{$row->id === $category->parent_id ? 'selected' : ''}}>{{$row->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if($category->child->isNotEmpty())
                                <small>Категория имеет вложеность (макс уровень 2). Удалите или перенесите дочерние категории</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sort" class="col-sm-3 col-form-label text-right">Сортировка</label>
                        <div class="col-sm-6">
                            <input id="sort" name="sort" type="number" class="form-control" value="{{$category->sort}}" required="">
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
    <script>
        $(document).ready(function () {
            $('#url_ru').on('keyup', function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate.slug') }}",
                    data: {
                        _token:"{{ csrf_token() }}",
                        text: $(this).val()
                    },
                    success: function(data) {
                        $('.url_en').val(data)
                    },
                })

            });
        })
    </script>
@endsection