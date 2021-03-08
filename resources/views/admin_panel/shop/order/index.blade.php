@extends('admin_panel.layouts.app')

@section('section_header')
    <link rel="stylesheet" href="/assets/jquery-ui/jquery-ui.min.css">
@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    @include('admin_panel.shop.partials.orders_filter')

    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.shop.partials.orders_table', ['table' => $content['fields'], 'orders' => $orders, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $orders->onEachSide(2)->links() }}
        </div>
    </div>
@endsection

@section('footer_js')
    <script src="{{ asset('/assets/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('/assets/jquery-ui/datepicker-ru.js') }}"></script>
    <script src="{{ url('/js/datep.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.change').change(function () {
                let id = $(this).data('id');
                let active = $(this).val();
                edit(id, active);
            });
            function edit(id, active = null) {
                $.ajax({
                    type: "POST",
                    url: '{{route('admin.shop.researches.orders.update')}}',
                    data: {
                        _token:"{{ csrf_token() }}",
                        id: id,
                        active: active
                    },
                    success: function(data) {
                        if (data['success'] === 'false'){
                            alert(data['error']);
                        } else {
                            alert(data['mess']);
                        }
                    },
                })
            }
        })
    </script>
@endsection