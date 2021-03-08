@extends('admin_panel.layouts.auth')

@section('content')
    <div class="login-box">
        <div class="login-box-body">
            <p class="login-box-msg">Войдите чтобы начать работу</p>
            <form action="{{ route('admin.login.submit') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback {{$errors->has('email') ? 'has-error':''}}">
                    <input type="email" class="form-control" placeholder="Email" name='email' value='{{old('email')}}' required>
                </div>

                <div class="form-group has-feedback {{$errors->has('password') ? 'has-error':''}}">
                    <input type="password" class="form-control" placeholder="Password"  name='password' required>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js_footer')

@stop
