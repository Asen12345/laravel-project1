@extends('admin_panel.layouts.app')

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')
    <div class="col-12">
        @include('admin_panel.newsletter.unsubscribed.partials.filter')
        <div class="box  box-primary">
            <div class="box-body">
                <div>
                    <h4>Всего отписано ({{ $count }})</h4>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>
                            e-mail
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td nowrap width='3%'>
                                <form class='form-inline' action="{{route('admin.newsletter.unsubscribed.destroy', ['id' => $user->id])}}" method="POST" id='unsubscribe_{{$user->id}}'>
                                    @csrf
                                </form>
                                <a href="javascript:"><i class='fa fa-trash' onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#unsubscribe_{{$user->id}}').submit();}return false;" data-toggle="tooltip" title="Удаление записи"></i>
                                </a>
                            </td>
                            <td>
                               {{$user->email}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                           <td colspan="2" class="text-center">
                               Отписанных пользователей нет.
                           </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right">
            {{ $users->onEachSide(2)->links() }}
        </div>
    </div>
@endsection