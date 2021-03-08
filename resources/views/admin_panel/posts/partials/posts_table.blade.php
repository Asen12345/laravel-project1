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
    @forelse($posts as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.posts.delete', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление пользователя"></i>
                </a>
            </td>
            <td>
                <a href="{{route('admin.posts.edit', [$row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать пост">
                    {{$row->title}}
                </a>
            </td>
            <td>
                {{!is_null($blog) ? $blog->subject : $row->blog->subject}}
            </td>
            <td>
                <select autocomplete="off" data-id="{{$row->id}}" class="form-control change_selector" name="published">
                    <option value="1" {{$row['published'] == 1 ? 'selected' : ''}}>Да</option>
                    <option value="0" {{$row['published'] == 0 ? 'selected' : ''}}>Нет</option>
                </select>
            </td>
            <td>
                @if ($row->comments_count > 0)
                    <a href="{{route('admin.comments.index', ['id' => $row->id])}}">{{$row->comments_count}}</a>
                @else
                    0
                @endif
            </td>
            <td>
                @if ($row->subscribers_count > 0)
                    <a href="{{route('admin.subscriber.index', ['blog_id' => $row->blog_id])}}">{{$row->subscribers_count ?? '0'}}</a>
                @else
                    0
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Записей нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>