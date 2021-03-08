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
    @forelse($comments as $row)
        @if ((!empty($row->user)) && (!empty($row->post->title)))
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.comments.delete', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление пользователя"></i>
                </a>
            </td>
            <td>
                <a href="{{route('admin.comments.edit', [$row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать комментарий">
                    {{$row->post->title}}
                </a>
            </td>
            <td>
                {{\Carbon\Carbon::parse($row->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}
            </td>
            <td>
                <select autocomplete="off" data-id="{{$row->id}}" class="form-control change_selector" name="published">
                    <option value="1" {{$row['published'] == 1 ? 'selected' : ''}}>Да</option>
                    <option value="0" {{$row['published'] == 0 ? 'selected' : ''}}>Нет</option>
                </select>
            </td>
            <td>
                @if ($row->user->permission == 'social')
                    <a target="_blank" href="{{!empty($row->socialProfileUser->web_site)}}">{{$row->user->name}}</a> <i class="fa fa-share"></i>
                @else
                    <a href="{{route('admin.users.edit', ['id' => $row->user->id])}}">{{$row->user->name}}</a>
                @endif
            </td>
            <td>
                {{$row->anonym == 1 ? 'Да' : 'Нет'}}
            </td>
        </tr>
        @endif
    @empty
        <tr>
            <td colspan="8" class="text-center">Записей нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>