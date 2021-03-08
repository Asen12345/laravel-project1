@extends('front.layouts.app')

@section('header_style')
    <link rel="stylesheet" href="{{asset('/assets/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/select2/css/theme-flat.css')}}">
@endsection

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="title-block">
                        <h1>ЛИЧНЫЙ КАБИНЕТ - {{$user->name}}</h1>
                    </div>
                    <div class="company-activity">
                        <div class="li-tabs tabs-wrapper">
                            @include('front.page.setting.partials.setting-menu', ['active' => $menu_setting_active])
                            @yield('content-setting-page')
                        </div>
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
    @yield('js_footer_account')
@endsection