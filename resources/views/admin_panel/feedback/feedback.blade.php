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
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        @foreach($content['fields'] as $key => $field)
                            <th class="text-center">
                                @if (!empty(Request::input('sort_order')) && Request::input('sort_by') === $key)
                                    @php $sorted = Request::input('sort_order'); $icon = '-'. $sorted ; $sort = $sorted === 'desc' ? 'asc' : 'desc' ; @endphp
                                @else
                                    @php $sort = 'desc'; $icon = '' @endphp
                                @endif
                                <a class="sort-order" href='{{$field['sort_url'] . '&sort_order='. $sort}}'>{{$field['title']}} <i class='fa fa-sort{{$icon}}'></i></a>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td>
                                <i data-id="{{$row->id}}" data-type="subject" class='fa fa-edit fa_subject_edit' style="color:#72afd2"></i>
                                <span data-id="{{$row->id}}" data-type="subject" class="subject_edit" id="subject_{{$row->id}}">{{$row->subject}}</span>
                            </td>
                            <td>
                                <i data-id="{{$row->id}}" data-type="email" class='fa fa-edit fa_subject_edit' style="color:#72afd2"></i>
                                <span data-id="{{$row->id}}" data-type="email" class="subject_edit" id="email_{{$row->id}}">{{$row->email}}</span>
                            </td>
                            <td>
                                <form class='form-inline' action="{{route('admin.feedback.destroy', [$row->id])}}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-danger btn-flat">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right">
            {{ $data->appends(['sort_by' => request()->sort_by, 'sort_order' => request()->sort_order])->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.feedback.create')}}">Добавить</a>
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
                let type = $(this).data('type');
                updateVal(currentEle, value, id, type);
            });
            $('.fa_subject_edit').click(function (e) {
                e.stopPropagation();
                let id = $(this).data('id');
                let type = $(this).data('type');
                let currentEle = $('#'+ type +'_' +id );
                let value = currentEle.text();
                console.log(currentEle, value, id, type);
                updateVal(currentEle, value, id, type);
            });

            function updateVal(currentEle, value, id, type) {
                $(currentEle).html('<input id="thVal_'+ id +'" type="text" value="' + value + '" />');
                $('#thVal_' + id).focus()
                    .keyup(function (event) {
                        if (event.keyCode === 13) {
                            $(currentEle).html($('#thVal_' + id).val().trim());
                            edit(id, type);
                        }
                    });

            }

            function edit(id, type) {
                let input = $('#'+ type +'_' + id).text();
                $.ajax({
                    type: "POST",
                    url: '{{route('admin.feedback.update')}}',
                    data: {
                        _token:"{{ csrf_token() }}",
                        id: id,
                        value: input,
                        type: type
                    },
                    success: function(data) {
                        alert(data['response']);
                    },
                })
            }
        })
    </script>
@endsection