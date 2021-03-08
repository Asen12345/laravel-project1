<?php

namespace App\Jobs;

use App\Eloquent\BlogPostSubscriber;
use App\Jobs\Blog\Post\NewPostNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewBlogPostJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $blogPost;

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
        $subscribeUsers = BlogPostSubscriber::where('blog_id', $this->blogPost->blog->id)
            ->where('active', true)
            ->get();

        foreach ($subscribeUsers as $user) {
            NewPostNotification::dispatch($this->blogPost, $user);
        }

    }
}
