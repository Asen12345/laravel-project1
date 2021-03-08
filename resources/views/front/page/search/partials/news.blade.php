<div class="row">
    <div class="col-8">
    @if ($row->category->parent_id == 0)
    {{--Url news category without child--}}
    <h2><a href="{{route('front.page.news.category.entry', ['url_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue">{{$row->announce ?? $row->name}}</a></h2>
    @else
        {{--Url news category have child--}}
        <h2><a href="{{route('front.page.news.sub_category.entry', ['url_section' => $row->category->parent->url_en, 'url_sub_section' => $row->category->url_en, 'url_news' => $row->url_en])}}" class="li-link-blue">{{$row->announce ?? $row->name}}</a></h2>
    @endif
    </div>
    <div class="col-4 text-right">
        <div class="blog-row__date">{{\Carbon\Carbon::parse($row->created_at)->format('d.m.Y')}}</div>
    </div>
	<div class="col-12">
    {{ str_replace("&nbsp;", ' ', strip_tags($row->announce))}}
	</div>
</div>
