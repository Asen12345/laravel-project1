<div class="col-12">

    @if (request()->route()->getName() !== 'admin.users.create')
        <ul class="nav nav-tabs">
            <li class="{{request()->route()->getName() == 'admin.users.edit' ? 'active' : ''}}">
                <a href="{{route('admin.users.edit', ['id' => $user->id])}}">Настройки</a>
            </li>
            <li class="{{request()->route()->getName() == 'admin.users.privacy.edit' ? 'active' : ''}}">
                <a href="{{route('admin.users.privacy.edit', ['id' => $user->id])}}">Приватность</a>
            </li>
            @if(!$user->isExpert())
            <li class="{{request()->route()->getName() == 'admin.users.services.edit' ? 'active' : ''}}">
                <a href="{{route('admin.users.services.edit', ['id' => $user->id])}}">Услуги</a>
            </li>
            @endif
        </ul>
    @endif
    <div class="box">
        <div class="box-body">
            <form id="form" role="form" method="POST" action="{{route($content['post_route'], $content['name_method'] == 'edit' ? ['user' => $user->id] : '')}}">

                @if($content['name_method'] === 'edit')
                    {{ method_field('PUT') }}
                @endempty

                {{ csrf_field() }}

                @if (request()->route()->getName() == 'admin.users.privacy.edit')
                    {{--If page is privacy--}}
                    @include('admin_panel.users.chunk.privacy_section', ['user' => $user, 'page' => $content['page'], 'privacy' => $user->privacy])
                @elseif(request()->route()->getName() == 'admin.users.services.edit')
                    {{--If page is services--}}
                    @include('admin_panel.users.chunk.services_section', ['user' => $user, 'page' => $content['page'], 'userServices' => $userServices])
                @elseif(request()->route()->getName() == 'admin.users.edit')
                    {{--If page is edit--}}
                    {{--Administration--}}
                    @include('admin_panel.users.chunk.check_box', ['user' => $user, 'page' => $content['page']])
                    {{--Role--}}
                    @include('admin_panel.users.chunk.select_box', ['user' => $user, 'page' => $content['page']])
                    {{--Main--}}
                    @include('admin_panel.users.chunk.default_section_form', ['user' => $user, 'page' => $content['page']])
                    {{--Work--}}
                    @include('admin_panel.users.chunk.work_section_form', ['user' => $user, 'page' => $content['page']])
                    {{--Contact--}}
                    @include('admin_panel.users.chunk.contact_section_form', ['user' => $user, 'page' => $content['page']])
                    {{--Social Page--}}
                    @include('admin_panel.users.chunk.social_section_form', ['user' => $user, 'page' => $content['page']])
                @else
                    {{--If page is first create--}}
                    {{--Administration--}}
                    @include('admin_panel.users.chunk.check_box', ['user' => $user, 'page' => $content['page']])
                    {{--Role--}}
                    @include('admin_panel.users.chunk.select_box', ['user' => $user, 'page' => $content['page']])
                    {{--Main--}}
                    @include('admin_panel.users.chunk.default_section_form', ['user' => $user, 'page' => $content['page']])
                    {{--Work--}}
                    @include('admin_panel.users.chunk.work_section_form', ['user' => $user, 'page' => $content['page']])
                @endif

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