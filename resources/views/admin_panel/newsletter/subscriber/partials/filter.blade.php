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
		<form class="form-horizontal" method="GET" action="{{route('admin.newsletter.subscribers.sort')}}" enctype="multipart/form-data">
			<div class="form-group row">
				<label for="name" class="col-sm-2 col-form-label text-right">Email пользователя</label>
				<div class="col-sm-4">
					<input id="email" name="email" type="text" class="filter form-control" value="{{ $filter ?? '' }}">
				</div>
				<button type="submit" class="btn btn-primary pull-right">Поиск</button>
			</div>
			<div class="box-footer">
				<a href="{{route('admin.newsletter.subscribers.index')}}" class="btn btn-default">Сбросить фильтры</a>
			</div>
		</form>
	</div>
	<!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>