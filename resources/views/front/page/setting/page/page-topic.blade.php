@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="tab-friend companies-list">
        <div class="topic-cards">
            @forelse($topics as $row)
                <div class="blog-row">
                    <h3>
                        <a href="{{route('front.page.topic.page', ['url_en' => $row->url_en])}}">{{$row->title}}</a>
                    </h3>
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            {{\Carbon\Carbon::parse($row->published_at)->isoFormat("DD MMMM YYYY")}}
                        </div>
                    </div>
                </div>
            @empty
                <p>Нет доступных тем.</p>
            @endforelse
        </div>
        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$topics->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_footer_account')
@endsection