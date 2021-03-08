@extends('email.layouts.index')

@section('title')
    <title>ЛюдиИпотеки.рф</title>
@endsection

@section('content')

    Здравствуйте.
    <br/>
    Статус Вашего заказа {{ $number }} на сайте ЛюдиИпотеки.рф изменен на {{ $status }}.
    <br/>
    Во вложении счет на оплату.

@endsection