<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderCart extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $shoppingCart;
    public $from_email;
    public $from_name;
    public $to_email;
    public $to_name;


    /**
     * Create a new message instance.
     *
     * @param $shoppingCart
     * @param $admin
     */
    public function __construct($shoppingCart, $admin)
    {
        $this->shoppingCart = $shoppingCart;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
        $this->to_email     = $admin->email;
        $this->to_name      = $admin->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $researchesTotal = 0;
        $products = [];
        foreach ($this->shoppingCart->purchases as $key => $product) {
            $researchesTotal = $researchesTotal + $product->research->price;
            $products[$key] = $product->research;
        }

        if ($this->shoppingCart->status == 'paid') {
            $status = 'Оплачено';
        } elseif ($this->shoppingCart->status == 'waiting') {
            $status = 'Ожидание';
        } elseif ($this->shoppingCart->status == 'send') {
            $status = 'Отправлен';
        } elseif ($this->shoppingCart->status == 'started' || $this->shoppingCart->status == 'cancelled') {
            $status = 'Незаконченный';
        }

        $data = [
            'order_id'   => $this->shoppingCart->id,
            'status'     => $status,
            'total_sum'  => $researchesTotal,
            'products'   => $products,
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject('Новый заказ на сайте ЛюдиИпотеки')
            ->view('email.new_order_admin', $data);
    }
}
