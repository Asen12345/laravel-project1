<?php

namespace App\Observers;

use App\Eloquent\BlogPost;
use App\Events\NoticeBlogPostAccountEvent;
use App\Jobs\CreateJobNewsBlogPostJobAdmin;
use App\Jobs\NewBlogPostJob;

class BlogPostObserver
{
    /**
     * Handle the blog post "created" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost)
    {

    }

    public function created(BlogPost $blogPost)
    {
        /*Запускаем воркер для создания jobs (отпускаем запрос без задержки -> там уже логика)
        * один job - 1 письмо
        */
        CreateJobNewsBlogPostJobAdmin::dispatch($blogPost);
    }

    /**
     * Handle the blog post "updated" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function updated(BlogPost $blogPost)
    {
        //
    }

    public function updating(BlogPost $blogPost)
    {
        /*If change 'published' by 'true' -> send newsletter"*/
        if ($blogPost->isDirty('published') && $blogPost->getAttribute('published') == true) {
            NewBlogPostJob::dispatch($blogPost);
            event(new NoticeBlogPostAccountEvent($blogPost));
        }
    }

    /**
     * Handle the blog post "deleted" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function deleted(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the blog post "restored" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function restored(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the blog post "force deleted" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function forceDeleted(BlogPost $blogPost)
    {
        //
    }
}
