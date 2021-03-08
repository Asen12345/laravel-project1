<?php

namespace App\Mail;

use App\Repositories\Back\MailTemplateRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeStatusOrder extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $to_email;
    public $to_name;
    public $from_email;
    public $from_name;
    public $shoppingCart;

    /**
     * Create a new message instance.
     *
     * @param $shoppingCart
     */
    public function __construct($shoppingCart)
    {
        $this->to_email     = $shoppingCart->user->email;
        $this->to_name      = $shoppingCart->user->name;
        $this->shoppingCart = $shoppingCart;
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

        $template = (new MailTemplateRepository())->getByColumn('changing_order_status', 'template_id');

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
            'user_from'    => 'Администрация',
            'number'       => $this->shoppingCart->id,
            'user_to'      => $this->to_name,
            'status'       => $status,
            'template'     => $template['template_id'],
            'link'         => '<a href="' . route('front.setting.account', ['page' => 'purchase']) . '">Мои покупки</a>',
        ];

        return $this->to($this->to_email, $this->to_name)
            ->from($this->from_email, $this->from_name)
            ->subject($template->subject)
            ->view('email.new_register', $data);
    }
}
