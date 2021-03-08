<?php

namespace App\Http\Controllers\Front\Shop;

use App\Eloquent\Admin;
use App\Eloquent\BankDetail;
use App\Eloquent\Researches;
use App\Eloquent\ShoppingCart;
use App\Eloquent\ShoppingPayments;
use App\Eloquent\ShoppingPurchase;
use App\Http\PageContent\Frontend\Shop\ResearchesPageContent;
use App\Jobs\ChangeStatusOrderAdminJob;
use App\Jobs\InvoiceJob;
use App\Jobs\NewOrderAdminJob;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoppingCartController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('front.home')->withErrors([
                    'error' => 'Ваша сессия была закрыта. Войдите в аккаунт.'
                ]);
            } else {
                $this->user = auth()->user();
                if ($this->user->active !== true) {
                    return redirect()->back()->withErrors([
                        'error' => 'Учетная запись не одобрена, ожидайте E-mail письма об активации.'
                    ]);
                }
                return $next($request);
            }
        });
    }

    public function addCart (Request $request, $id)
    {
        $product = Researches::find($id);

        $userCart = $this->user
            ->cart()
            ->firstOrCreate(
                ['status' => 'started', 'remind' => false]

            );

        $purchase = $userCart->purchases()->create([
            'user_id'     => $this->user->id,
            'research_id' => $id,
            'price'       => $product->price,
        ]);

        if(empty($purchase->id)){
            return redirect()->back()->withErrors(['error', 'При обновлении корзины произошла ошибка.']);
        } else {
            return redirect()->back()->with('successAddCart', 'Исследование ' . $product->title . ' добавлено в корзину.');
        }
    }

    public function deleteCart (Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $purchase = ShoppingPurchase::where('user_id', $this->user->id)
                ->where('id', $id)->first();

            $cart = $purchase->cart;

            $purchase->delete();
            if (!$cart->purchases()->exists()) {
                $cart->delete();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return ['error' => $id_index];
        }

        return redirect()
            ->back()
            ->with('success', 'Исследование удалено из корзины.');

    }

    public function showCart(Request $request)
    {

        if (!empty($request->cart_id)) {
            /*Return false pay cart*/
            $researchesCart = $this->user
                ->cart()
                ->where(function ($query) use ($request) {
                    $query->where('status', 'started')
                        ->where('id', $request->cart_id)
                        ->where('remind', true);
                })
                ->with('purchases')
                ->first();
        } else {
            $researchesCart = $this->user
                ->cart()
                ->where(function ($query) {
                    $query->where('status', 'started')
                        ->where('remind', false);
                })
                ->with('purchases')
                ->first();
        }

        $researchesTotal = 0;

        if (!empty($researchesCart->purchases)) {
            foreach ($researchesCart->purchases as $product) {
                $researchesTotal = $researchesTotal + $product->research->price;
            }
        }
        if (!empty($researchesCart->purchases)) {
            $rbk = json_decode($this->createInvoice($researchesCart, $researchesCart->purchases, $researchesTotal), true);
            if (!empty($rbk['invoice']['id'])) {
                $rbkInvoiceId = $rbk['invoice']['id'];
                $rbkInvoiceToken = $rbk['invoiceAccessToken']['payload'];
            } else {
                return redirect()
                    ->back()
                    ->withErrors(['Произошла ошибка, обратитесь к администрации сайта.']);
            }
        } else {
            $rbkInvoiceId = '';
            $rbkInvoiceToken = '';
        }

        return view('front.page.shop.cart', [
            'content_page'    => (new ResearchesPageContent())->cartPageContent(),
            'researchesCart'  => $researchesCart,
            'researchesTotal' => $researchesTotal,
            'rbkInvoiceId'    => trim($rbkInvoiceId),
            'rbkInvoiceToken' => trim($rbkInvoiceToken)
        ]);
    }

    public function checkout(Request $request, $type)
    {

        if ((int)$request->shopping_cart_id) {

            if ($type === 'company'){
                $request['type'] = 'company';

                $payments = ShoppingPayments::updateOrCreate([
                   'shopping_cart_id' => $request->shopping_cart_id
                ], $request->all());

                if (!empty($payments->id)) {

                    $settingBank = BankDetail::first();
                    $data['payments']    = $payments->toArray();
                    $data['settingBank'] = $settingBank;
                    $data['products']    = $payments->cart()->find($request->shopping_cart_id)->purchases;

                    if ($payments->cart()->find($request->shopping_cart_id)->remind == true) {
                        InvoiceJob::dispatch($payments->cart()->find($request->shopping_cart_id), $this->user, $data, 'change');
                    } else {
                        InvoiceJob::dispatch($payments->cart()->find($request->shopping_cart_id), $this->user, $data, 'new');
                    }

                    $pause1 = rand(1, 10);
                    $admins = Admin::where('role', 'admin')->where('active', true)->get();
                    $inter = 4;

                    if ($payments->cart()->find($request->shopping_cart_id)->remind == true) {

                        foreach ($admins as $admin) {
                            ChangeStatusOrderAdminJob::dispatch($payments->cart()->find($request->shopping_cart_id), $admin)->delay(now()->addSeconds($pause1));
                            $pause1 = $pause1 * $inter;
                            $inter++;
                        }

                    } else {

                        foreach ($admins as $admin) {

                            NewOrderAdminJob::dispatch($payments->cart()->find($request->shopping_cart_id), $admin)->delay(now()->addSeconds($pause1));
                            $pause1 = $pause1 * $inter;
                            $inter++;
                        }
                    }

                    ShoppingCart::where('id', $request->shopping_cart_id)->update([
                        'status' => 'waiting'
                    ]);
                };
            }

        } else {
            return redirect()
                ->back()
                ->withErrors(['Произошла ошибка, обратитесь к администрации сайта.']);
        }

        return redirect()
            ->route('front.page.shop')
            ->with('success', 'Счет сформирован и отправлен вам на почту.');
    }

    protected function prepare_headers($apiKey)
    {
        $headers = [];
        $headers[] = 'X-Request-ID: ' . uniqid();
        $headers[] = 'Authorization: Bearer ' . trim($apiKey);
        $headers[] = 'Content-type: application/json; charset=utf-8';
        $headers[] = 'Accept: application/json';
        return $headers;
    }

    protected function createInvoice($cart, $purchases)
    {
        $apiKey = config('shop.key');
        $shopId = config('shop.id');

        $curl = curl_init();

        $postField = "{\"shopID\": \"" . $shopId . "\", \"dueDate\": \"" . Carbon::now()->addMinutes(10)->toIso8601String() . "\",\"currency\": \"RUB\",\"product\": \"Заказ № И-". $cart->id ."\",\"description\": \"Оплата заказа на сайте www.ludiipoteki.ru\",\"cart\": [" . $this->createArrayCart($purchases)  . "], \"metadata\": { \"cart_id\": " . $cart->id . "}}";


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rbk.money/v1/processing/invoices",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postField,
            CURLOPT_HTTPHEADER => $this->prepare_headers($apiKey),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    protected function createArrayCart ($purchases) {
        $arrayProducts = '';
        foreach ($purchases as $product) {
            $arrayProducts = $arrayProducts . '{"price": '. $product->research->price * 100 .', "product": "' . $product->research->title . '", "quantity": 1,},';
        }
        return $arrayProducts;
    }

    public function checkPaid(Request $request){

        $cart = ShoppingCart::where('user_id', auth()->user()->id)
            ->where('id', $request->cart)->first();
        $cart->update([
            'status' => 'waiting'
        ]);
        if ($cart->status == 'waiting') {
            return 'success';
        } else {
            return 'error';
        }

        /*return redirect()
            ->route('front.page.shop.researches.author', ['id' => 1])
            ->with('success', 'После оплаты ожидайте письмо на ваш email.');*/
    }

}
