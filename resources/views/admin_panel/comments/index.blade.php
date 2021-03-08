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
    @include('admin_panel.comments.partials.comments_filter', ['filter_data' => $filter_data, 'page' => $content['page']])
    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.comments.partials.comments_table', ['table' => $content['fields'], 'comments' => $comments, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $comments->appends([
                'sort_by' => $sort_by,
                'sort_order' => $sort_order,
                'title'     => $filter_data['title'] ?? null,
                'user'     => $filter_data['user'] ?? null,
                'published'     => $filter_data['published'] ?? null,
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
                    url: '{{route('admin.comments.activate')}}',
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