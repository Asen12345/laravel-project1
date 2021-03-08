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
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') ?? $user['email']}}" required>
                </div>
            </div>
        @endif
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">Имя*</label>
            <div class="col-md-6">
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name') ?? $user['name']}}" required>
            </div>
        </div>
        <div class="passwords_div">
        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>
            <div class="col-md-6">
                <input id="password" name="password" type="password" class="form-control" value="">
            </div>
        </div>
        <div class="form-group row">
            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Подтверждение
                пароля</label>
            <div class="col-md-6">
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" value="">
            </div>
            <div class="col-md-6 offset-4" id="cp"></div>
        </div>
        </div>
    </div>
</div>