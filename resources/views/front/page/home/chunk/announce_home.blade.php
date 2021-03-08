<div class="announcements">
    <div class="title-block title-block--iconed">
        <div class="h1-no-title">Анонсы и мероприятия</div>
        <div class="title-block__icons"><a href="{{route('front.anons.rss')}}" class="title-block__icon title-block__icon--rss"><i class="icon-rss"></i></a></div>
    </div>
    <div class="announcements-items">
        @foreach($announces as $announce)
        <div class="announcements-item">
            <div class="announcements-item__header">
                <div class="announcements-item__date">
                    <div class="ico-calendar sp-icon"></div>
                    <div class="announcements-item_date-val">{{\Carbon\Carbon::parse($announce->date)->isoFormat("DD MMMM YYYY")}}</div>
                </div>
            </div><a href="{{ route('front.page.anons.page', [$announce->id]) }}" class="li-link-blue announcements-item__title">{{$announce->title}}</a>
            <p class="announcements-item__text">Место: {{$announce->place}}</p>
            <p class="announcements-item__text">Организатор: {{$announce->organizer}}</p>
        </div>
        @endforeach
    </div>
    <div class="personal-blogs__bottom more-container text-right"><a href="{{route('front.page.anons')}}" class="li-red-link">Все анонсы</a></div>
</div>