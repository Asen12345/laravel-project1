<?php

namespace App\Observers;

use App\Eloquent\BlogPostDiscussion;
use App\Events\NoticePostCommentAccountEvent;
use App\Jobs\CreateJobNewsBlogPostCommentJobAdmin;
use App\Jobs\CreateJobNewsBlogPostJobAdmin;
use App\Jobs\NewBlogPostCommentJob;

class NewBlogPostCommentObserver
{
    /**
     * Handle the blog post discussion "created" event.
     *
     * @param BlogPostDiscussion $blogPostDiscussion
     * @return void
     */
    public function created(BlogPostDiscussion $blogPostDiscussion)
    {
        CreateJobNewsBlogPostCommentJobAdmin::dispatch($blogPostDiscussion);
        NewBlogPostCommentJob::dispatch($blogPostDiscussion);
        event(new NoticePostCommentAccountEvent($blogPostDiscussion));
    }

    /**
     * Handle the blog post discussion "updated" event.
     *
     * @param BlogPostDiscussion $blogPostDiscussion
     * @return void
     */
    public function updated(BlogPostDiscussion $blogPostDiscussion)
    {
        //
    }

    /**
     * Handle the blog post discussion "deleted" event.
     *
     * @param BlogPostDiscussion $blogPostDiscussion
     * @return void
     */
    public function deleted(BlogPostDiscussion $blogPostDiscussion)
    {
        //
    }

    /**
     * Handle the blog post discussion "restored" event.
     *
     * @param BlogPostDiscussion $blogPostDiscussion
     * @return void
     */
    public function restored(BlogPostDiscussion $blogPostDiscussion)
    {
        //
    }

    /**
     * Handle the blog post discussion "force deleted" event.
     *
     * @param BlogPostDiscussion $blogPostDiscussion
     * @return void
     */
    public function forceDeleted(BlogPostDiscussion $blogPostDiscussion)
    {
        //
    }
}
