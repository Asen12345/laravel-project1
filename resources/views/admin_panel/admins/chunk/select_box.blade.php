<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Доступ</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group row">
            <label for="role" class="col-sm-2 control-label">Уровень доступа</label>
            <div class="col-sm-10">
                <select autocomplete="off" class="form-control" id="permission" name="role" required="">
                    <option value="admin" {{ $user['role'] == 'admin' || old('role') == 'admin' ? 'selected' : ''}}>Админ</option>
                    <option value="redactor" {{ $user['role'] == 'redactor' || old('role')== 'redactor' ? 'selected' : ''}}>Редактор</option>
                </select>
            </div>
        </div>
    </div>
</div>