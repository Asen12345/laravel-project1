<div class="col-12">
    <div class="box  box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Фильтрация</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="box-body">
                <form class="form-horizontal" method="GET" action="{{route('admin.'.$page.'.sort')}}"
                      enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="subject" class="col-sm-3 col-form-label text-right">Заголовок записи</label>
                        <div class="col-sm-9">
                            <input id="subject" name="subject" type="text" class="filter form-control" value="{{$filter_data['subject'] ?? ''}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label text-right">Email</label>
                        <div class="col-sm-9">
                            <input id="email" name="email" type="text" class="filter form-control" value="{{$filter_data['email'] ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="active" class="col-sm-3 col-form-label text-right">Активность</label>
                        <div class="col-sm-9">
                            <select autocomplete="off" class="filter form-control" id="published" name="active">
                                <option value="" @if(isset($filter_data['active'])){{$filter_data['active'] === null ? 'selected' : ''}}@endif>Все</option>
                                <option value="1" @if(isset($filter_data['active'])){{$filter_data['active'] === '1' ? 'selected' : ''}}@endif>Активные</option>
                                <option value="0" @if(isset($filter_data['active'])){{$filter_data['active'] === '0' ? 'selected' : ''}}@endif>Неактивные</option>
                            </select>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Отфильтровать</button>
                        <a href="{{route('admin.'.$page.'.index', ['id' => request()->id])}}" class="btn btn-default">Сбросить фильтры</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>