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
            <label for="active" class="col-sm-3 col-form-label text-right">Активность</label>
            <div class="col-sm-9">
                <select autocomplete="off" class="filter form-control" id="active" name="active">
                    <option value="" @if(isset(request()->active)){{request()->active === null ? 'selected' : ''}}@endif>Все</option>
                    <option value="1" @if(isset(request()->active)){{request()->active === '1' ? 'selected' : ''}}@endif>Активные</option>
                    <option value="0" @if(isset(request()->active)){{request()->active === '0' ? 'selected' : ''}}@endif>Неактивные</option>
                </select>
            </div>
        </div>
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
        <div class="box-footer">

            <button type="submit" class="btn btn-primary pull-right">Отфильтровать</button>

            <a href="{{route('admin.'.$page.'.index')}}" class="btn btn-default">Сбросить фильтры</a>

        </div>
    </form>
</div>