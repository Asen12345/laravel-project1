<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Администрирование</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-check mb-2">
            <input id="active" name="active" type="checkbox" class="form-form-check-input" {{$user['active'] === true ? 'checked' : ''}} value="{{$user['active'] ?? '0'}}">
            <label class="form-check-label ml-2" for="active">Пользователь активен</label>
        </div>
        <div class="form-check mb-2">
            <input id="block" name="block" type="checkbox" class="form-form-check-input" {{$user['block'] === true ? 'checked' : ''}} value="{{$user['block']}}">
            <label class="form-check-label ml-2" for="block">Пользователь заблокирован</label>
        </div>
        <div class="form-check mb-2">
            <input id="notifications_subscribed" name="notifications_subscribed" type="checkbox" class="form-form-check-input" {{$user['notifications_subscribed'] === true ? 'checked' : ''}} value="{{$user['notifications_subscribed']}}" {{ request()->route()->getName() !== 'admin.users.create' ? 'disabled' : '' }}>
            <label class="form-check-label ml-2" for="notifications_subscribed">Новостная рассылка</label>
        </div>

        <div class="form-check mb-2">
            <input id="invitations" name="invitations" type="checkbox" class="form-form-check-input" {{$user['invitations'] === true ? 'checked' : ''}} value="{{$user['invitations']}}" {{ request()->route()->getName() !== 'admin.users.create' ? 'disabled' : '' }}>
            <label class="form-check-label ml-2" for="invitations">Уведомления и приглашения</label>
        </div>
    </div>
</div>