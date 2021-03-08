<div class="content-similar">
    <div class="block-title title-upcase-bl">
        <div>Похожие новости</div>
    </div>
    <div class="row">
        @forelse($news->similarNews(3, \Carbon\Carbon::parse($news->created_at)->format('m'), $news->id) as $row)
            <div class="col-4">
                <span class="blog-row__date">{{\Carbon\Carbon::parse($row->created_at)->format('d.m.Y')}}</span>
                <div class="similar__title">
                    <a href="{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en , 'url_news' => $row->url_en])}}" class="similar__title-link">{{$row->announce ?? $row->name}}</a>
                </div>
            </div>
        @empty
            <div class="col-12">
                Похожих новостей нет.
            </div>
        @endforelse

    </div>
</div>