<?php

namespace App\Jobs;

use App\Mail\SendFiledOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendFiledOrderJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    public $filedOrders;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $filedOrders
     */
    public function __construct($filedOrders)
    {
        $this->filedOrders = $filedOrders;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->filedOrders->user;

        Mail::send(new SendFiledOrder($this->filedOrders, $user));
    }
}
