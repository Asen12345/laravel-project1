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
    @forelse($orders as $row)
        <tr>
            <td>
                <a href="{{route('admin.shop.researches.orders.order', [$row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать заказ">
                    № И-{{$row['id']}}
                </a>
            </td>
            <td>
                {{ $row->user->name ?? 'Нет' }}
            </td>
            <td>
                {{ $row->total_count ?? '' }} руб
            </td>
            <td>
                {{ \Carbon\Carbon::parse($row['created_at'])->isoFormat('DD MMMM YYYY') }}
            </td>
            <td>
                {{ \Carbon\Carbon::parse($row['updated_at'])->isoFormat('DD MMMM YYYY') }}
            </td>
            <td>
                <select data-id="{{$row->id}}" class="form-control change" name="active" autocomplete="off">
                    <option value="waiting" {{ $row->status == 'waiting' ? 'selected' : ''}}>Ожидание</option>
                    <option value="started" {{ $row->status == 'started' || $row->status == 'cancelled' ? 'selected' : ''}}>Незаконченный</option>
                    <option value="paid" {{ $row->status == 'paid' ? 'selected' : ''}}>Оплачен</option>
                    <option value="send" {{ $row->status == 'send' ? 'selected' : ''}}>Отправлен</option>
                </select>
            </td>

        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Заказов нет</td>
        </tr>
    @endforelse
    </tbody>
</table>