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
    @forelse($scenes as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.scene.destroy', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление группы"></i>
                </a>
            </td>
            <td>
                <a href="{{route('admin.scene.edit', [$row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать группу">
                    {{$row['name']}}
                </a>
            </td>
            <td>
                {{ $row->getGroup->name ?? '' }}
            </td>
            <td>
                {{$row['created_at']}}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Сюжетов нет</td>
        </tr>
    @endforelse
    </tbody>
</table>