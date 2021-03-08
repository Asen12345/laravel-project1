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
                @if ($permission->name_id == 'all_news')
                    @continue
                @else
                    <div class="form-check mb-2">
                        <input id="{{$permission->name_id}}" name="permissions[{{$permission->id}}]" type="checkbox" class="form-form-check-input" {{!empty($user) ? $user->permissions->contains('permission_id', $permission->id) ? 'value=1 checked' : 'value=0' : ''}}>
                        <label class="form-check-label ml-2" for="{{$permission->name_id}}">{{$permission->permission_name}}</label>
                    </div>
                @endif
            @empty
                <span>В базе нет прав.</span>
            @endforelse
        </div>
    </div>
    <div class="box-header with-border">
        <h3 class="box-title">Категории новостей</h3>
    </div>
    <div class="box-body">
        <div class="form-group row">
            <div class="form-check mb-2">
                @php($news = $permissions->firstWhere('name_id', 'all_news'))
                <input id="{{ $news->name_id }}" name="permissions[{{ $news->id }}]" type="checkbox" class="form-form-check-input" {{ !empty($user) ? $user->permissions->contains('permission_id', $news->id) ? 'value=1 checked' : 'value=0' : '' }}>
                <label class="form-check-label ml-2" for="{{ $news->name_id }}">{{ $news->permission_name }}</label>
            </div>
            @forelse ($newsCategories as $category)
                <div class="form-check mb-2" id="category_list">
                    <input id="category_{{$category->id}}" name="categories[{{$category->id}}]" type="checkbox" class="form-form-check-input" {{ !empty($user) ? $user->categoryPermission->contains('category_id', $category->id) == true ? 'value=1 checked' : 'value=0' : ''}}>
                    <label class="form-check-label ml-2" for="category_{{$category->id}}">{{$category->name}}</label>
                </div>
            @empty
                <span>В базе нет категорий.</span>
            @endforelse
        </div>
    </div>
</div>