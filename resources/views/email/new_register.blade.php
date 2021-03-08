@extends('email.layouts.index')

@section('title')
<title>ЛюдиИпотеки.рф</title>
@endsection

@section('content')

@include('email.content.'.$template)

@endsection