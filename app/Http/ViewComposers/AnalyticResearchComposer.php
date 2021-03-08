<?php

namespace App\Http\ViewComposers;

use App\Eloquent\CompanyType;
use App\Eloquent\GeoCountry;
use App\Eloquent\Researches;
use Illuminate\View\View;

class AnalyticResearchComposer
{
    public function compose(View $view) {

        $researchesAnalytic = Researches::where('main', true)->get()->shuffle();
        return $view->with('researchesAnalytic', $researchesAnalytic);

    }
}