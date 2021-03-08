@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="content-buttons">
        <a href="{{route('front.setting.account', ['type' => 'message'])}}" class="button">От пользователей {{--({{$messagesNotRead ?? '0'}})--}}</a>
        <a href="{{route('front.setting.account.massage.administration')}}" class="button">От администрации {{--({{$adminNotRead ?? '0'}})--}}</a>
        <a href="{{route('front.setting.account.alert')}}" class="button button-dark-blue">Оповещения {{--({{$alertNotRead}})--}}</a>
    </div>
    <div class="tab-friend companies-list active">
        @forelse($mailingAdmin as $row)
            <div class="row h-mt-20">
                <div class="col-sm-2">
                    <img class="img-fluid" src="{{'/img/admin.png'}}" alt="Аватар">
                    <div class="text-center"><strong>Админ</strong></div>
                    <a class="button-red message-button" href="{{route('front.setting.account.thread.admin.destroy', ['subject' => $row->mail->id])}}">Удалить ветку</a>
                </div>
                <div class="col-sm-10">
                    <div class="text-center p-2">
                        Переписка с <strong>Администрация</strong>
                    </div>
                    <fieldset class="message">
                        <legend>
                            <span>Тема: {{$row->mail->subject}}</span>
                            <span class="float-right">{{\Carbon\Carbon::parse($row->mail->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</span>
                        </legend>
                        <p>{!! $row->mail->text !!}</p>
                        @if ($row['read'] === false)
                            <div class="float-right"><i class="fas fa-bell"></i></div>
                        @endif
                        @if (!empty($row->mail->file_patch))
                            <form action="{{ route('front.setting.account.message.admin.download', ['id' => $row->mail->id]) }}" method="post" class="li-form">
                            @csrf
                                <button type="submit" class="button button-micro button-dark-blue">Скачать файл</button>
                            </form>
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
                    {{$mailingAdmin->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_footer_account')
@endsection