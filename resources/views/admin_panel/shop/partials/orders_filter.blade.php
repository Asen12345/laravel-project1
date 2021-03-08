<div class="col-12">
    <div class="box box-primary">
	  <div class="box box-default collapsed-box">
		<div class="box-header with-border">
		  <h3 class="box-title">Фильтрация</h3>
		  <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
		  </div>
		</div>
		<div class="box-body">
			<form class="form-horizontal" method="GET" action="{{route('admin.shop.researches.orders.sort')}}"
				  enctype="multipart/form-data">
				<div class="form-group row">
					<label for="user_id" class="col-sm-3 col-form-label text-right">Фамилия/Имя</label>
					<div class="col-sm-9">
						<input id="user_id" name="user_id" type="text" class="filter form-control" value="{{request()->user_id ?? ''}}">
					</div>
				</div>

				<div class="form-group row">
					<label for="status" class="col-sm-3 col-form-label text-right">Статус</label>
					<div class="col-sm-9">
						<select autocomplete="off" class="filter form-control" id="status" name="status">
							<option></option>
							<option value="started" @if(isset(request()->status)){{request()->status == 'started' ? 'selected' : ''}}@endif>Незаконченный</option>
							<option value="waiting" @if(isset(request()->status)){{request()->status == 'waiting' ? 'selected' : ''}}@endif>В ожидании</option>
							<option value="paid" @if(isset(request()->status)){{request()->status == 'paid' ? 'selected' : ''}}@endif>Оплачен</option>
							<option value="send" @if(isset(request()->status)){{request()->status == 'send' ? 'selected' : ''}}@endif>Отправлен</option>
						</select>
					</div>
				</div>

				<div class="form-group row" id="from-to">
					<label for="created_from" class="col-sm-3 col-form-label text-right">Дата создания:</label>
					<div class="col-sm-3">
						<input type="text" name="created_from" value="{{request()->created_from ?? ''}}" class="datep filter form-control">
					</div>
					<div class="col-sm-3">
						<input type="text" name="created_to" value="{{request()->created_to ?? ''}}" class="datep filter form-control">
					</div>
				</div>
				<div class="form-group row" id="from-to">
					<label for="updated_at" class="col-sm-3 col-form-label text-right">Дата обновления:</label>
					<div class="col-sm-3">
						<input type="text" name="updated_from" value="{{request()->updated_from ?? ''}}" class="datep filter form-control">
					</div>
					<div class="col-sm-3">
						<input type="text" name="updated_to" value="{{request()->updated_to ?? ''}}" class="datep filter form-control">
					</div>
				</div>

				<div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right">Отфильтровать</button>
					<a href="{{route('admin.shop.researches.orders')}}" class="btn btn-default">Сбросить фильтры</a>
				</div>
			</form>
		</div>
	  </div>
    </div>
</div>