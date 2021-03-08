<?php

namespace App\Observers;

use App\Eloquent\Researches;
use App\Jobs\NewResearchesJob;
use Carbon\Carbon;

class ResearchesObserver
{
    /**
     * Handle the researches "created" event.
     *
     * @param Researches $researches
     * @return void
     */
    public function created(Researches $researches)
    {
        NewResearchesJob::dispatch($researches);
    }

    /**
     * Handle the researches "updated" event.
     *
     * @param Researches $researches
     * @return void
     */
    public function updated(Researches $researches)
    {
        //
    }

    public function creating(Researches $researches) {
        $researches->published_at = isset($researches->published_at) ? Carbon::parse($researches->published_at)->toDate() : null;
    }

    public function updating(Researches $researches) {
        $researches->published_at = isset($researches->published_at) ? Carbon::parse($researches->published_at)->toDate() : null;
    }

    /**
     * Handle the researches "deleted" event.
     *
     * @param Researches $researches
     * @return void
     */
    public function deleted(Researches $researches)
    {
        //
    }

    /**
     * Handle the researches "restored" event.
     *
     * @param Researches $researches
     * @return void
     */
    public function restored(Researches $researches)
    {
        //
    }

    /**
     * Handle the researches "force deleted" event.
     *
     * @param Researches $researches
     * @return void
     */
    public function forceDeleted(Researches $researches)
    {
        //
    }
}
