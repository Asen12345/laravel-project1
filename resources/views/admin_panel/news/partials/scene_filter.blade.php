<div class="col-12">
    <div class="box box-primary">
	  <div class="box box-default collapsed-box">
		<div class="box-header with-border">
		  <h3 class="box-title">Фильтрация</h3>
		  <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
		  </div>
		  <!-- /.box-tools -->
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<form class="form-horizontal" method="GET" action="{{route('admin.'.$page.'.sort')}}"
				  enctype="multipart/form-data">
				<div class="form-group row">
					<label for="name" class="col-sm-3 col-form-label text-right">Заголовок</label>
					<div class="col-sm-9">
						<input id="name" name="name" type="text" class="filter form-control" value="{{request()->name ?? ''}}">
					</div>
				</div>
				<div class="form-group row">
					<label for="news_scene_group_id" class="col-sm-3 col-form-label text-right">Сюжетная группа</label>
					<div class="col-sm-9">
						<select autocomplete="off" class="filter form-control" id="news_scene_group_id" name="news_scene_group_id">
							<option></option>
							@foreach($scene_groups as $group)
								<option value="{{$group->id}}" @if (!empty(request()->news_scene_group_id))
								{{request()->news_scene_group_id == $group->id ? 'selected' : ''}}@endif>{{$group->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right">Отфильтровать</button>
					<a href="{{route('admin.'.$page.'.index')}}" class="btn btn-default">Сбросить фильтры</a>
				</div>
			</form>
		</div>
		<!-- /.box-body -->
	  </div>
	  <!-- /.box -->
    </div>
</div>