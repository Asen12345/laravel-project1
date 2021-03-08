@extends('admin_panel.layouts.app')

@section('content')

    <div class="col-sm-3">
        <a href="{{ route('admin.users.sort', ['active' => 0]) }}">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-person-add"></i></span>
                <div class="info-box-content">
                    <span><small>Неподтвержденных пользователей</small></span>
                    <span class="info-box-number">{{ $usersNotActive ?? '' }}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-3">
        <a href="{{ route('admin.news.sort', ['published' => 0]) }}">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-person-add"></i></span>
                <div class="info-box-content">
                    <span><small>Неподтвержденных новостей</small></span>
                    <span class="info-box-number">{{ $newsNotActive ?? '' }}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-3">
        <a href="{{ route('admin.blogs.sort', ['active' => 0]) }}">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-person-add"></i></span>
                <div class="info-box-content">
                    <span><small>Неподтвержденных блогов</small></span>
                    <span class="info-box-number">{{ $blogsNotActive ?? '' }}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-3">
        <a href="{{ route('admin.answer.sort', ['published' => 0]) }}">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-person-add"></i></span>
                <div class="info-box-content">
                    <span><small>Неподтвержденных ответов экспертов</small></span>
                    <span class="info-box-number">{{ $topicAnswersNotActive ?? '' }}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-3">
        <a href="{{ route('admin.shop.researches.orders.sort', ['status' => 'waiting']) }}">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-person-add"></i></span>
                <div class="info-box-content">
                    <span><small>Новых заказов в магазине</small></span>
                    <span class="info-box-number">{{ $ordersNotActive ?? '' }}</span>
                </div>
            </div>
        </a>
    </div>

@endsection