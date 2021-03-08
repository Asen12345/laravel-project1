@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="tab-friend companies-list active">

        <legend class="title-block text-gray">Тема: {{$message_admin->subject}}</legend>
            <div class="row h-mt-20">
                <div class="col-sm-2">
                    <img class="img-fluid" src="{{'/img/admin.png'}}" alt="Аватар">
                </div>
                <div class="col-sm-9">
                    <strong>Администрация</strong>
                    <fieldset class="message">
                        <legend>
                            <span></span>
                            <span class="float-right">{{\Carbon\Carbon::parse($user->last_login_at)->isoFormat("DD MMMM YYYY, H:mm")}}</span>
                        </legend>
                        <p>{!! $message_admin->text !!}</p>
                        <form action="{{ route('front.setting.account.message.admin.download' , ['id' => $message_admin->id]) }}" method="POST">
                            @csrf
                            @if (!empty($message_admin->file))
                                <button class="btn button button-dark-blue">Скачать файл</button>
                            @endif
                        </form>
                    </fieldset>
                </div>
            </div>

    </div>

@endsection

@section('js_footer_account')
@endsection