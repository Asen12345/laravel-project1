<div class="box box-primary" id="permission_div" @if (!empty($user)) {{$user['role'] == 'redactor' ? 'style="display: none"' : 'style="display: block"'}} @else style="display: none" @endif>
    <div class="box-header with-border">
        <h3 class="box-title">Права пользователя</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group row">
            @forelse ($permissions as $permission)
                <div class="form-check mb-2">
                    <input id="{{$permission->name_id}}" name="permissions[{{$permission->id}}]" type="checkbox" class="form-form-check-input" {{$permission->name_id == 'users' ? 'value=1 checked' : 'value=0'}}>
                    <label class="form-check-label ml-2" for="{{$permission->name_id}}">{{$permission->permission_name}}</label>
                </div>
            @empty
                <span>В базе нет прав.</span>
            @endforelse
        </div>
    </div>
</div>