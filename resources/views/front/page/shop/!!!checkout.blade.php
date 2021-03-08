@extends('front.layouts.app')

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="news-content">
                        <div class="title-block title-block--iconed">
                            <h1>Корзина</h1>
                        </div>
                        <div class="blog-post__body">
                            <div class="companies-list">

                            </div>
                        </div>
                    </div>
                    <div class="research-card__buttons mt-3 float-right">
                        <a href="{{ route('front.shop.researches.shopping.checkout') }}" class="button button-dark-blue">Оформить заказ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar-right')
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
            @if (session()->has('successAddCart'))
            $('#successAddCart').modal('show');
            @endif
        })
    </script>
@endsection