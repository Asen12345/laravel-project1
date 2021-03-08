@extends('admin_panel.layouts.app')

@section('section_header')
    <link href="{{asset('/assets/summernote/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    @include('admin_panel.users.partials.setting_tab')

@endsection

@section('footer_js')
    <script src="{{asset('/assets/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/js/jquery.mask.min.js')}}"></script>
    <script src="{{asset('/js/validator.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('/assets/summernote/lang/summernote-ru-RU.js')}}"></script>
    <script src="{{asset('/assets/summernote/summernote-image-title.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#about_me').summernote({
                imageTitle: {
                    specificAltField: true,
                },
                lang: 'ru-RU',
                popover: {
                    image: [
                        ['imagesize', ['resizeFull', 'resizeHalf', 'resizeQuarter']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']],
                        ['custom', ['imageTitle']],
                    ],
                },
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

            $('input[type=checkbox]').on('change', function() {
                let checkbox = $(this);
                if (checkbox.is(":checked") === true) {
                    checkbox.val('1');
                } else {
                    checkbox.val('0');
                }
            });
            $('#private').click(function() {
                checkPrivate(this)
            });
            /*Validation Form*/
            $('#form').validator();
            $('#email, #work_email, #personal_email').on('keyup', function(e) {
                emailCheck($(this));
            });
            $('#password_confirmation').on('keyup', function() {
                passCheck();
            });
            $('#mobile_phone, #work_phone').mask('+0(000)000-00-00');
            $('#permission').on('change', function () {
                let val = $(this).val();
                checkPermission(val);
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
                        let country = $('select[name="country_id"]' , form).val();
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
            $('select[name=country_id]').on('change', function () {
                $('select[name=city_id]').val('');
                $('.js-example-basic-single[name="city_id"]').trigger('change');
            });
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
                            $('select[name="company_type"]').val( ui.item.type_id);
                            return false;
                        }
                    });
                }
            });
            checkPermission($('#permission').val());
        });
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
                        $('#about_me').summernote("insertNode", image[0]);
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        function emailCheck(val){
            let email = val;
            if(email.val() === ""){
                email.addClass('is-invalid');
                return false;
            }else{
                let regMail =  /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,3})$/;
                if(regMail.test(email.val()) === false){
                    email.addClass('is-invalid');
                    return false;
                }else{
                    email.removeClass('is-invalid');
                    //$('#next-form').collapse('show');
                }

            }
        }
        function passCheck(){
            let password = $('#password');
            let ret_pass = $('#password_confirmation');
            if(password.val() !== ret_pass.val()){
                ret_pass.addClass('is-invalid');
                $("#cp").html('<span class="text-danger">Пароли не совпадают</span>');
                return false;
            }else{
                ret_pass.removeClass('is-invalid');
                $("#cp").find('span').remove();
            }
        }

        function checkPermission(val) {
            if (val === 'expert') {
                $('.name-expert').css('display', '');
                $('.private').css('display', '');
                $('#firstname').prop('required',true);
                $('#lastname').prop('required',true);
                $('#company_rus').prop('required',false);
                $('select[name=company_type]').prop('required',false);
                $('.work_div').css('display', 'flex');
            } else {
                $('.name-expert').css('display', 'none');
                $('.private').css('display', 'none');
                $('#firstname').prop('required',false);
                $('#lastname').prop('required',false);
                $('select[name=company_type]').prop('required',true);
                $('#company_rus').prop('required',true);
                $('#company_post').prop('required',false);
                $('.work_div').css('display', 'none');
            }
            /*let check_private = $(document).find('#private');
            checkPrivate(check_private);*/
        }
        function checkPrivate (elem) {
            if ($(elem).is(':checked')) {
                $('.work_div').css('display', 'none');
                $('#company_rus').prop('required',false);
                $('#ui-id-2').prop('required',false);
                $('#company_post').prop('required',false);
            } else {
                $('.work_div').css('display', 'flex');
                $('#company_rus').prop('required',true);
                $('#ui-id-2').prop('required',true);
                $('#company_post').prop('required',true);
            }
        }


    </script>
    @include('admin_panel.components.picture_upload.picture_upload_js', ['type' => 'admin'])
@endsection