<?php

namespace App\Jobs;

use App\Eloquent\UserNotifyComment;
use App\Jobs\Blog\Post\NewCommentNotification;
use App\Mail\NewBlogPostComment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use \Illuminate\Support\Facades\Mail;

class NewBlogPostCommentJob implements ShouldQueue
{
    public $tries = 2;
    public $timeout = 30;
    public $retryAfter = 10;

    private $comment;

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
        $post   = $this->comment->post;
        $blog   = $this->comment->blog;
        $text = $this->comment->text;
        if ($this->comment->anonym == false) {
            $userName = $this->comment->user->name;
        } else {
            $userName = 'Аноним';
        }
        $user = $this->comment->blog->user;

        NewCommentNotification::dispatch($this->comment, $userName, $user);

        $commentSubscribers = UserNotifyComment::where('notify', true)
            ->where('blog_post_id', $post->id)->get();
        foreach ($commentSubscribers as $commentSubscriber) {
            NewCommentNotification::dispatch($this->comment, $userName, $commentSubscriber->user);
        }

    }
}
