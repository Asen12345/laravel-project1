<?php

namespace App\Observers;

use App\Eloquent\Admin;
use App\Eloquent\BankDetail;
use App\Eloquent\ShoppingCart;
use App\Jobs\ChangeStatusOrderAdminJob;
use App\Jobs\ChangeStatusOrderJob;
use App\Jobs\InvoiceJob;
use App\Jobs\NewOrderAdminJob;
use App\Jobs\NewOrderJob;
use App\Jobs\SendResearchJob;

class ShoppingCartObserver
{
    /**
     * Handle the shopping cart "created" event.
     *
     * @param ShoppingCart $shoppingCart
     * @return void
     */
    public function created(ShoppingCart $shoppingCart)
    {
        //
    }

    /**
     * Handle the shopping cart "updated" event.
     *
     * @param ShoppingCart $shoppingCart
     * @return void
     */
    public function updated(ShoppingCart $shoppingCart)
    {
        $pause = rand(1, 10);
        $pause1 = rand(1, 10);

        $role = auth()->user()->role ?? null; // admin - redactor
        $paymentType = $shoppingCart->payment->type ?? null; // company

        /*Новый ордер*/
        if ($shoppingCart->getOriginal('status') == 'started' && $shoppingCart->isDirty('status') && $shoppingCart->getAttribute('status') !== 'paid' && $shoppingCart->getAttributeValue('remind') !== 1 && $paymentType !== 'company') {
            NewOrderJob::dispatch($shoppingCart);
            $admins = Admin::where('role', 'admin')->where('active', true)->get();
            $inter = 4;
            foreach ($admins as $admin) {
                NewOrderAdminJob::dispatch($shoppingCart, $admin)->delay(now()->addSeconds($pause1));
                $pause1 = $pause1 * $inter;
                $inter ++;
            }
        }

        /* Изменение статуса*/
        if ($shoppingCart->isDirty('status') && $shoppingCart->getAttribute('status') !== 'paid' && $shoppingCart->getOriginal('status') !== 'started' && $shoppingCart->getAttribute('status') !== 'cancelled' && $shoppingCart->getOriginal('status') !== 'cancelled' && $shoppingCart->getAttribute('status') !== 'waiting' && $paymentType !== 'company') {

            ChangeStatusOrderJob::dispatch($shoppingCart);

            $admins = Admin::where('role', 'admin')->where('active', true)->get();
            $inter = 4;
            foreach ($admins as $admin) {
                ChangeStatusOrderAdminJob::dispatch($shoppingCart, $admin)->delay(now()->addSeconds($pause1));
                $pause1 = $pause1 * $inter;
                $inter ++;
            }
        }

        /*If change status from admin to send invoice*/
        if ($shoppingCart->isDirty('status') && $shoppingCart->getAttribute('status') !== 'paid' && $paymentType == 'company') {

            $settingBank = BankDetail::first();

            $data['payments']    = $shoppingCart->payment->toArray();
            $data['settingBank'] = $settingBank;
            $data['products']    = $shoppingCart->purchases;

            if ($shoppingCart->isDirty('remind')) {
                if ($shoppingCart->getAttribute('remind') == false) {
                    InvoiceJob::dispatch($shoppingCart, $shoppingCart->user, $data, 'new');
                }
            }

            if ($role == 'admin' || $role == 'redactor') {
                $admins = Admin::where('role', 'admin')->where('active', true)->get();
                $inter = 4;
                foreach ($admins as $admin) {
                    ChangeStatusOrderAdminJob::dispatch($shoppingCart, $admin)->delay(now()->addSeconds($pause1));
                    $pause1 = $pause1 * $inter;
                    $inter++;
                }
            }
        }

        if ($shoppingCart->getAttribute('status') == 'paid' && $shoppingCart->isDirty('status')) {
            SendResearchJob::dispatch($shoppingCart)->delay(now()->addSeconds($pause));
        }

    }

    /**
     * Handle the shopping cart "deleted" event.
     *
     * @param ShoppingCart $shoppingCart
     * @return void
     */
    public function deleted(ShoppingCart $shoppingCart)
    {
        //
    }

    /**
     * Handle the shopping cart "restored" event.
     *
     * @param ShoppingCart $shoppingCart
     * @return void
     */
    public function restored(ShoppingCart $shoppingCart)
    {
        //
    }

    /**
     * Handle the shopping cart "force deleted" event.
     *
     * @param ShoppingCart $shoppingCart
     * @return void
     */
    public function forceDeleted(ShoppingCart $shoppingCart)
    {
        //
    }
}
