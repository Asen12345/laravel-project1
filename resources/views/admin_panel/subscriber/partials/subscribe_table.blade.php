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
                {{$field['title']}}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @forelse($records as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.subscriber.delete', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление пользователя"></i>
                </a>
            </td>
            <td>
                {{$row->blog->subject}}
            </td>
            <td>
                <select autocomplete="off" data-id="{{$row->id}}" class="form-control change_selector" name="active">
                    <option value="1" {{$row['active'] == 1 ? 'selected' : ''}}>Да</option>
                    <option value="0" {{$row['active'] == 0 ? 'selected' : ''}}>Нет</option>
                </select>
            </td>
            <td>
                {{$row->email}}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Записей нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>