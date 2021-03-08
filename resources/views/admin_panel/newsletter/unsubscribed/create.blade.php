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
            <div class="box-body table-responsive">
                <form action="{{route('admin.newsletter.subscribers.subscribe')}}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group row pt-3">
                        <label for="email" class="col-sm-1 col-form-label text-right">Email:</label>
                        <div class="col-sm-4">
                            <input id="email" type="text" value="{{old('email')}}" name="email" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary btn-flat">Добавить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection