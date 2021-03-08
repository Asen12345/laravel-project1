<?php

namespace App\Jobs;

use App\Mail\MailingAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class MailingAdminJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $mailingUser;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $mailingUser
     */
    public function __construct($mailingUser)
    {
        $this->mailingUser = $mailingUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->mailingUser->user->invitations == true) {
            Mail::send(new MailingAdmin($this->mailingUser));
        }
    }
}
