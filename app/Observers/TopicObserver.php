<?php

namespace App\Observers;

use App\Eloquent\Topic;
use Carbon\Carbon;

class TopicObserver
{
    public function created()
    {

    }

    public function creating(Topic $anons) {
        $anons->published_at = Carbon::parse($anons->published_at)->toDate();
    }
    public function updating(Topic $anons) {
        $anons->published_at = Carbon::parse($anons->published_at)->toDate();
    }
}
