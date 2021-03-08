<?php

namespace App\Observers;

use App\Eloquent\Feedback;
use App\Jobs\FeedBackSiteJob;

class SiteFeedbackObserver
{
    /**
     * Handle the feedback "created" event.
     *
     * @param Feedback $feedback
     * @return void
     */
    public function created(Feedback $feedback)
    {
        FeedBackSiteJob::dispatch($feedback);
    }

    /**
     * Handle the feedback "updated" event.
     *
     * @param Feedback $feedback
     * @return void
     */
    public function updated(Feedback $feedback)
    {
        //
    }

    /**
     * Handle the feedback "deleted" event.
     *
     * @param Feedback $feedback
     * @return void
     */
    public function deleted(Feedback $feedback)
    {
        //
    }

    /**
     * Handle the feedback "restored" event.
     *
     * @param Feedback $feedback
     * @return void
     */
    public function restored(Feedback $feedback)
    {
        //
    }

    /**
     * Handle the feedback "force deleted" event.
     *
     * @param Feedback $feedback
     * @return void
     */
    public function forceDeleted(Feedback $feedback)
    {
        //
    }
}
