@extends('email.layouts.index')

@section('title')
    <title>Незавершенный заказ на сайте ЛюдиИпотеки.рф</title>
@endsection

@section('content')

	<div class="container">
        <div class="col-12">
            <p>Здравствуйте.</p>
			<p>На сайте ЛюдиИпотеки.рф есть незавершенный заказ № <span class="mr-2">{{ $number }}</span>.</p>
			<a href="{{$link}}">Перейти в Мои покупки.</a>
        </div>
    </div>

@endsection