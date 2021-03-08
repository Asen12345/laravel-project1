@extends('front.page.setting.account.account-main')

@section('content-setting-page')

<div class="tab-friend companies-list">
    <div class="tab-friend companies-list active">
        @forelse($comments as $comment)
            <div class="blog-row">
                <h3><a href="{{route('front.page.post', ['permission' => $comment->post->user->permission, 'blog_id' => $comment->post->blog->id, 'post_id' => $comment->post->id])}}" class="li-link-blue">{{$comment->post->title}}</a></h3>
                <div class="blog-post-discussion__info">
                    <div class="blog-row__date">
                        {{\Carbon\Carbon::parse($user->last_login_at)->isoFormat("DD MMMM YYYY, H:mm")}}
                    </div>
                </div>
                <div class="blog-row__content">
                    <p>{{$comment->post->announce ?? $comment->text}}</p>
                </div>
                <div class="row justify-content-end">
                    <div class="col-auto">
                        <form class='form-inline'
                              action="{{route('front.setting.account.comments.destroy', ['id' => $comment->id])}}"
                              method="POST" id='form_delete_{{$comment->id}}'>
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                        <a href="javascript:"
                           onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#form_delete_{{$comment->id}}').submit();}return false;"
                           class="button button-micro button-red">Удалить</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Сообщений нет.</p>
        @endforelse
    </div>
    <div class="row m-minus">
        <div class="col-12">
            <div class="col-12">
                {{$comments->links('vendor.pagination.custom-front')}}
            </div>
        </div>
    </div>
</div>

@endsection

@section('js_footer_account')
@endsection