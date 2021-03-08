<div class="sidebar-analitcs">
    <div class="sidebar-analitcs__header sidebar-standart-title">Аналитические исследования</div>
    <div class="sidebar-analitcs__body">
        <div class="sidebar-analitcs__slider">
            @forelse($researchesAnalytic as $row)
            <div class="sidebar-analitcs__slide">
                <a href="{{route('front.page.shop.researches.category.entry', ['id' => $row->id])}}" class="li-link sidebar-analitcs__slide-title">{{ $row->title }}</a>
                <div class="sidebar-analitcs__slide-img"><img src="{{ url($row->image ?? '/img/no_picture.jpg') }}" alt="alt"></div>
            </div>
            @empty

            @endforelse
        </div>
        <div class="more-container text-right"><a href="{{ route('front.page.shop') }}" class="li-red-link">Все исследования</a></div>
    </div>
</div>