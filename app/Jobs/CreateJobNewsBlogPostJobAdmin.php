<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateJobNewsBlogPostJobAdmin implements ShouldQueue
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
     */
    public function __construct($blogPost)
    {
        $this->blogPost = $blogPost;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pause = 3;
        $admins = Admin::where('active', true)->get();
        $inter = 5;
        foreach ($admins as $admin) {
            NewBlogPostJobAdmin::dispatch($this->blogPost, $admin)->delay(now()->addSeconds($pause));
            $pause = $pause + $inter;
            $inter = $inter + 3;
        }
    }
}
