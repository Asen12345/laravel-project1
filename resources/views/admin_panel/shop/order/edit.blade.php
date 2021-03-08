@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
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
                <h3 class="box-title">Просмотр заказа № И-{{ $order->id }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.shop.researches.orders.order.update', ['id' => $order->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label text-right">Заказ</label>
                        <div class="col-sm-7">
                            <input id="id" name="id" type="text" class="form-control" value="№ И-{{ $order->id }}" required="" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="user_id" class="col-sm-3 col-form-label text-right">Фамилия/Имя</label>
                        <div class="col-sm-7">
                            <input id="user_id" name="user_id" type="text" class="form-control" value="{{ $order->user->name }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="total" class="col-sm-3 col-form-label text-right">Сумма</label>
                        <div class="col-sm-7">
                            <input id="total" name="total" type="text" class="form-control" value="{{ $order->total_count }} руб" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="created_at" class="col-sm-3 col-form-label text-right">Дата создания</label>
                        <div class="col-sm-2">
                            <input id="created_at" disabled name="created_at" type="text" class="form-control datep" value="{{ \Carbon\Carbon::parse($order->created_at)->isoFormat("DD MMMM YYYY") }}">
                        </div>
                    </div>

                    @forelse($order->purchases as $product)
                        <div class="form-group row">
                            @if ($loop->iteration == 1)
                                <label for="created_at" class="col-sm-3 col-form-label text-right">Исследования</label>
                            @endif
                            <div class="col-sm-7 offset-3">
                                <input id="total" name="total" type="text" class="form-control" value="{{ $product->research->title }}" disabled>
                            </div>
                        </div>
                    @empty

                    @endforelse

                    <div class="form-group row">
                        <label for="updated_at" class="col-sm-3 col-form-label text-right">Дата обновления</label>
                        <div class="col-sm-2">
                            <input id="updated_at" disabled name="updated_at" type="text" class="form-control datep" value="{{ \Carbon\Carbon::parse($order->updated_at)->isoFormat("DD MMMM YYYY") }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label text-right">Статус</label>
                        <div class="col-sm-2">
                            <select autocomplete="off" data-id="{{$order->id}}" class="form-control change" name="status">
                                <option value="waiting" {{ $order->status == 'waiting' ? 'selected' : ''}}>Ожидание</option>
                                <option value="started" {{ $order->status == 'started' || $order->status == 'cancelled'? 'selected' : ''}}>Незаконченный</option>
                                <option value="paid" {{ $order->status == 'paid' ? 'selected' : ''}}>Оплачен</option>
                                <option value="send" {{ $order->status == 'send' ? 'selected' : ''}}>Отправлен</option>
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
    <script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
@endsection