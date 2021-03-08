<?php

namespace App\Jobs;

use App\Mail\SendResetPasswordLink;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $user;
    private $hash;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $hash
     */
    public function __construct($user, $hash)
    {
        $this->user = $user;
        $this->hash = $hash;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $link = '<a href="' . route('api.password.reset', ['email' => $this->user->email, 'hash'  => $this->hash,]) . '">Перейти</a>';
        Mail::send(new SendResetPasswordLink($this->user, $link));
    }
}
