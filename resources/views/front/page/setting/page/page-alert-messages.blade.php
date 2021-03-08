@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="content-buttons">
        <a href="{{route('front.setting.account', ['type' => 'message'])}}" class="button">От пользователей {{--({{$messagesNotRead ?? '0'}})--}}</a>
        <a href="{{route('front.setting.account.massage.administration')}}" class="button">От администрации {{--({{$adminNotRead ?? '0'}})--}}</a>
        <a href="{{route('front.setting.account.alert')}}" class="button button-dark-blue">Оповещения {{--({{$alertNotRead}})--}}</a>
    </div>
    <div class="tab-friend companies-list active">
        @forelse($alertMessaged as $subject)
            <div class="row h-mt-20">
                <div class="col-sm-2">
                    <img class="img-fluid" src="{{'/img/admin.png'}}" alt="Аватар">
                    <div class="text-center"><strong>Админ</strong></div>
                    <form class='form-inline w-100' action="{{route('front.setting.account.alert.destroy', ['id' => $subject->id])}}" method="POST">
                        @csrf
                        <button type="submit" class="button button-red message-button" href="">Удалить</button>
                    </form>
                </div>
                <div class="col-sm-10">
                    <div class="text-center p-2">
                        Уведомления : <strong>Администрация</strong>
                    </div>
                    <fieldset class="message">
                        <legend>
                            @if ($subject->type == 'new_message')
                                <span>Тема: Новое сообщение</span>
                            @endif
                            @if ($subject->type == 'new_friend')
                                <span>Тема: новая заявка в друзья</span>
                            @endif
                            @if ($subject->type == 'new_post')
                                <span>Тема: новый пост</span>
                            @endif
                            @if ($subject->type == 'new_comments')
                                <span>Тема: новый комментарий</span>
                            @endif
                            @if ($subject->type == 'new_topic_subscriber')
                                <span>Тема: вы авторизованы для ответа в теме</span>
                            @endif
                            <span class="float-right">{{\Carbon\Carbon::parse($subject->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</span>
                        </legend>
                        <p>{!! $subject->text !!}</p>
                        @if ($subject['read'] === false)
                            <div class="float-right"><i class="fas fa-bell"></i></div>
                        @endif
                    </fieldset>
                </div>
            </div>
            <hr/>
        @empty
        @endforelse

        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$alertMessaged->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_footer_account')
@endsection