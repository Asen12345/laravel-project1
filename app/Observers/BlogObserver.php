<?php

namespace App\Observers;

use App\Eloquent\Blog;
use App\Jobs\NewsBlogJob;

class BlogObserver
{
    /**
     * Handle the blog "created" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function created(Blog $blog)
    {
        /*Writing to all job to send a message to Admin*/
        NewsBlogJob::dispatch($blog, 'send_admin');
    }

    /**
     *
     *
     * @param Blog $blog
     * @return void
     */
    public function updating(Blog $blog)
    {
        /*If change 'active' by 'true' -> send mail "your account is approved"*/
        if ($blog->isDirty('active') && $blog->getAttribute('active') == true) {
            NewsBlogJob::dispatch($blog, 'send_user');
        }
    }

    /**
     * Handle the blog "deleted" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function deleted(Blog $blog)
    {
        //
    }

    /**
     * Handle the blog "restored" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function restored(Blog $blog)
    {
        //
    }

    /**
     * Handle the blog "force deleted" event.
     *
     * @param Blog $blog
     * @return void
     */
    public function forceDeleted(Blog $blog)
    {
        //
    }
}
