<?php

namespace App\Observers;

use App\Eloquent\FeedbackShop;
use App\Jobs\FeedBackShopJob;

class FeedbackShopObserver
{
    /**
     * Handle the feedback shop "created" event.
     *
     * @param FeedbackShop $feedbackShop
     * @return void
     */
    public function created(FeedbackShop $feedbackShop)
    {
        FeedBackShopJob::dispatch($feedbackShop);
    }

    /**
     * Handle the feedback shop "updated" event.
     *
     * @param FeedbackShop $feedbackShop
     * @return void
     */
    public function updated(FeedbackShop $feedbackShop)
    {
        //
    }

    /**
     * Handle the feedback shop "deleted" event.
     *
     * @param FeedbackShop $feedbackShop
     * @return void
     */
    public function deleted(FeedbackShop $feedbackShop)
    {
        //
    }

    /**
     * Handle the feedback shop "restored" event.
     *
     * @param FeedbackShop $feedbackShop
     * @return void
     */
    public function restored(FeedbackShop $feedbackShop)
    {
        //
    }

    /**
     * Handle the feedback shop "force deleted" event.
     *
     * @param FeedbackShop $feedbackShop
     * @return void
     */
    public function forceDeleted(FeedbackShop $feedbackShop)
    {
        //
    }
}
