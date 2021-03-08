<?php

namespace App\Http\Controllers;

use App\Eloquent\ShoppingCart;
use App\Jobs\SendFiledOrderJob;
use Exception;
use Illuminate\Http\Request;

class HookController extends Controller
{
    public function webHook(Request $request)
    {
        // Достаем сигнатуру из заголовка и декодируем
        $signatureFromHeader = $this->get_signature_from_header($_SERVER['HTTP_CONTENT_SIGNATURE']);

        // Декодируем данные
        $decodedSignature = $this->urlsafe_base64decode($signatureFromHeader);

        // Данные, которые пришли в теле сообщения
        $content = file_get_contents('php://input');

        /**
         * Публичный ключ - ключ для обработки уведомлений о смене статуса
         *
         * Заходим в личный кабинет RBKmoney: Создать Webhook;
         * Вставляем в поле URL на который будут приходить уведомления
         * Выбираем Типы событий, например: InvoicePaid и InvoiceCanсelled;
         * После создания Webhook-а копируем Публичный ключ после нажатия на Показать детали;
         * Копируем Публичный ключ полностью с заголовками ---- BEGIN PUB...;
         */
        $webhookPublicKey = config('shop.webhook');

        if(!$this->verify_signature($content, $decodedSignature, $webhookPublicKey)) {
            return abort(401);
        }

        // Преобразуем данные в массив
        $data = json_decode($content, TRUE);

        $cart_id = $data['invoice']['metadata']['cart_id'];

        $cart = ShoppingCart::where('id', $cart_id)->first();

        /*Если возврвзается canceled a оплата была по invoice*/
        if (!empty($cart->payment)) {
            if ($cart->payment->type !== 'company') {
                $cart->update([
                    'status' => $data['invoice']['status']
                ]);

                if ($data['invoice']['status'] == 'cancelled') {
                    $cart->update([
                        'status' => 'started',
                        'remind' => true
                    ]);
                    SendFiledOrderJob::dispatch($cart);
                }
            } else {
                return abort(200);
            }
        } else {
            if (!empty($cart)) {
                $cart->update([
                    'status' => $data['invoice']['status']
                ]);

                if ($data['invoice']['status'] == 'cancelled') {
                    $cart->update([
                        'status' => 'started',
                        'remind' => true
                    ]);
                    SendFiledOrderJob::dispatch($cart);
                }
            }
        }

        return abort(200);
    }

    protected function get_signature_from_header($contentSignature) {
        $signature = preg_replace("/alg=(\S+);\sdigest=/", '', $contentSignature);

        if (empty($signature)) {
            return abort(401);
        }

        return $signature;
    }

    protected function urlsafe_base64decode($string) {
        return base64_decode(strtr($string, '-_,', '+/='));
    }

    // Проверяем сигнатуру
    protected function verify_signature($data, $signature, $publicKey) {
        if (empty($data) || empty($signature) || empty($publicKey)) {
            return abort(401);
        }

        $publicKeyId = openssl_get_publickey($publicKey);
        if (empty($publicKeyId)) {
            return abort(401);
        }

        $verify = openssl_verify($data, $signature, $publicKeyId, OPENSSL_ALGO_SHA256);

        return ($verify == 1);
    }
}
