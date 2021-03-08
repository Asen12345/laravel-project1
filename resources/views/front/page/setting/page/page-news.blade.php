@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="tab-friend companies-list">
        <div class="tab-friend companies-list active">
		    <div class="row h-mt-20">
                <div class="col-sm-12 content-buttons text-left">
                    <a href="{{route('front.page.news.add')}}" class="button">Добавить новость</a>
                </div>
            </div>
            @forelse($news as $row)
                <div class="blog-row">
                    <h2>{{$row->name}}</h2>
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <div class="blogs-previews__views">
                                <div class="ico-eye"></div>
                                <div class="blogs-previews__views-count">{{$row->views_count ?? '0'}} {{$row->published == true ? 'Опубликовано' : 'Не опубликовано'}}</div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="col-12">
                            {{\Carbon\Carbon::parse($row->created_at)->isoFormat("DD MMMM YYYY, H:mm")}}
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <p>Новостей нет.</p>
            @endforelse
        </div>
        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$news->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_footer_account')
@endsection