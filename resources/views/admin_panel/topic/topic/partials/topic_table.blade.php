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
    @forelse($topics as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.topic.destroy', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись!')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление блога"></i>
                </a>
            </td>
            <td>
                <a href="{{route('admin.topic.edit', ['topic_id' => $row->id])}}">
                    <span>{{$row->title}}</span>
                </a>
            </td>
            <td>
                <span>{{\Carbon\Carbon::parse($row->published_at)->format('d-m-Y')}}</span>
            </td>
            <td>
                <span>{{$row->published == true ? 'Да' : 'Нет'}}</span>
            </td>
            <td>
                <a href="{{route('admin.answer.index', ['id' => $row->id])}}">
                    <span>{{$row->answers_count ?? '0'}}</span>
                </a>
            </td>
            <td>
                <select autocomplete="off" data-id="{{$row->id}}" class="form-control change_selector" name="main">
                    <option value="1" {{$row->main_topic == 1 ? 'selected' : ''}}>Да</option>
                    <option value="0" {{$row->main_topic == 0 ? 'selected' : ''}}>Нет</option>
                </select>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Тем нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>