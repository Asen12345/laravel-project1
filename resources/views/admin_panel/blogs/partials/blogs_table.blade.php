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
    @forelse($blogs as $row)
        <tr>
            <td nowrap width='3%'>
                <form class='form-inline' action="{{route('admin.blogs.delete', [$row->id])}}" method="POST" id='formDelete{{$row['id']}}'>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить блог?\nС ним будут удалены все его посты!')) return false; {$('#formDelete{{$row['id']}}').submit();}return false;" data-toggle="tooltip" title="Удаление блога"></i>
                </a>
            </td>
            <td>
                <i data-id="{{$row->id}}" class='fa fa-edit fa_subject_edit' style="color:#72afd2"></i>
                <span data-id="{{$row->id}}" class="subject_edit" id="subject_{{$row->id}}">{{$row['subject']}}</span>
            </td>
            <td>
                {{$row->user->permission == 'company' ? 'Компания' : 'Эксперт'}}
            </td>
            <td>
                <select autocomplete="off" data-id="{{$row->id}}" class="form-control change_selector" name="active">
                    <option value="1" {{$row['active'] == 1 ? 'selected' : ''}}>Да</option>
                    <option value="0" {{$row['active'] == 0 ? 'selected' : ''}}>Нет</option>
                </select>
            </td>
            <td>
                @if ($row->posts_count > 0)
                    <a href="{{route('admin.posts.index', ['id' => $row->id])}}">{{$row->posts_count}}</a>
                @else
                    0
                @endif
            </td>
            <td>
                {{$row->votes_count ?? '0'}}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">Блогов нет.</td>
        </tr>
    @endforelse
    </tbody>
</table>