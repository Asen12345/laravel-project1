@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="content-buttons">
        <a href="javascript:" id="button_friend" class="button" data-tab="friend">Друзья ({{$allFriends ?? '0'}})</a>
        <a href="javascript:" id="button_request" class="button button-dark-blue" data-tab="request">Запросы в Друзья  ({{$allRequest ?? '0'}})</a>
        <a href="{{route('front.page.people.experts.index')}}" class="button button-dark-blue">Найти друзей</a>
    </div>
    <div class="tab-friend companies-list active">
        <table>
            <tbody>
            @forelse($friends as $friend)
                <tr class="companies-list__desktop-info">
                    <td class="companies-list__avatar">
                        <a href="{{route('front.page.people.user', ['id' => $friend->id])}}" class="companies-list__img">
                            <img src="{{$friend->socialProfile->image  ?? '/img/no_picture.jpg'}}" alt="Аватар">
                        </a>
                    </td>
                    <td class="companies-list__title">
                        <h2><a href="{{route('front.page.people.user', ['id' => $friend->id])}}" class="li-link li-link-blue">{{$friend->name}}</a></h2>
                    </td>
                    <td class="companies-list">{{$friend->socialProfile->company_post}}</td>
                    <td class="companies-list">@if($friend->permission == 'expert'){{$friend->company->name ?? ''}}  @endif</td>
                    <td>
                        <form method="post" action="{{route('front.page.people.friend.delete', ['user_id' => $friend->id])}}">
                            @csrf
                            <button type="submit" class="button button-micro button-dark-blue">Удалить из друзей</button>
                        </form>
                        <a href="#li-message-person-popup" data-user_to="{{$friend->id}}" class="button button-micro button-red to-popup send_message_popup">оставить сообщение</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Друзей нет.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$friends->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>
    <div class="tab-request companies-list" style="display: none">
        <table>
            <tbody>
            @forelse($friends_request as $friend_req)
                <tr class="companies-list__desktop-info">
                    <td class="companies-list__avatar">
                        <a href="{{route('front.page.people.user', ['id' => $friend_req->id])}}" class="companies-list__img">
                            <img src="{{$friend_req->socialProfile->image  ?? '/img/no_picture.jpg'}}" alt="Аватар">
                        </a>
                    </td>
                    <td class="companies-list">
                        <h2><a href="" class="li-link li-link-blue">{{$friend_req->name}}</a></h2>
                    </td>
                    <td class="companies-list">{{$friend_req->socialProfile->company_post}}</td>
                    <td class="companies-list">@if($friend_req->permission == 'expert'){{$friend_req->company->name ?? ''}}@endif</td>
                    <td class="companies-list">
                        <form method="post" action="{{route('front.page.people.friend.accept', ['user_id' => $friend_req->id])}}">
                            @csrf
                            <button type="submit" class="button button-dark-blue">Принять</button>
                        </form>
                        <form method="post" action="{{route('front.page.people.friend.cancel', ['user_id' => $friend_req->id])}}">
                            @csrf
                            <button type="submit" class="button">Отклонить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Запросов нет.</td>
                </tr>
            @endforelse

            </tbody>
        </table>
        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$friends_request->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>
    @include('front.partials.message-popup')
@endsection

@section('js_footer_account')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.content-buttons').on('click', function (event) {
                let index = $(event.target).data('tab');
                $('div.active').css('display', 'none').removeClass('active');
                $('.content-buttons a:not(.button-dark-blue)').addClass('button-dark-blue');
                $('#button_' + index).removeClass('button-dark-blue');
                $('.tab-' + index).addClass('active').css('display', 'block')
            });
            $('.send_message_popup').on('click', function () {
                let user_to = $(this).data('user_to');
                $("input[name='user_to']").val(user_to)
            });
            $('#message-popup').validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
            });
        });
    </script>
@endsection