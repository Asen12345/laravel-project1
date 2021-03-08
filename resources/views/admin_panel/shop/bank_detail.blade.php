@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/summernote/summernote-bs4.css')?? '' }}" rel="stylesheet">
@endsection

@section('section_title')
    {{$content['title']?? '' }}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')
    <div class="col-12">
        <div class="box  box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{$content['title']?? '' }}</h3>
            </div>
            <div class="box-body">
                <div class="box-body">
                    <form class="form-horizontal" method="POST" action="{{route('admin.shop.researches.settings.bank.detail.update')?? '' }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-form-label col-sm-3 text-right">Название</label>
                            <div class="col-sm-6">
                                <input id="name" name="name" type="text"  class="form-control" value="{{ $bank->name ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-form-label col-sm-3 text-right">Адрес</label>
                            <div class="col-sm-6">
                                <input id="address" name="address" type="text"  class="form-control" value="{{ $bank->address ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inn" class="col-form-label col-sm-3 text-right">ИНН</label>
                            <div class="col-sm-6">
                                <input id="inn" name="inn" type="text"  class="form-control" value="{{ $bank->inn ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="kpp" class="col-form-label col-sm-3 text-right">КПП</label>
                            <div class="col-sm-6">
                                <input id="kpp" name="kpp" type="text"  class="form-control" value="{{ $bank->kpp ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="recipient" class="col-form-label col-sm-3 text-right">Получатель</label>
                            <div class="col-sm-6">
                                <input id="recipient" name="recipient" type="text"  class="form-control" value="{{ $bank->recipient ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="beneficiary_bank" class="col-form-label col-sm-3 text-right">Банк получателя</label>
                            <div class="col-sm-6">
                                <input id="beneficiary_bank" name="beneficiary_bank" type="text"  class="form-control" value="{{ $bank->beneficiary_bank ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="r_account" class="col-form-label col-sm-3 text-right">Р/сч</label>
                            <div class="col-sm-6">
                                <input id="r_account" name="r_account" type="text"  class="form-control" value="{{ $bank->r_account ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bic" class="col-form-label col-sm-3 text-right">БИК</label>
                            <div class="col-sm-6">
                                <input id="bic" name="bic" type="text"  class="form-control" value="{{ $bank->bic ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="k_account" class="col-form-label col-sm-3 text-right">К/сч</label>
                            <div class="col-sm-6">
                                <input id="k_account" name="k_account" type="text"  class="form-control" value="{{ $bank->k_account ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name_signatory" class="col-form-label col-sm-3 text-right">ФИО подписывающего счет</label>
                            <div class="col-sm-6">
                                <input id="name_signatory" name="name_signatory" type="text"  class="form-control" value="{{ $bank->name_signatory ?? '' }}" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="position_signatory" class="col-form-label col-sm-3 text-right">Должность подписывающего счет</label>
                            <div class="col-sm-6">
                                <input id="position_signatory" name="position_signatory" type="text"  class="form-control" value="{{ $bank->position_signatory ?? '' }}" required >
                            </div>
                        </div>
                    
                        <div class="text-center h-mt-20">
                            <button type="submit" class="btn btn-primary btn-flat">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(".form-horizontal").validate({
                lang: 'ru',
                errorPlacement: function (error, element) {
                    if (element.hasClass("summernote")) {
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));
                    } else if (element.attr('name') === 'users_id[]') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>

@endsection