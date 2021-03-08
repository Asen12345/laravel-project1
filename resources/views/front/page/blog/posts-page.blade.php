@extends('front.layouts.app')

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12 blog-page-container">
                    <div class="blogs-top">
                        <div class="title-block title-block--iconed">
                            <h1>{{$content_page['title']}}</h1>
                        </div>
                        <div class="li-tabs tabs-wrapper">
                            <ul class="tabs__content mnu-standart-style-reset">
                                <li id="item1" class="tabs__item">
                                    <div class="blog-rows">
                                        @foreach($posts as $post)
                                            @include('front.page.blog.chunk.post-row-all-posts', ['post' => $post])
                                        @endforeach
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    {{$posts->links('vendor.pagination.custom-front')}}
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
    <script>
        $(document).ready(function () {
            $('.blog-rating__handler .sp-icon').on('click', function (e) {
                e.preventDefault();
                let cl = $(this).attr('class');
                let type_cl = cl.split(' ')[1];
                let post_id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('front.post.votes') }}",
                    data: {
                        _token:"{{ csrf_token() }}",
                        type: type_cl,
                        post_id: post_id
                    },
                    success: function(data) {
                        if (data['success']) {
                            alert(data['success'])
                        }
                        if (data['error']) {
                            alert(data['error'])
                        }
                        if (data['total']) {
                            $('#rating_'+post_id).text(data['total'])
                        }
                    },
                })
            });
        })
    </script>
@endsection