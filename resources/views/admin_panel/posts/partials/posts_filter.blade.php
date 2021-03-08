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
			<form class="form-horizontal" method="GET" action="{{route('admin.'.$page.'.sort', ['id' => request()->id])}}"
				  enctype="multipart/form-data">
				<div class="form-group row">
					<label for="title" class="col-sm-3 col-form-label text-right">Заголовок записи</label>
					<div class="col-sm-9">
						<input id="title" name="title" type="text" class="filter form-control" value="{{$filter_data['title'] ?? ''}}">
					</div>
				</div>
				<div class="form-group row">
					<label for="subject" class="col-sm-3 col-form-label text-right">Заголовок блога</label>
					<div class="col-sm-9">
						<input id="subject" name="subject" type="text" class="filter form-control" value="{{$filter_data['subject'] ?? ''}}">
					</div>
				</div>

				<div class="form-group row">
					<label for="published" class="col-sm-3 col-form-label text-right">Активность</label>
					<div class="col-sm-9">
						<select autocomplete="off" class="filter form-control" id="published" name="published">
							<option value="" @if(isset($filter_data['published'])){{$filter_data['published'] === null ? 'selected' : ''}}@endif>Все</option>
							<option value="1" @if(isset($filter_data['published'])){{$filter_data['published'] === '1' ? 'selected' : ''}}@endif>Активные</option>
							<option value="0" @if(isset($filter_data['published'])){{$filter_data['published'] === '0' ? 'selected' : ''}}@endif>Неактивные</option>
						</select>
					</div>
				</div>

				<div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right">Отфильтровать</button>
					<a href="{{route('admin.'.$page.'.index', ['id' => request()->id])}}" class="btn btn-default">Сбросить фильтры</a>
				</div>
			</form>
		</div>
		<!-- /.box-body -->
	  </div>
	  <!-- /.box -->
    </div>
</div>