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
    @forelse($buyers as $row)
        <tr>
            <td>
                {{ $row->name }}
            </td>
            <td>
                <a href="{{route('admin.shop.researches.orders.sort', ['user_id' => $row->name])}}">
                    {{ $row->cart_count }}
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Новостей нет</td>
        </tr>
    @endforelse
    </tbody>
</table>