<?php

namespace App\Jobs;

use App\Eloquent\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateJobNewsBlogPostCommentJobAdmin implements ShouldQueue
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
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
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
            NewBlogPostCommentJobAdmin::dispatch($this->comment, $admin)->delay(now()->addSeconds($pause));
            $pause = $pause + $inter;
            $inter = $inter + 3;
        }
    }
}
