<?php

namespace App\Jobs;

use App\Mail\NewBlogPostAdmin;
use App\Mail\NewTopicAnswerAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendNewTopicAnswerJobAdmin implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $answer;
    private $admin;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $answer
     * @param $admin
     */
    public function __construct($answer, $admin)
    {
        $this->answer   = $answer;
        $this->admin    = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new NewTopicAnswerAdmin($this->answer, $this->admin->email, $this->admin->name));
    }
}
