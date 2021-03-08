<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Основные</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        @if(Gate::allows('is-redactor', auth()->user()))
            <div class="form-group row mb-0">
                <label for="email" class="col-md-4 col-form-label text-md-right">Логин (e-mail)*</label>
                <div class="col-md-6">
                    <p id="email" class="form-control">{{$user['email']}}</p>
                </div>
            </div>
        @else
            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">Логин (e-mail)*</label>
                <div class="col-md-6">
                    <input id="email" name="email" type="email" class="form-control" value="{{$user['email'] ?? old('email')}}" required>
                </div>
            </div>
        @endif
        <div class="form-group row name-expert">
            <label for="firstname" class="col-md-4 col-form-label text-md-right">Имя*</label>
            <div class="col-md-6">
                <input id="firstname" name="firstname" type="text" class="form-control" value="{{$social_profile->first_name ?? old('firstname')}}" required>
            </div>
        </div>
        <div class="form-group row name-expert">
            <label for="lastname" class="col-md-4 col-form-label text-md-right">Фамилия*</label>
            <div class="col-md-6">
                <input id="lastname" name="lastname" type="text" class="form-control" value="{{$social_profile->last_name ?? old('lastname')}}" required>
            </div>
        </div>
        {{--Start upload image--}}
        @include('admin_panel.components.picture_upload.picture_upload_modal')

        <div class="form-group row">
            <label for="image" class="col-md-4 col-form-label text-md-right">Пиктограмма</label>
            <div class="col-md-6">
                <img id="user_avatar" class="img-fluid" src="{{$social_profile->image ?? url('/img/no_picture.jpg')}}" alt="Image">
                <input type="button" class="btn btn-default" value="Сменить" onclick="select_avatar()">
                <input type="hidden" name="image" id="picture" value="{{$social_profile->image ?? ''}}">
            </div>
        </div>
        {{--End upload image--}}
        <div class="passwords_div">
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>
                <div class="col-md-6">
                    <input id="password" name="password" type="password" class="form-control" value="" {{$content['name_method'] === 'create' ? 'required' : ''}}>
                </div>
            </div>
            <div class="form-group row">
                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Подтверждение
                    пароля</label>
                <div class="col-md-6">
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" value="" {{$content['name_method'] === 'create' ? 'required' : ''}}>
                </div>
                <div class="col-md-6 offset-4" id="cp"></div>
            </div>
            @if ((request()->route()->getName() == 'admin.users.edit') && ($user->permission == 'company'))
                <div class="form-group row">
                    <label for="send_notification" class="col-md-4 col-checkbox-label text-md-right">Отправить уведомление: </label>
                    <div class="col-md-6">
                        <input id="send_notification" name="send_notification" type="checkbox" class="form-form-check-input" {{ $user->send_notification == true ? 'checked' : '' }}>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>