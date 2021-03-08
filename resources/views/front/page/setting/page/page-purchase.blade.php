@extends('front.page.setting.account.account-main')

@section('content-setting-page')
    <div class="tab-friend companies-list">
        <div class="tab-friend companies-list active">
            @forelse($purchase as $cart)
                <div class="blog-row">
                    <h3>№ И-{{ $cart->id }}</h3>
                    <div class="blog-row__content row justify-content-between">
                        <div class="col-5">
                            @foreach ($cart->purchases as $product)
                                <span>
                                    <a href="{{route('front.page.shop.researches.category.entry', ['id' => $product->research->id])}}" class="li-link-blue">{{ $product->research->title }}</a>
                                </span>
                            @endforeach
                        </div>
                        <div class="col-2 text-center m-auto">
                            {{ $cart->total_count }} руб
                        </div>
                        <div class="col-3 m-auto">
                            <div class="col">
                                Создано: {{ \Carbon\Carbon::parse($cart->created_at)->isoFormat("DD MMMM YYYY") }}
                            </div>
                            <div class="col">
                                Обновлено: {{ \Carbon\Carbon::parse($cart->updated_at)->isoFormat("DD MMMM YYYY")  }}
                            </div>
                        </div>
                        <div class="col-2 text-center m-auto">
                            @if ($cart->status == 'paid')
                                <span>Оплачено</span>
                            @elseif($cart->status == 'waiting')
                                <span>Ожидание</span>
                            @elseif($cart->status == 'send')
                                <span>Отправлен</span>
                            @elseif($cart->status == 'started' || $cart->status == 'cancelled')
                                <span>Незаконченный</span>
                                @if ($cart->remind  == true)
                                    <div class="button-del">
                                        <a href="{{ route('front.shop.researches.shopping.cart', ['cart_id' => $cart->id]) }}" class="button button-micro button-l-red row-del">Оплатить</a>
                                    </div>
                                @endif
                            @else
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p>Покупок нет.</p>
            @endforelse
        </div>
        <div class="row m-minus">
            <div class="col-12">
                <div class="col-12">
                    {{$purchase->links('vendor.pagination.custom-front')}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_footer_account')
@endsection