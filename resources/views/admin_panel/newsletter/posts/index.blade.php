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
    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.newsletter.posts.partials.posts_table', ['table' => $content['fields'], 'posts' => $posts, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $posts->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order])->onEachSide(2)->links() }}
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        $('input[name=to_newsletter]').change(function () {
            let id = $(this).data('id');
            if ($(this).is(':checked'))  {
                edit(id, 1);
            } else {
                edit(id, 0);
            }

        });

        function edit(id, active) {
            $.ajax({
                type: "POST",
                url: '{{route('admin.newsletter.news.active')}}',
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
    </script>
@endsection