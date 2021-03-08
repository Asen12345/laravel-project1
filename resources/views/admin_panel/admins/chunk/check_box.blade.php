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
    </div>
</div>