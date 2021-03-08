<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use App\Mail\FeedBack;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class FeedBackShopJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $feedbackShop;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @param $feedbackShop
     */
    public function __construct($feedbackShop)
    {
        $this->feedbackShop = $feedbackShop;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admins = Admin::where('role', 'admin')->where('active', true)->get();
        foreach ($admins as $admin) {
            Mail::send(new FeedBack($this->feedbackShop, $admin));
        }
    }
}
