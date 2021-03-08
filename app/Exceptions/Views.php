<?php

namespace App\Exceptions;

use Carbon\Carbon;
use \CyrildeWit\EloquentViewable\Views as OldViews;

class Views extends OldViews
{


    public function record(): bool
    {
        if ($this->shouldRecord()) {
            $this->viewable->views()
                ->updateOrCreate([
                    'visitor' => $this->resolveVisitorId(),
                    'viewable_id' => $this->viewable->getKey(),
                    'viewable_type' => $this->viewable->getMorphClass(),
                    'collection' => $this->collection,
                ], [
                    'viewed_at' => Carbon::now()
                ]);
            return true;
        }

        return false;
    }

}
