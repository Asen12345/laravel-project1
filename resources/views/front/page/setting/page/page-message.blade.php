@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="tab-friend companies-list active">
        <legend>Тема: {{$thread->subject}}</legend>
        @forelse($messages as $message)
            <div class="row h-mt-20">
                @if ($message->userSend->id === auth()->user()->id)
                    <div class="col-sm-1"></div>
                @endif
                @if ($message->userSend->id !== auth()->user()->id)
                    <div class="col-sm-2">
                        <img class="img-fluid" src="{{$message->userSend->socialProfile->image  ?? '/img/no_picture.jpg'}}" alt="Аватар">
                    </div>
                @endif
                <div class="col-sm-9">
                    <strong class="{{$message->userSend->id === auth()->user()->id ? 'right-legend': ''}}"><a href="{{route('front.page.people.user', ['id' => $message->userSend->id])}}">{{$message->userSend->name}}</a></strong>
                    <fieldset class="message">
                        <legend>
                            <span></span>
                            <span class="float-right">{{\Carbon\Carbon::parse($message->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}</span>
                        </legend>
                        <p>{{$message->body}}</p>
                    </fieldset>
                </div>
                @if ($message->userSend->id === auth()->user()->id)
                    <div class="col-sm-2">
                        <img class="img-fluid" src="{{$message->userSend->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="Аватар">
                        <form class='form-inline' action="{{route('front.setting.account.message.delete', ['id' => $message->id, 'thread' => $thread->id])}}" method="POST" id='form_{{$message->id}}'>
                            @csrf
                            <a class="button-red message-button" href="javascript:;" onclick="$('#form_{{ $message->id }}').submit()">Удалить</a>
                        </form>
                    </div>
                @endif

            </div>
        @empty

        @endforelse
        <hr/>
        <div class="row h-mt-20 justify-content-center">
            <div class="col-sm-9">
                <form action="{{route('front.setting.account.message.page.send', ['id' => $message->thread->id])}}" class="li-form li-form--colored" method="post">
                    @csrf
                    <label for="body"><strong><a href="{{route('front.page.people.user', ['id' => $user->id])}}">{{$user->name}}</a></strong></label>
                    <textarea id="body" name="body" class="form-control" rows="4" cols="50" required="">{{old('text')}}</textarea>
                    <button class="button button-dark-blue message-button h-mt-20">Отправить</button>
                </form>
            </div>
            {{--<div class="col-sm-2">
                <img class="img-fluid" src="{{$user->socialProfile->image}}" alt="Аватар">
            </div>--}}
        </div>

    </div>

@endsection

@section('js_footer_account')
@endsection