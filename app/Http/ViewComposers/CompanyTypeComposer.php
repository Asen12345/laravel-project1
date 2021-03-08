<?php

namespace App\Http\ViewComposers;

use App\Eloquent\CompanyType;
use App\Eloquent\GeoCountry;
use Illuminate\View\View;

class CompanyTypeComposer
{
    public function compose(View $view) {

        $companyTypes = CompanyType::all();
        return $view->with('companyTypes', $companyTypes);

    }
}