<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name')}} | Вход</title>

    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/assets/template/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/assets/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/assets/template/css/AdminLTE.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/template/css/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">


<body class="hold-transition login-page">
<div class="login-logo">
    <b>{{ config('app.name')}}</b> - Панель администратора
</div>
@if ($errors->any())
    @include('admin_panel.admins.partials.errors-success')
@endif
@yield('content')

<script src="{{asset('/js/app.js')}}"></script>

@yield('js_footer')

</body>
</html>
