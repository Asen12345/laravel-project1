<div class="box-body">
    <form class="form-horizontal" method="GET" action="{{route('admin.'.$page.'.sort')}}"
          enctype="multipart/form-data">
        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}

        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label text-right">{{$page === 'admins' ? 'Имя/Email пользователя' : 'ФИО/Название компании'}}</label>
            <div class="col-sm-9">
                <input id="name" name="name" type="text" class="filter form-control" value="{{request()->name ?? ''}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="active" class="col-sm-3 col-form-label text-right">Активность пользователя</label>
            <div class="col-sm-9">
                <select autocomplete="off" class="filter form-control" id="active" name="active">
                    <option value="" @if(isset(request()->active)){{request()->active === null ? 'selected' : ''}}@endif>Все</option>
                    <option value="1" @if(isset(request()->active)){{request()->active === '1' ? 'selected' : ''}}@endif>Активные</option>
                    <option value="0" @if(isset(request()->active)){{request()->active === '0' ? 'selected' : ''}}@endif>Неактивные</option>
                </select>
            </div>
        </div>

        @if ($page === 'users')
            <div class="form-group row">
                <label for="block" class="col-sm-3 col-form-label text-right">Блокировка пользователя</label>
                <div class="col-sm-9">
                    <select autocomplete="off" class="filter form-control" id="block" name="block">
                        <option value="" @if(isset(request()->block)){{request()->block === null ? 'selected' : ''}}@endif>Все</option>
                        <option value="1" @if(isset(request()->block)){{request()->block === '1' ? 'selected' : ''}}@endif>Заблокированные</option>
                        <option value="0" @if(isset(request()->block)){{request()->block === '0' ? 'selected' : ''}}@endif>Не заблокированные</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="notifications_subscribed" class="col-sm-3 col-form-label text-right">Новостная рассылка</label>
                <div class="col-sm-9">
                    <select autocomplete="off" class="filter form-control" id="notifications_subscribed" name="notifications_subscribed">
                        <option value="" @if(isset(request()->notifications_subscribed)){{request()->notifications_subscribed === null ? 'selected' : ''}}@endif>Все</option>
                        <option value="1" @if(isset(request()->notifications_subscribed)){{request()->notifications_subscribed === '1' ? 'selected' : ''}}@endif>Подписан</option>
                        <option value="0" @if(isset(request()->notifications_subscribed)){{request()->notifications_subscribed === '0' ? 'selected' : ''}}@endif>Не подписан</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="notifications_subscribed" class="col-sm-3 col-form-label text-right">Уведомления и приглашения</label>
                <div class="col-sm-9">
                    <select autocomplete="off" class="filter form-control" id="invitations" name="invitations">
                        <option value="" @if(isset(request()->invitations)){{request()->invitations === null ? 'selected' : ''}}@endif>Все</option>
                        <option value="1" @if(isset(request()->invitations)){{request()->invitations === '1' ? 'selected' : ''}}@endif>Подписан</option>
                        <option value="0" @if(isset(request()->invitations)){{request()->invitations === '0' ? 'selected' : ''}}@endif>Не подписан</option>
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <label for="permission" class="col-sm-3 col-form-label text-right">Роль пользователя</label>
                <div class="col-sm-9">
                    <select autocomplete="off" class="filter form-control" id="permission" name="permission">
                        <option value="" @if(isset(request()->permission)){{request()->permission === null ? 'selected' : ''}}@endif>Все</option>
                        <option value="expert" @if(isset(request()->permission)){{request()->permission === 'expert' ? 'selected' : ''}}@endif>Эксперт</option>
                        <option value="company" @if(isset(request()->permission)){{request()->permission === 'company' ? 'selected' : ''}}@endif>Компания</option>
                    </select>
                </div>
            </div>
        @endif
        @if ($page === 'admins')
            <div class="form-group row">
                <label for="role" class="col-sm-3 col-form-label text-right">Роль пользователя</label>
                <div class="col-sm-9">
                    <select autocomplete="off" class="filter form-control" id="role" name="role">
                        <option value="" @if(isset(request()->role)){{request()->role === null ? 'selected' : ''}}@endif>Все</option>
                        <option value="redactor" @if(isset(request()->role)){{request()->role === 'redactor' ? 'selected' : ''}}@endif>Редактор</option>
                        <option value="admin" @if(isset(request()->role)){{request()->role === 'admin' ? 'selected' : ''}}@endif>Админ</option>
                    </select>
                </div>
            </div>
        @endif

        <div class="box-footer">

            <button type="submit" class="btn btn-primary pull-right">Отфильтровать</button>

            <a href="{{route('admin.'.$page.'.index')}}" class="btn btn-default">Сбросить фильтры</a>

        </div>
    </form>
</div>