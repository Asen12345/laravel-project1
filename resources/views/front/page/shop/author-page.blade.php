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
                            <h1>Все исследования компании</h1>
                        </div>
                        <div class="blog-post__body">
                            <div class="float-left mr-3">
                                <img class="li-portrait rounded mb-3 mx-auto d-block" src="{{ $author->image ?? url('/img/no_picture.jpg') }}" alt="">
                                <div class="text-center">
                                @if (auth()->check())
                                    @if ($author->subscribe == false)
                                        <a href="javascript:;" data-id="{{$author->id}}" data-type="subscribe" class="button button-micro button-dark-blue subscribe">подписаться</a>
                                    @else
                                        <a href="javascript:;" data-id="{{$author->id}}" data-type="unsubscribe" class="button button-micro button-dark-red subscribe">отписаться</a>
                                    @endif
                                @else
                                    <a href="#li-popup-blog-sbscr_{{$author->id}}" class="button button-micro button-dark-blue to-popup">подписаться</a>
                                    @include('front.partials.subscriber-author-popup', ['row' => $author])
                                @endif
                                </div>
                            </div>
                            {!! $author->text !!}
                        </div>
                    </div>
                    <div class="researhes-list">
                        @forelse($researches as $row)
                            <div class="research-item li-card-block"><a href="{{route('front.page.shop.researches.category.entry', ['id' => $row->id])}}" class="research-item__img"><img src="{{ $row->image ?? '/img/no_picture.jpg' }}" alt="{{ $row->title }}"></a>
                                <div class="research-item__header">
                                    <div class="height-covered">
                                        <h2><a href="{{route('front.page.shop.researches.category.entry', ['id' => $row->id])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}">{{ $row->title }}</a></h2>
                                        <div class="height-cover"></div>
                                    </div>
                                </div>
                                <div class="research-item__body">
                                    <div class="research-item__price li-person-name">
										{{ $row->price ? $row->price . ' руб' : 'бесплатно' }}                                        
                                    </div>
                                    <div class="research-item__bottom li-gray-body">
                                        <div class="company-info__block">
                                            <div class="company-info__bage">Автор</div><a href="{{ route('front.page.shop.author', ['id' => $row->author->id]) }}" class="li-link">{{ $row->author->title }}</a>
                                        </div>
                                        <div class="company-info__block">
                                            <div class="company-info__bage">Дата выхода</div>
                                            <div class="company-info__descr">{{ \Carbon\Carbon::parse($row->published_at)->isoFormat('DD MMMM YYYY') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            У автора нет исследований.
                        @endforelse
                    </div>



                </div>
                <div class="col-12">
                    {{$researches->links('vendor.pagination.custom-front')}}
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