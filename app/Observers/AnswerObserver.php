<?php

namespace App\Observers;

use App\Eloquent\TopicAnswer;
use Carbon\Carbon;

class AnswerObserver
{
    public function created()
    {

    }

    public function creating(TopicAnswer $anons) {
        $anons->published_at = Carbon::parse($anons->published_at)->toDateTime();
    }
    public function updating(TopicAnswer $anons) {
        $anons->published_at = Carbon::parse($anons->published_at)->toDateTime();
    }
}
