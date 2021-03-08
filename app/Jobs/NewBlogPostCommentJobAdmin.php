<?php

namespace App\Jobs;

use App\Mail\NewBlogPostAdmin;
use App\Mail\NewBlogPostCommentAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class NewBlogPostCommentJobAdmin implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $comment;
    private $admin;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $comment
     * @param $admin
     */
    public function __construct($comment, $admin)
    {
        $this->comment = $comment;
        $this->admin   = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new NewBlogPostCommentAdmin($this->comment, $this->admin->email, $this->admin->name));
    }
}
