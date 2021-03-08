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
                        <th>
                        </th>
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
                    @foreach($data as $tag)
                        <tr>
                            <td nowrap width='3%'>
                                <form class='form-inline' action="{{route('admin.resources.tags.destroy', [$tag->id])}}" method="POST" id='formDelete{{$tag['id']}}'>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$tag['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление тега"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.resources.tags.edit', ['id' => $tag->id])}}">{{$tag->name}}</a>
                            </td>
                            <td>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-left">
            <a class="btn btn-primary btn-flat" href="{{route('admin.resources.tags.create')}}">Добавить</a>
        </div>
        <div class="pull-right">
            {{ $data->appends(['sort_by' => request()->sort_by, 'sort_order' => request()->sort_order])->onEachSide(2)->links() }}
        </div>
    </div>
@endsection