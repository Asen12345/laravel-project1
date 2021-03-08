@extends('admin_panel.layouts.app')

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')
    <div class="col-12">
        <div class="box  box-primary">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th>
                        Метатеги
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td nowrap="" width="1%">
                            <a href="{{route('admin.resources.metatags.edit', ['id' => $template->id])}}"><i class="fa fa-edit" data-toggle="tooltip" title="" data-original-title="Редактировать"></i></a>
                        </td>
                        <td>
                            {{$template->name}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td nowrap="" width="1%">

                        </td>
                        <td>
                            No template
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
            <div class="pull-right">

            </div>
        </div>
    </div>
@endsection