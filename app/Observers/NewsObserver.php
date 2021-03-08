<?php

namespace App\Observers;

use App\Eloquent\News;
use App\Jobs\NewNewsJob;

class NewsObserver
{
    /**
     * Handle the news "created" event.
     *
     * @param News $news
     * @return void
     */
    public function created(News $news)
    {
        /*Writing to all job to send a message to Admin*/
        NewNewsJob::dispatch($news, 'send_admin');
    }

    /**
     * Handle the news "updated" event.
     *
     * @param News $news
     * @return void
     */
    public function updated(News $news)
    {
        /*If change 'active' by 'true' -> send mail "your account is approved"*/
        if ($news->isDirty('published') && $news->getAttribute('published') == true) {
            if($news->author_user_id == true) {
                NewNewsJob::dispatch($news, 'send_user');
            };
        }

    }

    /**
     * Handle the news "deleted" event.
     *
     * @param News $news
     * @return void
     */
    public function deleted(News $news)
    {
        //
    }

    /**
     * Handle the news "restored" event.
     *
     * @param News $news
     * @return void
     */
    public function restored(News $news)
    {
        //
    }

    /**
     * Handle the news "force deleted" event.
     *
     * @param News $news
     * @return void
     */
    public function forceDeleted(News $news)
    {
        //
    }
}
