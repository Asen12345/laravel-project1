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
    @forelse($banners as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.banner.destroy', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление записи"></i>
                </a>
            </td>
            <td>
                <a href="{{route('admin.banner.edit', ['id' => $row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать запись">
                    {{$row->name}}
                </a>
            </td>
            <td>
                {{$row->bannerPlace->place ?? 'НЕТ'}}
            </td>
            <td>
                {{$row->published == true ? 'ДА' : 'НЕТ'}}
            </td>
            <td>
                {{$row->views_count}}
            </td>
            <td>
                {{$row->click ?? '0'}}
            </td>
            <td>
                <form action="{{route('admin.banner.clear.statistic', ['id' => $row->id])}}" method="post" class="li-form">
                    @csrf
                    <button class="btn btn-danger" type="submit">Очистить статистику</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">Баннеров нет</td>
        </tr>
    @endforelse
    </tbody>
</table>