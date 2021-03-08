<div class="company-card">
    <div class="li-portrait"><img src="{{$user->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="alt"></div>
    <div class="company-card__content">
        <div class="company-card__top">
            @if (auth()->check())
                @if (($user->id !== auth()->user()->id) && auth()->user()->permission !== 'social')
                    @if ($user->requestFriends->contains('user_id', auth()->user()->id))
                        {{--If has friend record && has request record === friend forever--}}
                        @if ($user->friends->contains('friend_id', auth()->user()->id))
                            <div class="li-to-friends">
                                <div class="sp-icon ico-plus"></div>
                                <span>Пользователь уже в друзьях</span>
                            </div>
                            <a href="#li-message-person-popup" class="button button-micro button-dark-blue to-popup send_message_popup" data-user_to="{{$user->id}}">оставить сообщение</a>
                        @else
                            {{--Has only request--}}
                            <div class="li-to-friends">
                                <div class="sp-icon ico-plus"></div>
                                <span>Запрос уже отправлен</span>
                            </div>
                            <a href="#li-message-person-popup" class="button button-micro button-dark-blue to-popup send_message_popup" data-user_to="{{$user->id}}">отправить сообщение</a>
                        @endif
                    @else
                        <div class="li-to-friends">
                            <div class="sp-icon ico-plus"></div>
                            <a href="javascript:" class="add_friend" data-id="{{$user->id}}">Добавить в друзья</a>
                        </div>
                        <a href="#li-message-person-popup" class="button button-micro button-dark-blue to-popup send_message_popup" data-user_to="{{$user->id}}">отправить сообщение</a>
                    @endif
                @endif
            @else
                <div class="li-to-friends">
                    <div class="sp-icon ico-plus"></div>
                    <a href="javascript:" onclick="alert('Чтобы добавить в друзья - авторизуйтесь')">Добавить в друзья</a>
                </div>
                <a href="javascript:" class="button button-micro button-dark-blue" onclick="alert('Чтобы отправлять сообщения - авторизуйтесь')">отправить сообщение</a>
            @endif
        </div>
        <div class="company-card__bottom">
            <div class="company-card__left">
                <div class="company-card__stat"><span>Дата регистрации</span>
                    <div>{{\Carbon\Carbon::parse($user->created_at)->isoFormat("DD MMMM YYYY") ?? 'нет данных'}}</div>
                </div>
                <div class="company-card__stat"><span>Последнее посещение</span>
                    <div>{{\Carbon\Carbon::parse($user->last_login_at)->isoFormat("DD MMMM YYYY, H:mm") ?? 'нет данных'}}</div>
                </div>
            </div>
            <div class="company-card__right">
                <div class="company-card__stat">
                    <div>{{$blog_post_count ?? ''}}</div><span>Сообщений <span>в блоге</span></span>
                </div>
                <div class="company-card__stat">
                    <div>{{$friends_count ?? ''}}</div><span>Друзей</span>
                </div>
                <div class="company-card__stat">
                    <div>{{$news_count ?? '0'}}</div><span>Новостей</span>
                </div>
            </div>
        </div>
    </div>
</div>