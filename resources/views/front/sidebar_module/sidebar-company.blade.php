<div class="sidebar-multi-wrap sidebar-multi-wrap--for-buttons">
    <div class="sidebar-company sidebar-block">
        <a href="#" class="sidebar-iconed-title sidebar-iconed-title--companies">
        <div class="sidebar-title-icon">
            <div class="sp-icon ico-case-wt"></div>
        </div>
            <span>деятельность компаний</span>
        <div class="sp-icon chev-top"></div>
        </a>
        <div class="sidebar-company__content hidden">
            <ul class="mnu-standart-style-reset">
                @foreach ($companyTypes as $type)
                    <li><a href="{{route('front.page.people.filter.company', ['id' => $type->id])}}">{{$type->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>