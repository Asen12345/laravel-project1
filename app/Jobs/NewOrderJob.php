<?php

namespace App\Jobs;

use App\Mail\NewOrderCartUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class NewOrderJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    public $shoppingCart;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $shoppingCart
     */
    public function __construct($shoppingCart)
    {
        $this->shoppingCart = $shoppingCart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new NewOrderCartUser($this->shoppingCart));
    }
}
