@extends('front.layouts.app')

@section('header_style')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/template/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="col-xl-9 col-lg-8 main-content">
    <div class="container-fluid">
        <div class="row m-minus">
            <div class="col-12">
                <div class="title-block">
                    <h1>{{$content_page['title']}}</h1>
                </div>
                <div class="companies-select">
                    <div class="toggle-button text-right relative">
                        <div class="sidebar-informer__title float-left m-2" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Найти</div>
                        <a href="#collapseExample" class="close" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-plus"></i>
                        </a>
                    </div>
                    <div class="collapse" id="collapseExample">
                        @include('front.page.people.expert.chunk.filter-experts')
                    </div>
                </div>
                <div class="companies-list">
                    <div class="companies-list__header">
                        @foreach($content_page['fields'] as $key => $field)
                            <a href="{{$field['sort_url'] . '&sort_order='. $sort_order}}" class="companies-list__sort @if($sort_by == $key){{$sort_order == 'desc' ? '' : 'sort-desc'}}@endif">{{$field['title']}}</a>
                        @endforeach
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <th></th>
                            @foreach($content_page['fields'] as $key => $field)
                                <th>
                                    <a href="{{$field['sort_url'] . '&sort_order='. $sort_order}}" class="companies-list__sort @if($sort_by == $key){{$sort_order == 'desc' ? '' : 'sort-desc'}}@endif">{{$field['title']}}</a>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($experts as $expert)
                            <tr class="companies-list__desktop-info">
                                <td class="companies-list__avatar">
                                    <a href="#" class="companies-list__img">
                                        <img src="{{$expert->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="alt">
                                    </a>
                                </td>
                                <td class="companies-list__title">
                                    <h2><a href="{{route('front.page.people.user', ['id' => $expert->id])}}" class="li-link li-link-blue">{{$expert->name}}</a></h2>
                                </td>
                                <td class="companies-list__news">
                                    @if ($expert->news->count() > 0)
                                        <a href="{{route('front.page.news.user.all', ['author_user_id' => $expert->id])}}" class="li-link">{{$expert->news->count() ?? '0'}}</a>
                                    @else
                                        <span>{{$expert->news->count()}}</span>
                                    @endif
                                </td>

                                <td class="companies-list__comments">
                                    @if ($expert->comments_count > 0)
                                        <a href="{{route('front.page.people.user', ['id' => $expert->id])}}" class="li-link">{{$expert->comments_count ?? '0'}}</a>
                                    @else
                                        <span>{{$expert->comments_count ?? '0'}}</span>
                                    @endif
                                </td>

                                <td class="companies-list__blogs">
                                    @if (!empty($expert->blog))
                                        <a href="{{route('front.page.blog', ['permission' => 'expert', 'blog_id' => $expert->blog->id])}}" class="li-link">{{$expert->posts_count ?? '0'}}</a>
                                    @else
                                        <span>0</span>
                                    @endif
                                </td>

                                <td class="companies-list__date">
                                    <span>{{$expert->last_login_at ? \Carbon\Carbon::parse($expert->last_login_at)->isoFormat("DD MMMM YYYY, H:mm") : 'нет данных'}}</span>
                                </td>
                            </tr>
                        @empty
                            <tr class="companies-list__desktop-info">
                                <td colspan="6">Экспертов нет</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$experts->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
    @include('front.sidebar_module.shop-category-menu-block')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection
@section('js_footer')
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#city').select2({
                //minimumInputLength: 2,
                ajax: {
                    type: "POST",
                    url: "{{ route('front.register.autocomplete') }}",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token:"{{ csrf_token() }}",
                            city: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.title,
                                    id: item.id,
                                    //contryflage:item.state.coutry.sortname
                                }
                            })
                        };
                    }
                },
                language: {
                    noResults: function () {
                        return 'Ничего не найдено';
                    },
                    searching: function () {
                        return 'Поиск…';
                    },
                }
            });
            $('input[name="company_id"]').autocomplete()
                .keyup(function () {
                let word = $(this).val();
                $('input[name="company_id"]').autocomplete({

                    source: function( request, response ) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('front.register.company.autocomplete') }}",
                            data: {
                                _token:"{{ csrf_token() }}",
                                name: word,
                            },
                            success: function(data) {
                                response( $.map( data, function( item ) {

                                    return {
                                        //label_en: item.name,
                                        value: item.name
                                    }
                                }));

                            },
                        })
                    },
                    focus: function( event, ui ) {
                        $('input[name="company_id"]').val( ui.item.value );
                        return false;
                    },
                    select: function( event, ui ) {
                        $('input[name="company_id"]').val( ui.item.value);
                        return false;
                    }
                });

            });

            $('b[role="presentation"]').hide();
            $('.select2-selection__arrow').append('<span class="ui-selectmenu-icon ui-icon ico-chev-selects ui-icon-triangle-1-s" style="background-position: -165px -11px !important;"></span>');
            $('#collapseExample').on('show.bs.collapse', function () {
                let elem = $('.fa-plus');
                elem.attr('class', 'fa fa-minus');
            }).on('hide.bs.collapse', function () {
                let elem = $('.fa-minus');
                elem.attr('class', 'fa fa-plus');
            })
        })
    </script>
@endsection