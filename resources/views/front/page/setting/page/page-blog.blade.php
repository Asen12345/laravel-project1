@extends('front.page.setting.account.account-main')

@section('content-setting-page')
        <h2>{{$blog->subject ?? ''}}</h2>
    <hr/>
    <div class="tab-friend companies-list">
        @if (empty($blog))
            <div class="row h-mt-20">
                <div class="col-sm-12 content-buttons">
                    <a href="{{route('front.setting.account.blog.create')}}" class="button">Создать блог</a>
                </div>
            </div>
        @else
            <div class="tab-friend companies-list active">
		        <div class="row h-mt-20">
                    <div class="col-sm-12 content-buttons text-left">
                        <a href="{{route('front.setting.account.post.create')}}" class="button">Добавить запись</a>
                    </div>
                </div>
                @forelse($posts as $post)
                    <div class="blog-row">
                        <h3>{{$post->title}}</h3>{{ \Carbon\Carbon::parse($post->created_at)->isoFormat("DD MMMM YYYY, H:mm") }}
                        <div class="blog-row__content row justify-content-between">
                            <div class="col-12">
                                <p>{!! preg_replace("/\r\n|\r|\n/",'<br/>',$post->announce) !!}</p>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-4">
                                <div class="blogs-previews__views">
                                    <div class="ico-eye"></div>
                                    <div class="blogs-previews__views-count">{{$post->views_count}}</div>
                                </div>
                                @if ($post->published == true)
                                    <span>Опубликовано</span>
                                @else
                                    <span>Не опубликовано</span>
                                @endif
                            </div>
                            <div class="col-auto">
                                <form class='form-inline' action="{{route('front.setting.account.post.destroy', ['post_id' => $post->id])}}"
                                      method="POST" id='form_delete_{{$post->id}}'>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                                <a href="javascript:"
                                   onClick="if (!confirm('Вы уверены что хотите удалить запись?')) return false; {$('#form_delete_{{$post->id}}').submit();}return false;"
                                   class="button button-micro button-red">Удалить</a>
                                <a href="{{route('front.setting.account.post.edit', ['post_id' => $post->id])}}" class="button button-micro button-dark-blue">Редактировать</a>
                            </div>
                        </div>

                    </div>
                @empty
                    <p>В блоге нет записей.</p>
                @endforelse
            </div>
            <div class="row m-minus">
                <div class="col-12">
                    <div class="col-12">
                        {{$posts->links('vendor.pagination.custom-front')}}
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@section('js_footer_account')
@endsection