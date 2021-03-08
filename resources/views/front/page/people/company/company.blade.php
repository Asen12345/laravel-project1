@extends('front.layouts.app')

@section('content')
    <div class="col-xl-9 col-lg-8 main-content">
        <div class="container-fluid">
            <div class="row m-minus">
                <div class="col-12">
                    <div class="title-block title-block--iconed">
                        <h1>{{$user->name}}</h1>
                        <div class="title-block__icons title-block__count">
                            <div class="blogs-previews__views title-block__count">
                                <div class="ico-eye"></div>
                                <div class="blogs-previews__views-count">{{$count_view ?? '0'}}</div>
                            </div>
                        </div>
                    </div>
                    {{--Header accoun--}}
                    @include('front.page.people.company.chunk.account-card')
                    {{--Social info box--}}
                    @include('front.page.people.company.chunk.social-info')
                    {{--Listing news, messeges, posts--}}
                    @include('front.page.people.company.chunk.activity-blog')

                </div>
            </div>
        </div>
    </div>

@endsection

@section('sidebar-right')
    @include('front.sidebar_module.search')
    @include('front.sidebar_module.shop-category-menu-block')
    @include('front.sidebar_module.sidebar-analytics')
    @include('front.sidebar_module.banner')
    @include('front.sidebar_module.informer')
    @include('front.sidebar_module.sidebar-scene-news')
    @include('front.sidebar_module.sidebar-company')
    @include('front.partials.message-popup')
@endsection

@section('js_footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script src="{{asset('/js/messages_ru.js')}}"></script>
    <script>
        $(document).ready(function () {
            /*Start Add friends*/
            $('.add_friend').click(function () {
                $.ajax({
                    type: "POST",
                    url: "{{route('front.page.people.friend.add')}}",
                    data: {
                        _token:"{{ csrf_token() }}",
                        id: $(this).data('id')
                    },
                    success: function(data) {
                        if (data['result'] === 'save'){
                            $('.add_friend').remove();
                            $('.ico-plus').after('<span>Запрос отправлен</span>')
                        }
                        alert(data['text']);

                    },
                })
            });
            /*And Add friends*/
            $('.send_message_popup').on('click', function () {
                let user_to = $(this).data('user_to');
                $("input[name='user_to']").val(user_to)
            });
            $('#message-popup').validate({
                lang: 'ru',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
            });

            var url = document.location.search.split('?');
            if(url[1] !== undefined) {
                let tab_name = url[1].split('=')[0];
                let tab = $('.'+tab_name+'__tab');
                showTab(tab);
            }
        })

        function showTab(tab) {
            var $th = $(tab),
                $tabsWrapper = $th.closest('.tabs-wrapper'),
                $data = $th.attr('data-tab'),
                $parent = $th.parent();

            $parent.addClass('tabs__item--active')
                .siblings()
                .removeClass('tabs__item--active');

            $tabsWrapper
                .find('.tabs__content .tabs__item[data-tab=' + $data + ']')
                .removeClass('hidden')
                .siblings()
                .addClass('hidden');
        }
    </script>
@endsection