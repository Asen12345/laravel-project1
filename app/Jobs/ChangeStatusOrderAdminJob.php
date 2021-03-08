<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use App\Mail\ChangeStatusOrderAdmin;
use App\Mail\NewOrderCart;
use App\Mail\RegisterNewUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class ChangeStatusOrderAdminJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    public $shoppingCart;
    public $admin;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $shoppingCart
     * @param $admin
     */
    public function __construct($shoppingCart, $admin)
    {
        $this->shoppingCart = $shoppingCart;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new ChangeStatusOrderAdmin($this->shoppingCart, $this->admin));
    }
}
