@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/theme-flat.css')}}" rel="stylesheet">
@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{$content['title']}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="POST" action="{{route('admin.banner.update', ['id' => $banner->id])}}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label text-right">Заголовок</label>
                        <div class="col-sm-7">
                            <input id="name" name="name" type="text" class="form-control" value="{{$banner->name}}" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link" class="col-sm-3 col-form-label text-right">Ссылка</label>
                        <div class="col-sm-7">
                            <input id="link" name="link" type="text" class="form-control" value="{{$banner->link}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="published" class="form-check-label col-sm-3 text-right">Опубликовано</label>
                        <div class="col-sm-7">
                            <input id="published" name="published" type="checkbox" {{$banner->published == true ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date_from" class="col-sm-3 col-form-label text-right">Дата начала</label>
                        <div class="col-sm-2">
                            <input id="date_from" name="date_from" type="text" class="form-control datep" value="{{$banner->date_from != null ? \Carbon\Carbon::parse($banner->date_from)->format('d-m-Y') : ''}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="date_to" class="col-sm-3 col-form-label text-right">Дата окончания</label>
                        <div class="col-sm-2">
                            <input id="date_to" name="date_to" type="text" class="form-control datep" value="{{$banner->date_to != null ? \Carbon\Carbon::parse($banner->date_to)->format('d-m-Y') : '' }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="banner_place_id" class="col-sm-3 col-form-label text-right">Расположение</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" class="filter form-control js-example-basic-single" id="banner_place_id" name="banner_place_id">
                                @foreach($bannerPlace as $place)
                                    <option value="{{$place->id}}" {{$banner->banner_place_id == $place->id ? 'selected' : ''}}>{{$place->place}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="input_row" {{$banner->blog_announce_id == true ? 'style="display: none"' : ''}}>
                        <label for="input_group" class="col-sm-3 col-form-label text-right">Место размещения</label>
                        <div class="col-sm-7">
                            <select autocomplete="off" {{in_array($banner->banner_place_id, [12, 4, 17]) ? '' : 'disabled'}} class="filter form-control js-example-basic-single" id="input_group" name="input_group" >
                                <option value="{{$banner->blog_announce_id}}">{{$blogAnnounceIdValue}}</option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-3 offset-3">
                            <img class="img-fluid" src="{{url('/storage/' . $banner->image)}}" alt="">
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <label for="image" class="col-sm-3 col-form-label text-right">Файл (jpg, gif, png, swf) </label>
                        <div class="col-sm-2">
                            <input type="file" name="image" value="{{old('file')}}">
                        </div>
                    </div>
                    <div class="box-footer text-center">
                        <button type="submit" class="btn btn-primary center-block">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/jquery-ui/datepicker-ru.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single#company_type').select2({
                tags: true,
            });
            //Выбор даты
            $('.datep').datepicker($.extend({
                    //showMonthAfterYear: false,
                    dateFormat:'d MM, y'
                },
                $.datepicker.regional['ru']
            ));
            let banner_place_id = $('#banner_place_id');
            banner_place_id.change(function () {
                if (findValueInArray($(this).val())) {
                    let subject = $(".select2-search__field").val();
                    ajaxGetNameAndId($(this).val(), subject);
                }
            });
            let id = banner_place_id.val();
            let subject = $(".select2-search__field").val();
            let input_row = $('#input_row');
            let input_group = $('#input_group').select2();
            ajaxGetNameAndId(id, subject);
            function findValueInArray(value){
                let arr = ['12', '4', '17'];
                for(let i=0; i < arr.length; i++){
                    let name = arr[i];
                    if(name === value){
                        input_row.css('display', 'flex');
                        input_group.prop("disabled", false)
                            .select2().val('').trigger('change');
                        return true;
                    }
                }
                input_row.css('display', 'none');
                input_group.prop("disabled", true)
                    .select2().val('').trigger('change');
                return false;
            }
            $("form").validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    if (element.attr('name') === 'text') {
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));  // custom placement example
                    } else {
                        if (element.attr('name') === 'tags[]') {
                            error.insertAfter(element.parent().children('.select2'))
                        } else {
                            error.insertAfter(element);
                        }
                    }
                }
            });
            function ajaxGetNameAndId(id, subject) {
                input_group.select2({
                    //tags: true,
                    //maximumSelectionLength : 4,
                    ajax: {
                        type: "POST",
                        url: "{{route('admin.banner.ajax.upload')}}",
                        dataType: 'json',
                        data: function () {
                            return {
                                _token:"{{ csrf_token() }}",
                                id: id,
                                subject: $(".select2-search__field").val()
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.subject,
                                        id: item.id,
                                    }
                                })
                            };
                        }
                    },
                    language: {
                        noResults: function () {
                            return 'Ничего не найдено';
                        },
                        searching: function () {
                            return 'Поиск…';
                        },
                    }
                }).trigger('change');

            }
        })
    </script>
@endsection