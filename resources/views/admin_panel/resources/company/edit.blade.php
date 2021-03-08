@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
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
            <div class="box-header with-border">
                <h3 class="box-title">{{$content['title']}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.resources.company.update', ['id' => $company->id])}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок русский</label>
                        <div class="col-sm-6">
                            <input id="name" name="name" type="text" class="form-control" value="{{$company->name}}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name_en" class="col-sm-3 col-form-label text-right">Заголовок английский</label>
                        <div class="col-sm-6">
                            <input id="name_en" name="name_en" type="text" class="form-control" value="{{$company->name_en}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type_id" class="col-sm-3 col-form-label text-right">Сфера деятельности</label>
                        <div class="col-sm-6">
                            <select autocomplete="off" name="type_id" class="form-control">
                                @foreach($company_type as $row)
                                    <option {{$company->type_id == $row->id ? 'selected' : ''}} value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-3 col-form-label text-right">Сотрудники компании</div>
                        <div class="col-sm-6">
                            <select autocomplete="off" class="filter form-control js-example-basic-multiple" id="tags" name="users[]" multiple="multiple">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="box-footer text-center mt-3">
                        <button type="submit" class="btn btn-primary center-block">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('.js-example-basic-multiple').select2({
                tags: false,
                theme: "flat",
                //maximumSelectionLength : 4,
                ajax: {
                    type: "POST",
                    url: "{{route('admin.news.autocomplete.author')}}",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token:"{{ csrf_token() }}",
                            value: params.term,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    }
                },
                language: {
                    noResults: function () {
                        return 'Ничего не найдено';
                    },
                    searching: function () {
                        return 'Поиск…';
                    },
                }
            }).trigger('change');
        })
    </script>
@endsection