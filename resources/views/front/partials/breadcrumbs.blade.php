<div class="col-lg-8 col-xl-9 order-md-2">
    <ul class="breadcrumbs">
        @foreach($crumbs as $crumb => $href)
            @if (!empty($href))
                <li><a href="{{$href}}">{{$crumb}}</a></li>
            @else
                <li class="active">{{$crumb}}</li>
            @endif
        @endforeach
    </ul>
</div>