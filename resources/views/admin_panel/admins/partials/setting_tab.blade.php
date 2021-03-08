<div class="col-12">
    <div class="box">
        <div class="box-body">
            <form id="form" role="form" method="POST" action="{{route($content['post_route'], $content['name_method'] === 'edit' ? ['user' => $user->id] : '')}}">

                @if($content['name_method'] === 'edit')
                    {{ method_field('PUT') }}
                @endempty
                {{ csrf_field() }}

                @include('admin_panel.admins.chunk.check_box', ['user' => $user, 'page' => $content['page']])

                @include('admin_panel.admins.chunk.input_box', ['user' => $user, 'page' => $content['page']])

                @include('admin_panel.admins.chunk.select_box', ['user' => $user, 'page' => $content['page']])

                @include('admin_panel.admins.partials.permission_tab', ['permissions' => $permissions])

                <div class="form-group row mb-0">
                    <div class="col-auto mx-auto">
                        <button type="submit" class="btn btn-primary" id="submit_button">
                            Сохранить изменения
                        </button>
                    </div>
                </div>

            </form>


        </div>
    </div>


</div>