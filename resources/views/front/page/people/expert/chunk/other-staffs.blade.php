<div class="company-other-staffs">
    <div class="title-upcase-bl">Другие сотрудники {{$user->company->name}}</div>
	  <div class="row">
		@foreach ($other_staffs as $staff)
		 <div class="blogs-previews__item col-sm-6 col-md-4 col-xl-3">
		  <a href="{{route('front.page.people.user', ['id' => $staff->id])}}" class="li-portrait"><img src="{{$staff->socialProfile->image ?? '/img/no_picture.jpg'}}" alt="{{$staff->name}}"></a>
		  <a href="{{route('front.page.people.user', ['id' => $staff->id])}}" class="li-person-name">{{$staff->name}}</a>
		 </div>
		@endforeach
	  </div>	
</div>