<?php

namespace App\Jobs;

use App\Mail\ApprovedNewUser;
use App\Mail\RegisterNewUser;
use App\Mail\SendNewPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NewUserPasswordJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $user;
    private $who_change;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $who_change
     */
    public function __construct($user, $who_change)
    {
        $this->user = $user;
        $this->who_change = $who_change;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new SendNewPassword($this->user, $this->who_change));
    }
}
