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
                <form class="form-horizontal" method="POST" action="{{route('admin.resources.company.merged')}}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group row">
                        <div class="col-12">
                            <div class="box-title">Будут объеденены следующие компании:</div>
                        </div>
                    </div>
                    @foreach($companies as $company)
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{$company->name}}" disabled>
                                <input name="company_old[{{$company->id}}]" type="hidden">
                            </div>
                        </div>
                    @endforeach
                    <hr/>
                    <div class="form-group row h-mt-10">
                        <div class="col-12">
                            <div class="box-title">Выберите новую компанию для пользователей</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <select autocomplete="off" name="company_new" class="form-control">
                                @foreach($all_companies as $row)
                                    <option width="300px;" value="{{$row->id}}">{{$row->name}}</option>
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