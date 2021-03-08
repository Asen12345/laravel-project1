@extends('email.layouts.index')

@section('title')
    <title>ЛюдиИпотеки.рф</title>
@endsection

@section('content')

    Изменен статус ващего заказа.
    <br/>
    Новый статус: {{ $status }}
    <br>
    Исследование во вложении.

@endsection