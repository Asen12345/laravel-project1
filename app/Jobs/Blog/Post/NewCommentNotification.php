<?php

namespace App\Jobs\Blog\Post;

use App\Mail\NewBlogPostComment;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NewCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $comment;
    protected $userFrom;
    protected $userTo;

    /**
     * Create a new job instance.
     *
     * @param Model $comment
     * @param string $userFrom
     * @param Model $userTo
     */
    public function __construct(Model $comment, string $userFrom, Model $userTo)
    {
        $this->comment = $comment;
        $this->userFrom = $userFrom;
        $this->userTo = $userTo;
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
        $text   = $this->comment->text;

        Mail::send(new NewBlogPostComment($post, $blog, $text, $this->userFrom, $this->userTo));
    }
}
