<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceChangeStatusMail extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mailingUser;
    public $to_email;
    public $to_name;
    public $from_email;
    public $from_name;
    public $dataPdf;
    public $cart;

    /**
     * Create a new message instance.
     *
     * @param $cart
     * @param $mailingUser
     * @param $dataPdf
     */
    public function __construct($cart, $mailingUser, $dataPdf)
    {
        $this->cart        = $cart;
        $this->dataPdf     = $dataPdf;
        $this->mailingUser = $mailingUser;
        $this->to_email    = $mailingUser->email;
        $this->to_name     = $mailingUser->name;
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
        $pdf = PDF::loadView('front.page.shop.pdf', $this->dataPdf);

        if ($this->cart->status == 'paid') {
            $status = 'Оплачено';
        } elseif ($this->cart->status == 'waiting') {
            $status = 'Ожидание';
        } elseif ($this->cart->status == 'send') {
            $status = 'Отправлен';
        } elseif ($this->cart->status == 'started' || $this->cart->status == 'cancelled') {
            $status = 'Незаконченный';
        }


        $data = [
            'user_from'    => 'Администрация',
            'user_to'      => $this->mailingUser->name,
            'number'       => $this->cart->id,
            'status'       => $status ?? 'Неизвестен',
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject('Изменен статус вашего заказа')
            ->attachData($pdf->output(), "invoice.pdf")
            ->view('email.invoice_change_status', $data);
    }
}
