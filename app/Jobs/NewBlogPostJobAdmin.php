<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use App\Mail\NewBlogPostAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class NewBlogPostJobAdmin implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $blogPost;
    private $admin;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $blogPost
     * @param $admin
     */
    public function __construct($blogPost, $admin)
    {
        $this->blogPost = $blogPost;
        $this->admin    = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new NewBlogPostAdmin($this->blogPost, $this->admin->email, $this->admin->name));
    }
}
