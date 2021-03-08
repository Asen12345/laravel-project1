@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="content-buttons">
        <a href="{{route('front.setting.account', ['type' => 'message'])}}" class="button">От пользователей {{--({{$messagesNotRead ?? '0'}})--}}</a>
        <a href="{{route('front.setting.account.massage.administration')}}" class="button">От администрации {{--({{$adminNotRead ?? '0'}})--}}</a>
        <a href="{{route('front.setting.account.alert')}}" class="button button-dark-blue">Оповещения {{--({{$alertNotRead}})--}}</a>
    </div>
    <div class="tab-friend companies-list active">
        @forelse($messagesTreads as $treads)
            @php($last_message = $treads->getLastMessage())
            <div class="row h-mt-20">
                <div class="col-sm-2">
                    <img class="img-fluid w-100" src="{{$treads->companion->socialProfile->image  ?? '/img/no_picture.jpg'}}" alt="Аватар">
                    <div class="text-center"><strong><a href="{{route('front.page.people.user', ['id' => $treads->companion->id])}}">{{ $treads->companion->name }}</a></strong></div>
                    <a class="button-red message-button" href="{{route('front.setting.account.thread.destroy', ['id' => $treads->id])}}">Удалить ветку</a>
                </div>
                <div class="col-sm-10">
                    <div class="text-center p-2">
                        Переписка с <strong>{{ $treads->companion->name }}</strong>
                    </div>
                    <fieldset class="message">
                        <legend>
                            <span>Тема: {{$treads->subject}}</span>
                            <span class="float-right">{{\Carbon\Carbon::parse($last_message->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</span>
                        </legend>
                        <p>{{$last_message->body}}</p>
                        @if ($last_message->read_at === null && $last_message->user_to_id == auth()->user()->id)
                            <div class="float-right"><i class="fas fa-bell"></i></div>
                        @endif
                    </fieldset>

                </div>
				<div class="col-12">
                <a class="button-dark-blue message-button" href="{{route('front.setting.account.message.page', ['id' => $treads->id])}}">Читать ветку</a>
				</div>
            </div>
            <hr/>
        @empty

        @endforelse
        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$messagesTreads->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_footer_account')
@endsection