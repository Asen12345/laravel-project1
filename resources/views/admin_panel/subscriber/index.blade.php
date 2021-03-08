@extends('admin_panel.layouts.app')

@section('section_header')

@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')
    <!--@include('admin_panel.subscriber.partials.subscribe_filter', ['filter_data' => $filter_data, 'page' => $content['page']])-->
    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.subscriber.partials.subscribe_table', ['table' => $content['fields'], 'records' => $records, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $records->appends([
                'sort_by' => $sort_by,
                'sort_order' => $sort_order,
                'subject'   => $filter_data['subject'] ?? null,
                'email'   => $filter_data['email'] ?? null,
                'active'   => $filter_data['active'] ?? null,
            ])->onEachSide(2)->links() }}
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        $(document).ready(function () {
            $('.change_selector').change(function () {
                let id = $(this).data('id');
                let active = $(this).val();
                edit(id, active);
            });

            function edit(id, active = null) {
                let input = $('#subject_' + id).text();
                $.ajax({
                    type: "POST",
                    url: '{{route('admin.subscriber.activate')}}',
                    data: {
                        _token:"{{ csrf_token() }}",
                        id: id,
                        value: input,
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