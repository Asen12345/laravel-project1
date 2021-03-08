<?php

namespace App\Observers;

use App\Eloquent\Anons;
use Carbon\Carbon;

class AnonsObserver
{
    public function created()
    {

    }

    public function creating(Anons $anons) {
        $anons->date = Carbon::parse($anons->date)->toDate();
        $anons->will_end = Carbon::parse($anons->will_end)->toDate();
    }
    public function updating(Anons $anons) {
        $anons->date = Carbon::parse($anons->date)->toDate();
        $anons->will_end = Carbon::parse($anons->will_end)->toDate();
    }
}
