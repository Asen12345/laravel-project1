@extends('front.layouts.app')


@section('header_style')
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
@endsection


@section('content')
<div class="col-xl-9 col-lg-8 main-content">
<div class="container-fluid">
  <div class="row m-minus">
    <div class="col-12">
      <div class="title-block title-block--iconed">
        <h1>Регистрация</h1>
      </div>
        @if (empty(session()->get('type')))
            {{session()->flash('type', 'expert')}}
        @endif
      {!! $content_page['content_form']['before'] !!}
        <div class="content-form">
            <div class="content-buttons">
                <a href="javascript:" id="button_expert" class="button" data-tab="expert">регистрация эксперта</a>
                <a href="javascript:" id="button_company" class="button button-dark-blue" data-tab="company">регистрация компании</a>
            </div>

            <div class="tab-expert {{ session()->get('type') == 'expert' ? 'active' : ''}}" style="display: {{ session()->get('type') == 'company' ? 'none' : ''}}">
                @include('front.page.register.partials.tab-expert')
            </div>

            <div class="tab-company {{ session()->get('type') == 'expert' ? 'active' : ''}}" style="display: {{ session()->get('type') == 'expert' ? 'none' : ''}}">
                @include('front.page.register.partials.tab-company')
            </div>
        </div>
        {!! $content_page['content_form']['after'] !!}
    </div>
  </div>
</div>
</div>
@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
@endsection

@section('js_footer')
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/js/jquery.mask.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            $.validator.addMethod("customemail",
                function(value, element) {
                    return /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/.test(value);
                },
                "Некорректный email адрес."
            );
            $("#registrationFormExpert").validate({
                lang: 'ru',
                rules: {
                    email: {
                        required: true,
                        customemail: true,
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.is(":checkbox")) {
                        let name = element.attr('name');
                        error.insertAfter($('#error-' + name));  // custom placement example
                    } else {
                        if (element.attr('name') === 'city_id') {
                            error.insertAfter(element.parent().children('.select2'))
                        } else if (element.attr('name') === 'company_type') {
                            let e = element.next('.select2');
                            error.insertAfter(e)
                        } else {
                            error.insertAfter(element);
                        }
                    }
                }
            });
            $("#registrationFormCompany").validate({
                lang: 'ru',
                rules: {
                    email: {
                        required: true,
                        customemail: true,
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.is(":checkbox")) {
                        let name = element.attr('name');
                        error.insertAfter($('#company-error-' + name));  // custom placement example
                    }  else {
                        if (element.attr('name') === 'city_id') {
                            error.insertAfter(element.parent().children('.select2'))
                        } else if (element.attr('name') === 'company_type') {
                            let e = element.next('.select2');
                            error.insertAfter(e)
                        } else {
                            error.insertAfter(element);
                        }
                    }
                }
            });
            $(".content-buttons").on('click', function (event) {
                let index = $(event.target).data('tab');
                $("div.active").css('display', 'none').removeClass('active');
                $(".tab-" + index).addClass('active').css('display', 'block');
            });
            $('.select2').select2({
                tags: false,
                width: '100%',
            });
            $('.js-example-basic-single[name="company_type"]').select2({
                width: '100%',
                minimumResultsForSearch: -1
            });
            $('.js-example-basic-single[name="city_id"]').select2({
                width: '100%',
                tags: false,
                //maximumSelectionLength : 4,
                ajax: {
                    type: "POST",
                    url: "{{ route('front.register.autocomplete') }}",
                    dataType: 'json',
                    data: function (params) {
                        let form = $(this).closest('form');
                        let country = $('select[name="country"]' , form).val();
                        if (!country){
                            alert('Выберите страну.')
                        }
                        return {
                            _token:"{{ csrf_token() }}",
                            city: params.term,
                            country: country,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.title + ", " + item.title_en,
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


            /*Company*/
            $('input.company').keyup(function () {
                let word = $(this).val();
                let lang = $(this).attr('name');
                if (word.length >= 3) {
                    $('input.company').autocomplete({
                        source: function( request, response ) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('front.register.company.autocomplete') }}",
                                data: {
                                    _token:"{{ csrf_token() }}",
                                    name: word,
                                    lang: lang,
                                },
                                success: function(data) {
                                    response( $.map( data, function( item ) {
                                        if (lang === 'company_rus') {
                                            return {
                                                label: item.name,
                                                label_en: item.name_en,
                                                value: item.id,
                                                type_id: item.type_id
                                            }
                                        } else {
                                            return {
                                                label_en: item.name,
                                                label: item.name_en,
                                                value: item.id,
                                                type_id: item.type_id
                                            }
                                        }
                                    }));

                                },
                            })
                        },
                        focus: function( event, ui ) {
                            if (lang === 'company_rus') {
                                $('input[name="company_rus"]').val( ui.item.label );
                                $('input[name="company_en"]').val( ui.item.label_en );
                                return false;
                            } else {
                                $('input[name="company_rus"]').val( ui.item.label_en );
                                $('input[name="company_en"]').val( ui.item.label );
                                return false;
                            }

                        },
                        select: function( event, ui ) {
                            if (lang === 'company_rus') {
                                $('input[name="company_rus"]').val( ui.item.label );
                            } else {
                                $('input[name="company_en"]').val( ui.item.label );
                            }
                            $('input[name="company_id"]').val( ui.item.value);
                            $('select[name="company_type"]').each(function(k, item) {
                                $(item).val(ui.item.type_id).trigger('change');
                            });
                            return false;
                        }
                    });
                }
            });

            $('select[name=country]').on('change', function () {
               $('select[name=city_id]').val('');
                $('.js-example-basic-single[name="city_id"]').trigger('change');
            });
            let check_private = $(document).find('#private');
            checkPrivate(check_private);
            $('#private').change(function() {
                checkPrivate($(this))
            });
            function checkPrivate (elem) {
                if (elem.is(":checked") === true) {
                    $('#company_hide').hide();
                    $('[name=company_rus]').prop('required', false);
                    $('[name=company_type]').prop('required', false);
                    $('[name=company_post]').prop('required', false);
                } else {
                    $('#company_hide').show();
                    $('[name=company_rus]').prop('required', true);
                    $('[name=company_type]').prop('required', true);
                    $('[name=company_post]').prop('required', true);
                }
            }
        });

    </script>

@endsection