<table class="table table-hover">
    <thead>
    <tr>
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
            <td>
                <a href="{{route('admin.widgets.edit', ['id' => $row->id])}}">
                    <span>{{$row->name}}</span>
                </a>
            </td>
            <td>
                {{$row->published == true ? 'ДА' : 'НЕТ'}}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">Виджетов нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>