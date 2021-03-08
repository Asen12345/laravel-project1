<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFiledOrder extends Mailable
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mailingUser;
    public $to_email;
    public $to_name;
    public $from_email;
    public $from_name;
    public $dataPdf;
    public $filedOrders;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $filedOrders
     * @param $user
     */
    public function __construct($filedOrders, $user)
    {
        $this->filedOrders = $filedOrders;
        $this->mailingUser = $user;
        $this->to_email    = $user->email;
        $this->to_name     = $user->name;
        $this->from_email  = config('mail.from.address');
        $this->from_name   = config('mail.from.name');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'user_from'    => 'Администрация',
            'user_to'      => $this->mailingUser->name,
            'number'       => $this->filedOrders->id,
            'link'         => route('front.shop.researches.shopping.cart', ['cart_id' => $this->filedOrders->id])
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject('Неоплаченые исследования')
            ->view('email.filed_order', $data);
    }
}
