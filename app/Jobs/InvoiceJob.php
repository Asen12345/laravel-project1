<?php

namespace App\Jobs;

use App\Mail\InvoiceChangeStatusMail;
use App\Mail\InvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class InvoiceJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    public $user;
    public $data;
    public $type;
    public $cart;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $cart
     * @param $user
     * @param $data
     * @param string $type
     */
    public function __construct($cart, $user, $data, $type = 'new')
    {
        $this->user = $user;
        $this->data  = $data;
        $this->cart  = $cart;
        $this->type  = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type == 'new') {
            Mail::send(new InvoiceMail($this->cart,$this->user, $this->data));
        } else {
            /*else change status*/
            Mail::send(new InvoiceChangeStatusMail($this->cart, $this->user, $this->data));
        }
    }
}
