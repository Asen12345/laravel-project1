<?php

namespace App\Jobs;

use App\Mail\NewMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class NewMessageJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $message;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = $this->message->thread->subject;
        $userFormSend = $this->message->userSend;
        $userToSend = $this->message->thread
            ->messageParticipants()
            ->where('user_id', '!=', $userFormSend->id)
            ->first()
            ->user;
        if ($userToSend->invitations == true) {
            Mail::send(new NewMessage($this->message, $subject, $userFormSend, $userToSend));
        }

    }
}
