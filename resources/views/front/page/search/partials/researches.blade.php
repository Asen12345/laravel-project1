<div class="row">
    <div class="col-8">
    <h2><a href="{{route('front.page.shop.researches.category.entry', ['id' => $row->id])}}" class="li-link-blue {{$row->vip == true ? 'news-row__text' : ''}}" class="li-link-blue">{{$row->title}}</a></h2>
    </div>
    <div class="col-4 text-right">
        <div class="blog-row__date">{{\Carbon\Carbon::parse($row->created_at)->format('d.m.Y')}}</div>
    </div>
</div>
<div class="col-12">
    {{ str_replace("&nbsp;", ' ', strip_tags($row->annotation)) }}
</div>