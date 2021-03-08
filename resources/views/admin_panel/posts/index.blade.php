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
    @include('admin_panel.posts.partials.posts_filter', ['filter_data' => $filter_data, 'page' => $content['page']])
    <div class="col-12">
        <div class="box  box-primary">
			<div class="pull-right" style="margin-bottom:10px;margin-top:10px;margin-right:10px;">
				<a class="btn btn-primary btn-flat" href="{{route('admin.posts.create')}}">Добавить</a>
			</div>
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.posts.partials.posts_table', ['table' => $content['fields'], 'posts' => $posts, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $posts->appends([
                'sort_by' => $sort_by,
                'sort_order' => $sort_order,
                'title' => $filter_data['title'] ?? null,
                'subject' => $filter_data['subject'] ?? null,
                'published' => $filter_data['published'] ?? null,
            ])->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.posts.create')}}">Добавить</a>
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        $('.change_selector').change(function () {
            let id = $(this).data('id');
            let active = $(this).val();
            edit(id, active);
        });

        function edit(id, active = null) {
            let input = $('#subject_' + id).text();
            $.ajax({
                type: "POST",
                url: '{{route('admin.post.activate')}}',
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
    </script>
@endsection