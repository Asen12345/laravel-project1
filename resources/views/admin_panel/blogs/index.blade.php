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
    @include('admin_panel.blogs.partials.'.$content['page'].'_filter', ['page' => $content['page']])
    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-body table-responsive no-padding">
                @include('admin_panel.blogs.partials.blogs_table', ['table' => $content['fields'], 'blogs' => $blogs, 'sort_by' => $sort_by, 'sort_order' => $sort_order])
            </div>
        </div>
        <div class="pull-right">
            {{ $blogs->onEachSide(2)->links() }}
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        $(document).ready(function () {

            $(".subject_edit").dblclick(function (e) {
                e.stopPropagation();
                let currentEle = $(this);
                let value = $(this).text();
                let id = $(this).data('id');
                updateVal(currentEle, value, id);
            });
            $('.fa_subject_edit').click(function (e) {
                e.stopPropagation();
                let id = $(this).data('id');
                let currentEle = $('#subject_' +id );
                let value = currentEle.text();
                updateVal(currentEle, value, id);
            });
            $('.change_selector').change(function () {
                let id = $(this).data('id');
                let active = $(this).val();
                edit(id, active);
            });
            function updateVal(currentEle, value, id) {
                $(currentEle).html('<input id="thVal_'+ id +'" type="text" value="' + value + '" />');
                $('#thVal_' + id).focus()
                    .keyup(function (event) {
                    if (event.keyCode === 13) {
                        $(currentEle).html($('#thVal_' + id).val().trim());
                        edit(id);
                    }
                });

            }

            function edit(id, active = null) {
                let input = $('#subject_' + id).text();
                $.ajax({
                    type: "POST",
                    url: '{{route('admin.blogs.edit')}}',
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