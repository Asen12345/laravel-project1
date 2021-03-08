<div class="title-block">
    <div class="h1-no-title">ТЕМА ДНЯ - ЭКСПЕРТНОЕ МНЕНИЕ</div>
</div><a href="{{ route('front.page.topic.page', ['url_en' => $dayTopic->url_en]) }}" class="day-theme__text">{{ $dayTopic->title }}</a>
<div class="day-theme__slider">
    @foreach($dayTopicAnswers as $answer)
    <div class="day-theme__slide">
        <div class="li-portrait"><img src="{{$answer->user->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="alt"></div>
        <div class="day-theme__slide-text">
            <div class="li-person-name">{{$answer->user->name}}</div>
            <div class="li-quote height-covered">{!! $answer->text !!}
                <div class="height-cover"></div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="day-theme__bottom text-right"><a href="{{ route('front.page.topic') }}" class="li-red-link">Архив Тем дня</a></div>