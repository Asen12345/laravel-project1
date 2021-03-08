@extends('admin_panel.layouts.app')

@section('section_title')
    {{$content['title']}}
    <input type="checkbox" name="all" @if(request()->exists('all')) checked @endif>
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
                        <th></th>
                        @foreach($content['fields'] as $key => $field)
                            <th>
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
                            <td nowrap width='3%'>
                                <form class='form-inline' action="{{route('admin.resources.company.destroy', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление записи"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.resources.company.edit', ['id' => $row->id])}}">{{$row->name}}</a>
                            </td>
                            <td>
                                {{$row->type_count}}
                            </td>
                            <td>
                                {{$row->users_count}}
                            </td>
                            <td>
                                <input class="merge-input" type="checkbox" name="checked" data-id="{{$row->id}}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right">
            {{ $data->appends(['sort_by' => request()->sort_by, 'sort_order' => request()->sort_order, 'all' => request()->all])->onEachSide(2)->links() }}
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.resources.company.create')}}">Добавить</a>
        </div>
        <div class="pull-right">
            <form class='form-inline' action="{{route('admin.resources.company.merge')}}" method="POST" id='merge'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <a class="btn btn-primary btn-flat" onClick="merge()" href="javascript:">Объединить выделенные компании</a>
        </div>

    </div>
@endsection
@section('footer_js')
    <script>
        function merge() {
            let input = $('.merge-input:checked');
            if (input.length < 1) {
                alert('Не выбраны компании')
            } else {
                $('#merge').submit()
            }
        }
        $(document).ready(function () {
            $('.merge-input').change(function() {
                if ($(this).is(':checked'))  {
                    appendInput($(this).data('id'))
                } else {
                    let id = $(this).data('id');
                    $(document).find('input#' +id ).remove()
                }
            });
            function appendInput(id){
                $('<input>').attr({
                    type: 'hidden',
                    id: id,
                    name: 'company[]',
                    value: id
                }).appendTo('#merge');
            }

            $('[name="all"]').on('change', function() {
                let uri = document.location.href;
                if ($(this).prop('checked')) {
                    uri = (uri.indexOf('?') !== -1)?
                       uri+'&all=true' : uri+'?all=true';
                } else {
                    uri = (uri.indexOf('?all=true') !== -1)?
                        uri.replace('?all=true', '?') : uri.replace('&all=true', '');
                }

                document.location.href = uri;
            });
        })
    </script>
@endsection