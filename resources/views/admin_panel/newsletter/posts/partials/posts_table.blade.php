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
    @forelse($posts as $row)
        <tr>
            <td>
                {{ $row->user->name }}
            </td>
            <td>
                <a href="{{route('admin.posts.edit', [$row->id])}}" data-toggle="tooltip" data-placement="top" title="Редактировать поста">
                    {{$row->title}}
                </a>
            </td>
            <td>
                {{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}
            </td>
            <td>
                <div class="form-check">
                    <input type="checkbox" data-id="{{$row->id}}" class="form-check-input" name="to_newsletter" {{ $row->to_newsletter == true ? 'checked' : ''}}>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Записей нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>