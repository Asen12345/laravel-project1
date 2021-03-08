@extends('email.layouts.index')

@section('title')
    <title>ЛюдиИпотеки.рф</title>
@endsection

@section('content')

    <div class="container">
        <div class="row">{{ $template->subject }}</div>
        <div class="row">
            <div class="col-6">
                Фамилия: {{ $feedback->last_name }}
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                Имя: {{ $feedback->first_name }}
            </div>
        </div>
        @if (!empty($feedback->company))
            <div class="row">
                <div class="col-6">
                    Компания: {{ $feedback->company }}
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-6">
                E-mail: {{ $feedback->email }}
            </div>
        </div>
        @if (!empty($feedback->phone))
        <div class="row">
            <div class="col-6">
                Телефон: {{ $feedback->phone }}
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-6">
                Сообщение: {{ $feedback->text }}
            </div>
        </div>
    </div>

@endsection