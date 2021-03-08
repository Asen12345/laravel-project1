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
                <h3 class="box-title">{{$content['title']}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.resources.company.store')}}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок русский</label>
                        <div class="col-sm-6">
                            <input id="name" name="name" type="text" class="form-control" value="{{old('name')}}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name_en" class="col-sm-3 col-form-label text-right">Заголовок английский</label>
                        <div class="col-sm-6">
                            <input id="name_en" name="name_en" type="text" class="form-control" value="{{old('name_en')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type_id" class="col-sm-3 col-form-label text-right">Сфера деятельности</label>
                        <div class="col-sm-6">
                            <select autocomplete="off" name="type_id" class="form-control">
                                @foreach($company_type as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
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