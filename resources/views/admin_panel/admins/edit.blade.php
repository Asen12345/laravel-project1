@extends('admin_panel.layouts.app')

@section('section_header')
    <link rel="stylesheet" href="{{asset('/assets/jquery-ui/jquery-ui.min.css')}}">
@endsection

@section('section_title')
    {{$content['title']}}
@endsection

@section('section_breadcrumbs')
    @include('admin_panel.partials.breadcrumbs', ['crumbs' => $content['crumbs']])
@endsection

@section('content')

    @include('admin_panel.admins.partials.setting_tab')

@endsection

@section('footer_js')
    <script src="{{asset('/js/jquery.mask.min.js')}}"></script>
    <script src="{{asset('/js/validator.min.js')}}"></script>
    <script src="{{asset('/assets/jquery-ui/jquery-ui.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('input[type=checkbox]').on('change', function() {
                let checkbox = $(this);
                changeValue(checkbox);
            });
            /*Validation Form*/
            $('#form').validator();
            $('#email').on('keyup', function(e) {
                emailCheck();
            });
            $('#password_confirmation').on('keyup', function() {
                passCheck();
            });
            $('#permission').on('change', function () {
                checkPermission($(this).val());
            });

        });
        $('#all_news').on('change', function () {
            let check = $(this).is(":checked");
            //console.log($(document).find('[type=checkbox]'));
            $('#category_list').parent().find('[type=checkbox]').each(function() {
                if (check === true) {
                    $(this).prop('checked', ':checked');
                    changeValue($(this))
                } else {
                    $(this).prop('checked', '');
                    changeValue($(this))
                }
            });
        });
        function changeValue (elem){
            if (elem.is(":checked") === true) {
                elem.val('1');
            } else {
                elem.val('0');
            }
        }
        function emailCheck(){
            let email = $('#email');
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
            if (val === 'redactor') {
                $('#permission_div').css('display', 'block')
            } else {
                $('#permission_div').css('display', 'none')
            }
        }
        checkPermission($('#permission').val());
    </script>
@endsection