<ol class="breadcrumb">
    @foreach($crumbs as $crumb => $href)
        @if (!empty($href))
            <li><a href="{{$href}}">{{$crumb}}</a></li>
        @else
            <li class="active">{{$crumb}}</li>
        @endif
    @endforeach
</ol>