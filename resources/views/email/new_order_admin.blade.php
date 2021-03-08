@extends('email.layouts.index')

@section('title')
    <title>ЛюдиИпотеки.рф</title>
@endsection

@section('content')

<div class="container">
    <div class="col-12">
        <h2>На сайте новый заказ № {{ $order_id }} - статус: {{ $status }}</h2>
    </div>
    @foreach($products as $product)
        <div class="col-12">
			Состав заказа:<br />
            {{ $product['title'] }}
        </div>
    @endforeach
    <div class="col-12">
        Общая сумма: {{ $total_sum }} руб.
    </div>
</div>

@endsection