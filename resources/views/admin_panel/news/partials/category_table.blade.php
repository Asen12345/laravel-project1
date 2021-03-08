<table class="table table-hover">
    <thead>
    <tr>
        <th></th>
        @foreach($table as $key => $field)
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
    @forelse($categories as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.category.destroy', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a class="delete" href="javascript:" data-id="{{$row['id']}}"><i class='fa fa-trash' data-toggle="tooltip" title="Удаление категории"></i>
                </a>
            </td>
            <td>
                <a href="{{route('admin.category.edit', [$row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать категорию">
                    {{$row['name']}}
                </a>
            </td>
            <td>
                {{$row['sort']}}
            </td>
            <td>
                {{$row['created_at']}}
            </td>
        </tr>
        @forelse ($row->child as $child)
            <tr>
                <td nowrap width='3%'>
                    <form class='form-inline' action="{{route('admin.category.destroy', [$child->id])}}" method="POST" id='formDelete{{$child->id}}'>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                    <a class="delete" href="javascript:" data-id="{{$child->id}}"><i class='fa fa-trash' data-toggle="tooltip" title="Удаление категории"></i>
                    </a>
                </td>
                <td colspan="2">
                    <a href="{{route('admin.category.edit', [$child->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать категорию">
                        {{$row->name}} > {{$child->name}}
                    </a>
                </td>
                <td>
                    {{$child->created_at}}
                </td>
            </tr>

        @empty
        @endforelse
    @empty
        <tr>
            <td colspan="8" class="text-center">Категорий нет</td>
        </tr>
    @endforelse
    </tbody>
</table>