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
					<label for="published" class="col-sm-3 col-form-label text-right">Активная новость</label>
					<div class="col-sm-9">
						<select autocomplete="off" class="filter form-control" id="published" name="published">
							<option></option>
							<option value="1" @if(isset(request()->published)){{request()->published == 1 ? 'selected' : ''}}@endif>Да</option>
							<option value="0" @if(isset(request()->published)){{request()->published == 0 ? 'selected' : ''}}@endif>Нет</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="news_category_id" class="col-sm-3 col-form-label text-right">Категория</label>
					<div class="col-sm-9">
						<select autocomplete="off" class="filter form-control" id="news_category_id" name="news_category_id">
							<option></option>
							@foreach($categories as $category)
								<option value="{{$category->id}}" @if (!empty(request()->news_category_id))
									{{request()->news_category_id == $category->id ? 'selected' : ''}}@endif>{{$category->name}}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="author_user_id" class="col-sm-3 col-form-label text-right">Разместивший новость</label>
					<div class="col-sm-9">
						<input id="author_user_id" name="author_user_id" type="text" class="filter form-control" value="{{request()->author_user_id ?? ''}}">
					</div>
				</div>

				<div class="form-group row" id="from-to">
					<label for="published_at" class="col-sm-3 col-form-label text-right">Период:</label>
					<div class="col-sm-3">
						<input type="text" name="from" value="{{request()->from ?? ''}}" class="datep filter form-control">
					</div>
					<div class="col-sm-3">
						<input type="text" name="to" value="{{request()->to ?? ''}}" class="datep filter form-control">
					</div>
				</div>

				<div class="form-group row">
					<label for="vip" class="col-sm-3 col-form-label text-right">Важная новость</label>
					<div class="col-sm-9">
						<select autocomplete="off" class="filter form-control" id="vip" name="vip">
							<option></option>
							<option value="1" @if(isset(request()->vip)){{request()->vip == 1 ? 'selected' : ''}}@endif>Да</option>
							<option value="0" @if(isset(request()->vip)){{request()->vip == 0 ? 'selected' : ''}}@endif>Нет</option>
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