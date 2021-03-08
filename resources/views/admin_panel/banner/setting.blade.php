@extends('admin_panel.layouts.app')

@section('section_header')

@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')
    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Место размещения</th>
                        <th>Кол-во блоков</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($places as $row)
                        <tr>
                            <td>
                                {{ $row->place }}
                            </td>
                            <td>
                                <select data-id="{{ $row->id }}" class="form-control change_selector" name="view_count" autocomplete="off">
                                    <option value="1" {{ $row->view_count === 1 ? 'selected' : ''}}>1</option>
                                    <option value="2" {{ $row->view_count === 2 ? 'selected' : ''}}>2</option>
                                    <option value="3" {{ $row->view_count === 3 ? 'selected' : ''}}>3</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Баннеров нет.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        $(document).ready(function () {
            $('.change_selector').change(function () {
                let id = $(this).data('id');
                let value = $(this).val();
                edit(id, value);
            });

            function edit(id, value) {
                $.ajax({
                    type: "POST",
                    url: '{{route('admin.banner.ajax.setting')}}',
                    data: {
                        _token:"{{ csrf_token() }}",
                        id: id,
                        value: value,
                    },
                    success: function(data) {
                        if (data['success'] === 'false'){
                            alert(data['error']);
                        } else {
                            alert(data['mess']);
                        }
                    },
                })
            }
        })
    </script>
@endsection