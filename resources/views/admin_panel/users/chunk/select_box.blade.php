<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Роль</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group row">
            <label for="permission" class="col-sm-2 control-label">Роль пользователя</label>
            <div class="col-sm-10">
                <select autocomplete="off" class="form-control" id="permission" name="permission" required="">
                    <option value="expert" {{$user['permission'] == 'expert' || old('permission') == 'expert' ? 'selected' : ''}}>Эксперт</option>
                    <option value="company" {{$user['permission'] == 'company' || old('permission') == 'company' ? 'selected' : ''}}>Компания</option>
                </select>
            </div>
        </div>
    </div>
</div>