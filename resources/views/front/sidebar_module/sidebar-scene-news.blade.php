<div class="sidebar-multi-wrap sidebar-multi-wrap--for-buttons">
    <a href="#li-subject-popup" id="subjects-open" class="sidebar-iconed-title sidebar-block to-popup">
        <div class="sidebar-title-icon">
            <div class="sp-icon ico-checklist"></div>
        </div>
        <span>Выбрать новости по сюжету</span>
    </a>
</div>
<div id="li-subject-popup" class="li-popup li-popup-subject mfp-hide">
    <form action="{{route('front.page.news.filter.scene')}}" method="POST">
        @csrf
        <header class="li-popup__header">Выбор новостей по сюжету</header>
        <div class="li-popup__body tabs-wrapper">
                <ul class="subjects-slider tabs__list">
                    @foreach($sceneGroup as $group)
                    <li class="tabs__item {{$loop->iteration == 1 ? 'tabs__item--active' : ''}} subjects-slide">
                        <a href="#item{{$loop->iteration}}" class="tabs__link">{{$group->name}}</a>
                    </li>
                    @endforeach
                </ul>
                <div id="subject-range" class="subject-range"></div>
                <ul id="subject-content" class="tabs__content subject-content mnu-standart-style-reset">
                    @foreach($sceneGroup as $group)
                        <li id="item{{$loop->iteration}}" class="tabs__item subject-content-item {{$loop->iteration > 1 ? 'hidden' : ''}}">
                            @foreach($group->newsScene as $scene)
                                <label><input type="checkbox" name="scene[{{$scene->id}}]"><span>{{$scene->name}}</span></label>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
        </div>
        <div class="li-popup-subject__footer text-right">
            <a href="javascript:;" onclick="$('input:checkbox').prop('checked', false);">сбросить выбор</a>
            <button type="submit" class="button button-mini button-l-blue">применить</button>
        </div>
    </form>
</div>