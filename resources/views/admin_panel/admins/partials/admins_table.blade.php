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
    @forelse($users as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.'. $page .'.destroy', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление пользователя"></i>
                </a>
            </td>
            <td>
                <a href="{{route('admin.'. $page .'.edit', [$row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать пользователя">
                    {{$row['email']}}
                </a>
            </td>
            <td>
                {{$row['name']}}
            </td>
            <td>
                {{$row['active'] == 1 ? 'Да' : 'Нет'}}
            </td>
            @if ($page === 'admins')
                <td>
                    {{$row['role']}}
                </td>
            @endif
            @if ($page === 'users')
            <td>
                {{$row['block'] == 1 ? 'Да' : 'Нет'}}
            </td>
            <td>
                {{$row['notifications_subscribed'] == 1 ? 'Да' : 'Нет'}}
            </td>
            <td>
                {{$row['permission'] == 'info' ? 'Info' : ($row['permission'] == 'demo' ? 'Demo' : ($row['permission'] == 'full' ? 'Full' : '' ) )}}
            </td>
            <td>
                @if ($row->time > 1)
                    {{\Carbon\Carbon::parse($row->time)->format('H:i:s')}}
                @else
                    00:00:00
                @endif
            </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">Пользователей нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>