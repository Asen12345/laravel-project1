@extends('front.layouts.app')

@section('header_style')

@endsection

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="news-content">
                        <div class="title-block title-block--iconed">
                            <h1>Авторы исследований</h1>
                        </div>
                        <div class="news-rows">
                            @forelse($authors as $row)
                                <div class="row active">
                                    <div class="col-12">
                                        <h2><a href="{{ route('front.page.shop.author', ['id' => $row->id]) }}">{{$row->title ?? $row->name}}</a></h2>
                                    </div>
                                    <div class="col-2">
                                        <img class="img-fluid rounded" src="{{ $row->image ?? '/img/no_picture.jpg' }}" alt="alt">
                                    </div>

                                    <div class="col-10">
                                        {{ $row->brief_text }}

                                        <div class="bottom-right visible-inline row">
                                            <div class="col-auto">
                                                @if (auth()->check())
                                                    @if ($row->subscribe == false)
                                                        <a href="javascript:;" data-id="{{$row->id}}" data-type="subscribe" class="button button-micro button-dark-blue subscribe">подписаться</a>
                                                    @else
                                                        <a href="javascript:;" data-id="{{$row->id}}" data-type="unsubscribe" class="button button-micro button-dark-red subscribe">отписаться</a>
                                                    @endif
                                                @else
                                                    <a href="#li-popup-blog-sbscr_{{$row->id}}" class="button button-micro button-dark-blue to-popup">подписаться</a>
                                                    @include('front.partials.subscriber-author-popup')
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                            @empty
                                <div class="news-row active col-12">
                                    <h2>Новостей нет.</h2>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    {{$authors->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js_footer')
    <script>
        $(document).ready(function () {
            $('.subscribe').on('click', function () {
                let val = $(this).data('id');
                let type = $(this).data('type');
                ajaxUpdate(type, val)
            });
            function ajaxUpdate(type = null, val = null) {
                $.ajax({
                    type: "POST",
                    url: '{{route('front.page.shop.researches.author.subscribe')}}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        val: val,
                        type: type,
                    },
                    success: function(data) {
                        if (type === 'unsubscribe') {
                            if (data.success) {
                                let elem = $(document).find("a[data-id='" + data.id + "'].subscribe");
                                elem.text('подписаться')
                                    .data('type', 'subscribe')
                                    .toggleClass('button-dark-red button-dark-blue');
                                alert('Вы успешно отписались.')
                            } else {
                                alert('Не удалось отписаться.')
                            }
                        }
                        if (type === 'subscribe') {
                            if (data.success) {
                                let elem = $(document).find("a[data-id='" + data.id + "'].subscribe");
                                elem.text('отписаться')
                                    .data('type', 'unsubscribe')
                                    .toggleClass('button-dark-blue button-dark-red');
                                alert('Вы успешно подписались.')
                            } else {
                                alert('Не удалось подписаться.')
                            }
                        }

                        if (type === 'notifications_subscribed' || type === 'invitations'){
                            if (data.success) {
                                alert('Настройки сохранены.')
                            } else {
                                alert('Не удалось сохранить настройки.')
                            }
                        }

                    }
                });
            }
        })
    </script>
@endsection


@section('sidebar-right')
    @include('front.sidebar_module.shop-category-menu-block')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection