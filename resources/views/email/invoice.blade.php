@extends('email.layouts.index')

@section('title')
    <title>ЛюдиИпотеки.рф</title>
@endsection

@section('content')

    Здравствуйте.
    <br/>
    Заказ {{ $number }} на сайте ЛюдиИпотеки.рф создан. Статус: {{ $status }}.
    <br/>
    Во вложении счет на оплату.

@endsection