@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="tab-friend companies-list">
        <div class="topic-cards">
            <h2>Настройка рассылки</h2>
            <div class="form-group row">
                <label for="notifications_subscribed" class="col-sm-4 col-form-label text-right">Новостная рассылка сайта на e-mail</label>
                <div class="col-sm-8">
                    <select autocomplete="off" name="notifications_subscribed" class="li-form-select" id="ui-id-2" style="display: none;">
                        <option value="1" {{$user->notifications_subscribed == true ? 'selected' : ''}}>Да, я хочу получать новости ипотечного рынка</option>
                        <option value="0" {{$user->notifications_subscribed == false ? 'selected' : ''}}>Нет, я не хочу получать новости ипотечного рынка</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="invitations" class="col-sm-4 col-form-label text-right">Уведомления</label>
                <div class="col-sm-8">
                    <select autocomplete="off" name="invitations" class="li-form-select" id="ui-id-3" style="display: none;">
                        <option value="1" {{$user->invitations == true ? 'selected' : ''}}>Да, я хочу получать уведомления о новых сообщениях и приглашениях</option>
                        <option value="0" {{$user->invitations == false ? 'selected' : ''}}>Нет, я не хочу получать уведомления о новых сообщениях и приглашениях</option>
                    </select>
                </div>
            </div>
            <div class="form-group row li-form delete-row">
                @if ($blogs->isNotEmpty())
                    <label for="company_type" class="col-sm-4 col-form-label text-right">Подписки на Блоги</label>
                    <div class="col-sm-8">
                        @foreach ($blogs as $blog)
                            <div class="form-group row subscribe-row" id="post_{{$blog->id}}">
                                <div class="col-9">
                                    <input disabled id="first_name" type="text" class="li-form-input" value="{{$blog->subject}}">
                                </div>
                                <div>
                                    <a href="javascript:;" data-id="{{$blog->id}}" class="button button-micro button-dark-blue unsubscribe">Отписаться</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('js_footer_account')
    <script>
        $(document).ready(function () {
            $('.unsubscribe').on('click', function () {
                let val = $(this).data('id');
                ajaxUpdate('unsubscribe', val)
            });
            $('select').on('selectmenuchange', function (e) {
                let type = $(this).attr('name');
                let val = $(this).val();
                ajaxUpdate(type, val)
            });
            function ajaxUpdate(type = null, val = null) {
                $.ajax({
                    type: "POST",
                    url: '{{route('front.setting.account.subscriptions.update')}}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        val: val,
                        type: type,
                    },
                    success: function(data) {
                        if (type === 'unsubscribe') {
                            if (data.success) {
                                $('#post_' + data.id).remove();
                                alert('Вы успешно отписались.');
                                function isEmpty( el ){
                                    return !$.trim(el.html())
                                }
                                if (isEmpty($('.subscribe-row'))) {
                                    $('.delete-row').remove();
                                }
                            } else {
                                alert('Не удалось отписаться.')
                            }
                        }
                        if (type === 'notifications_subscribed' || type === 'invitations'){
                            if (data.success) {
                                alert('Настройки сохранены.')
                            } else {
                                alert('Не удалось сохранить настройки.')
                            }
                        }

                    }
                });
            }
        })
    </script>
@endsection