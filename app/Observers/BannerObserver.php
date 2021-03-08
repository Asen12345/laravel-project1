<?php

namespace App\Observers;

use App\Eloquent\Banner;
use Carbon\Carbon;

class BannerObserver
{
    public function creating(Banner $banner) {
        $banner->date_from = Carbon::parse($banner->date_from)->toDateString();
        $banner->date_to = isset($banner->date_to) ? Carbon::parse($banner->date_to)->toDateString() : null;
    }
    public function updating(Banner $banner) {
        $banner->date_from = Carbon::parse($banner->date_from)->toDateString();
        $banner->date_to = isset($banner->date_to) ? Carbon::parse($banner->date_to)->toDateString() : null;
    }
}
