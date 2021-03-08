@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <form name="form" action="{{route('front.setting.account.update', ['page' => 'main'])}}" method="post" id="registrationFormExpert" class="li-form">
        @csrf
        <div class="blog-post__body">
            <h2>Основные</h2>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label text-right">E-mail <span class="li-form-required">*</span></label>
                <div class="col-sm-8">
                    <input id="email" type="text" class="li-form-input" placeholder="" value="{{$user->email}}" disabled>
                </div>
            </div>
            @if ($user->permission == 'expert')
                <div class="form-group row">
                    <label for="first_name" class="col-sm-3 col-form-label text-right">Имя <span class="li-form-required">*</span></label>
                    <div class="col-sm-8">
                        <input id="first_name" name="first_name" type="text" class="li-form-input" value="{{$user->socialProfile->first_name ?? ''}}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="last_name" class="col-sm-3 col-form-label text-right">Фамилия <span class="li-form-required">*</span></label>
                    <div class="col-sm-8">
                        <input id="last_name" name="last_name" type="text" class="li-form-input" value="{{$user->socialProfile->last_name ?? ''}}" required>
                    </div>
                </div>
            @endif


            {{--Image--}}
            @include('admin_panel.components.picture_upload.picture_upload_modal')
            <div class="form-group row">
                <label for="image" class="col-sm-3 col-form-label text-right">Фото</label>
                <div class="col-sm-8">
                    <img id="user_avatar" class="img-fluid" src="{{$user->socialProfile->image ?? url('/img/no_picture.jpg')}}" alt="Image">
                    <input type="button" class="btn btn-default" value="Сменить" onclick="select_avatar()">
                    <input type="hidden" name="image" id="picture" value="{{$user->socialProfile->image ?? url('/img/no_picture.jpg')}}">
                </div>
            </div>
            {{--End Image--}}

            <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label text-right">Пароль <span class="li-form-required">*</span></label>
                <div class="col-sm-8">
                    <input id="password" name="password" type="password" class="li-form-input" placeholder="" value="">
                </div>
            </div>

            <div class="form-group row">
                <label for="password_confirmation" class="col-sm-3 col-form-label text-right">Повтор пароля <span class="li-form-required">*</span></label>
                <div class="col-sm-8">
                    <input id="password_confirmation" name="password_confirmation" type="password" class="li-form-input" placeholder="" value="">
                    <div id="cp"></div>
                </div>
            </div>
        </div>
        <div class="blog-post__body">
        <h2>Работа</h2>
            @if ($user->permission == 'expert')
                <div class="form-group row">
                    <label for="private" class="col-sm-3 col-form-label text-right">Частное лицо:</label>
                    <div class="col-sm-8">
                        <input id="private" name="private" type="checkbox" {{$user->private == true ? 'checked' : ''}}>
                    </div>
                </div>
            @endif
            <div id="company_hide">
                <div class="form-group row">
                    <label for="company_rus" class="col-sm-3 col-form-label text-right">Компания (русское написание):</label>
                    <div class="col-sm-8">
                            <input id="company_rus" type="text" name="company_rus" class="company li-form-input" value="{{$user->company->name ?? ''}}" {{ $user->permission == 'company' ? 'required' : '' }}>
                            <input type="hidden" name="company_id" value="{{$user->company->id ?? ''}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="company_en" class="col-sm-3 col-form-label text-right">Компания (английское написание):</label>
                    <div class="col-sm-8">
                        <input id="company_en" name="company_en" type="text" class="company li-form-input" value="{{$user->company->name_en ?? ''}}">
                        <label id="companyIsUser" class="error hidden" for="company_en">Существующую компанию нельзя отредактировать, создайте новую или напишите администратору</label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="company_type" class="col-sm-3 col-form-label text-right">Сфера деятельности компании:</label>
                    <div class="col-sm-8">
                        <select name="company_type" class="li-form-select" id="ui-id-2" {{ $user->permission == 'company' ? 'required' : '' }}>
                            <option></option>
                            @forelse($company_type as $type)
                                <option {{($user->company->type_id ?? '') == $type->id ? 'selected' : ''}} value="{{$type->id}}">{{$type->name}}</option>
                            @empty
                                Нет данных
                            @endforelse
                        </select>
                    </div>
                </div>
                @if ($user->permission == 'expert')
                    <div class="form-group row">
                        <label for="company_post" class="col-sm-3 col-form-label text-right">Должность:</label>
                        <div class="col-sm-8">
                            <input id="company_post" name="company_post" type="text" class="li-form-input" value="{{$user->socialProfile->company_post ?? ''}}">
                        </div>
                    </div>
                @endif
            </div>
            <div class="form-group row">
                <label id="country" class="col-sm-3 col-form-label text-right">Страна <span class="li-form-required">*</span></label>
                <div class="col-sm-8">
                    <select name="country" class="li-form-input form-control js-example-basic-single" required>
                        <option></option>
                        @forelse ($countries as $country)
                            <option {{$user->socialProfile->country_id == $country->id ? 'selected' : ''}} value="{{$country->id}}">{{$country->title}}, {{$country->title_en}}</option>
                        @empty
                            <option>Данных нет</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="city_id" class="col-sm-3 col-form-label text-right">Город <span class="li-form-required">*</span></label>
                <div class="col-sm-8">
                    <select name="city_id" class="li-form-input form-control js-example-basic-single" required>
                        <option value="{{$user->socialProfile->city_id ?? ''}}">{{$user->socialProfile->getCity()}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="blog-post__body">
            <h2>Контакты</h2>
            <div class="form-group row">
                <label for="work_phone" class="col-sm-3 col-form-label text-right">{{ $user->permission == 'expert' ? 'Рабочий телефон:' : 'Телефон:' }}</label>
                <div class="col-sm-8">
                    <input id="work_phone" name="contacts[work_phone]" type="text" class="li-form-input" placeholder="+0(000)000-00-00" value="{{$user->socialProfile->work_phone ?? ''}}">
                </div>
            </div>
            @if ($user->permission == 'expert')
            <div class="form-group row">
                <label for="mobile_phone" class="col-sm-3 col-form-label text-right">Мобильный телефон:</label>
                <div class="col-sm-8">
                    <input id="mobile_phone" name="contacts[mobile_phone]" type="text" class="li-form-input" placeholder="+0(000)000-00-00" value="{{$user->socialProfile->mobile_phone ?? ''}}">
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label for="skype" class="col-sm-3 col-form-label text-right">Skype:</label>
                <div class="col-sm-8">
                    <input id="skype" name="contacts[skype]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->skype ?? ''}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="web_site" class="col-sm-3 col-form-label text-right">Сайт:</label>
                <div class="col-sm-8">
                    <input id="web_site" name="contacts[web_site]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->web_site ?? ''}}" autocomplete="off">
                </div>
            </div>
            @if ($user->permission == 'company')
                <div class="form-group row">
                    <label for="address" class="col-sm-3 col-form-label text-right">Адрес:</label>
                    <div class="col-sm-8">
                        <input id="address" name="contacts[address]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->address ?? ''}}">
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label for="work_email" class="col-sm-3 col-form-label text-right">{{ $user->permission == 'company'  ? 'Email корпоративный:' : 'Email рабочий:'}}</label>
                <div class="col-sm-8">
                    <input id="work_email" name="contacts[work_email]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->work_email ?? ''}}" autocomplete="off">
                </div>
            </div>

            @if ($user->permission == 'expert')
            <div class="form-group row">
                <label for="personal_email" class="col-sm-3 col-form-label text-right">E-mail личный:</label>
                <div class="col-sm-8">
                    <input id="personal_email" name="contacts[personal_email]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->personal_email ?? ''}}" autocomplete="off">
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label for="about_me" class="col-sm-3 col-form-label text-right">{{$user['permission'] == 'expert' ? 'Обо мне:' : 'О компании'}}</label>
                <div class="col-sm-8">
                    <textarea id="about_me" name="contacts[about_me]" class="form-control summernote" rows="4" cols="50">{!!$user->socialProfile->about_me!!}</textarea>
                </div>
            </div>
        </div>

        <div class="blog-post__body">
            <h2>Страницы в соцсетях</h2>
            <div class="form-group row">
                <label for="face_book" class="col-sm-3 col-form-label text-right">Facebook:</label>
                <div class="col-sm-8">
                    <input id="face_book" name="contacts[face_book]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->face_book ?? ''}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="linked_in" class="col-sm-3 col-form-label text-right">LinkedIn:</label>
                <div class="col-sm-8">
                    <input id="linked_in" name="contacts[linked_in]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->linked_in ?? ''}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="v_kontakte" class="col-sm-3 col-form-label text-right">Вконтакте:</label>
                <div class="col-sm-8">
                    <input id="v_kontakte" name="contacts[v_kontakte]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->v_kontakte ?? ''}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="odnoklasniki" class="col-sm-3 col-form-label text-right">Одноклассники:</label>
                <div class="col-sm-8">
                    <input id="odnoklasniki" name="contacts[odnoklasniki]" type="text" class="li-form-input" placeholder="" value="{{$user->socialProfile->odnoklasniki ?? ''}}">
                </div>
            </div>
        </div>
        <div class="h-clearfix h-mt-20">
            <button type="submit" class="button button-dark-blue float-right">Отправить</button>
        </div>
    </form>
@endsection

@section('js_footer_account')
    <script src="{{asset('/js/jquery.mask.min.js')}}"></script>
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
	    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    @include('admin_panel.components.picture_upload.picture_upload_js', ['type' => 'front'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
    $(document).ready(function () {
        @if($user->permission == 'expert')
            let check_private = $(document).find('#private');
            checkPrivate(check_private);
            $('#private').change(function() {
                checkPrivate($(this))
            });
            function checkPrivate (elem) {
                if (elem.is(":checked") === true) {
                    $('#company_hide').hide()
                } else {
                    $('#company_hide').show()
                }
            }
        @endif
        let company_id = $('input[name="company_id"]');
        let privateCheckBox = $('#private');
        checkCompanyIsUser(company_id.val());
        checkPrivateRequired(privateCheckBox);

        privateCheckBox.click(function() {
            checkPrivateRequired(this);
            eventValidate()
            //$('#form').validator('validate');
        });

        function checkPrivateRequired (elem) {
            if ($(elem).is(':checked')) {
                $('#company_hide').hide();
                $('#company_rus').prop('required',false);
                $('#ui-id-2').prop('required',false);
                $('#company_post').prop('required',false);
                eventValidate()
            } else {
                $('#company_hide').show();
                $('#company_rus').prop('required',true);
                $('#ui-id-2').prop('required',true);
                $('#company_post').prop('required',true);
                eventValidate()
            }
        }
            $('.summernote').summernote({
                lang:'ru-RU',
				tabsize: 2,
                height: 250,
                toolbar: [
					['edit',['undo','redo']],
                    ['style', ['style']],
					['fontname', ['fontname', 'fontsize']],
                    ['font', ['bold', 'underline', 'italic', 'clear', 'strikethrough', 'superscript', 'subscript']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['hr', 'link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']],
                ],
                callbacks: {
                    onPaste: function (e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    },
                    onImageUpload: function(image) {
                        uploadImage(image[0]);
                    },
                }
        });
        function eventValidate () {
            $("#registrationFormExpert").validate({
                lang: 'ru',
                ignore: 'summernote *',
                errorPlacement: function(error, element) {
                    if (element.hasClass("summernote")){
                        error.insertAfter($('#error-' + element.attr('id')));
                    } else if (element.attr('name') === 'city_id') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else if (element.attr('name') === 'country') {
                        error.insertAfter(element.parent().children('.select2'))
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        }
        function uploadImage(image) {
            let data = new FormData();
            data.append("image", image);
            data.append("_token", '{{ csrf_token() }}');
            $.ajax({
                url: '{!! route('front.resource.upload.custom-image') !!}',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "post",
                success: function(url) {
                    if (!url.location){
                        console.log('Error with upload image')
                    } else {
                        let image = $('<img>').attr('src', url.location);
                        $('.summernote').summernote("insertNode", image[0]);
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        $('#mobile_phone, #work_phone').mask('+0(000)000-00-00');

        let blockQuoteButton = function (context) {
            let ui = $('#about_me').ui;
            let button = ui.button({
                className: 'note-btn-blockquote',
                contents: '<i class="fa fa-quote-right"></i>',
                tooltip: 'Blockquote',
                click: function () {
                    context.invoke('editor.formatBlock', 'Blockquote')
                }
            });
            return button.render();
        };
        $('input[type=password]').on('keydown keyup focusout', function (e) {
            passCheck();
        });
        function passCheck(){
            let password = $('#password');
            let ret_pass = $('#password_confirmation');
            if(password.val() !== ret_pass.val()){
                $("#cp").html('<label class="error">Пароли не совпадают</label>');
                //$('button[type="submit"]').attr("disabled", true);
                return false;
            } else {
                $("#cp").find('label').remove();
                return true;
            }
        }
        $(document).on("submit", "form", function(e){
            let pass = passCheck();
            /* let site = validateCustom($('#web_site'), 'site');
            let email = validateCustom($('#work_email'), 'email');
            if (site === false) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $("#web_site").offset().top - 500
                }, 500);
                $("#web_site").focus();
                return  false;
            }
            if (email === false) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $("#work_email").offset().top - 500
                }, 500);
                $("#work_email").focus();
                return  false;
            } */
            if (pass === false) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $("#cp").offset().top - 500
                }, 500);
                $("#password").focus();
                return  false;
            } else {

            }
        });
        $('#web_site').on('keyup', function(e) {
            validateCustom($(this), 'site')
        });
        $('#work_email, #personal_email').on('keyup', function(e) {
            validateCustom($(this), 'email')
        });
        function validateCustom(val, type){
            let error = '<label class="error_custom">Поле заполнено некорректно.</label>';
            let reg = '';
            if (type === 'email') {
                reg = /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/;
            } else if (type === 'site')  {
                reg = /^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i;
            }
            //console.log(type+$(val).val()+' to test');
            if(reg.test($(val).val()) === false){
                if (val.parent().find(".error_custom")[0] == null) {
                    $(error).insertAfter(val);
                    //$('button[type=submit]').attr('disabled', true)
                }
                return false;
            } else {
                val.parent().find(".error_custom").remove();
                //$('button[type=submit]').attr('disabled', false);
                return true;
            }

        }

        $('.js-example-basic-single[name="country"]').select2({
            width: '100%',
            tags: false,
        }).trigger('change');

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
        $('select[name=country]').on('change', function () {
            $('select[name=city_id]').val('');
            $('.js-example-basic-single[name="city_id"]').trigger('change');
        });

        /*Company*/
        $('#company_en').on("focus", function () {
            let name = $('#company_rus').val();
            //console.log(name);
            $.ajax({
                type: "POST",
                url: "{{ route('front.register.company.autocomplete') }}",
                data: {
                    _token:"{{ csrf_token() }}",
                    name: name,
                    lang: 'company_strong',
                },
                success: function(data) {
                    //console.log(data);
                    /*if true => dont have company*/
                    if (data === true) {
                        $('input[name="company_en"]').prop('readonly', false);
                        $('#companyIsUser').hide();
                    } else if (data === false) {
                        $('input[name="company_en"]').prop('readonly', true);
                        $('#companyIsUser').show();
                    } else {

                    }
                },
            });
        }).focusout(function () {
            $('#companyIsUser').css('display', 'none');
        });
        $('input#company_rus').keyup(function () {
            let word = $(this).val();
            let lang = $(this).attr('name');
            //let company_id = $('input[name="company_id"]');
            //checkCompanyIsUser(company_id.val());
            if (word.length >= 2) {
                $('input[name="company_en"]').prop('readonly', false);
                $('input.company').autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('front.register.company.autocomplete') }}",
                            data: {
                                _token: "{{ csrf_token() }}",
                                name: word,
                                lang: lang,
                            },
                            success: function (data) {
                                response($.map(data, function (item) {
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
                            $('input[name="company_en"]').val( ui.item.label_en )
                                .prop('readonly', true);
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
                            $('input[name="company_en"]').prop('readonly', true);
                        } else {
                            $('input[name="company_en"]').val( ui.item.label );
                        }
                        $('input[name="company_id"]').val( ui.item.value);
                        $('select[name="company_type"]').val(ui.item.type_id).selectmenu('refresh');
                        checkCompanyIsUser(ui.item.value);
                        return false;
                    }
                }).keydown(function(e){
                    if (e.keyCode === 13){
                        $('input[name="company_en"]').prop('readonly', true);
                    }
                });
            }
        });
        function checkCompanyIsUser(id){
            $.ajax({
                type: "POST",
                url: '{{route('front.check.company.is.user')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                },
                success: function(data) {
                    {{-- if company is user return -> true, if not user -> false --}}
                    if (data === true){
                        //$('#companyIsUser').show();
                        $('input[name="company_en"]').prop('readonly', true);
                    } else {
                       // $('#companyIsUser').hide();
                        $('input[name="company_en"]').prop('readonly', false);
                    }
                },
            });
        }

    });
    </script>
@endsection