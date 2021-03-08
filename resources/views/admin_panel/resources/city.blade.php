@extends('admin_panel.layouts.app')

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
                        @foreach($content['fields'] as $key => $field)
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
                    @foreach($cities as $city)
                        <tr>
                            <td>
                                {{$city['title']}} ({{$city['title_en']}})
                            </td>
                            <td>
                                {{$city->region_count}}
                            </td>
                            <td>
                                {{$city->country_count}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pull-right">

            </div>
        </div>
        <div class="pull-right">
            {{ $cities->appends(['sort_by' => request()->sort_by, 'sort_order' => request()->sort_order])->onEachSide(2)->links() }}
        </div>
    </div>
@endsection