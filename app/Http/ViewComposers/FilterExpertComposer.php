<?php

namespace App\Http\ViewComposers;

use App\Eloquent\Company;
use App\Eloquent\CompanyType;
use App\Eloquent\GeoCountry;
use Illuminate\View\View;

class FilterExpertComposer
{
    public function compose(View $view) {

        $filter_content = array();
        $filter_content['company_types'] = CompanyType::select('id', 'name')->get();
        $filter_content['geo_country'] = GeoCountry::where('hidden', false)->orderBy('position', 'asc')->get();
        $filter_content['company'] = Company::whereHas('users')->get();

        //$filter_content['geo_city'] = GeoCity::select('id', 'title')->get()->toArray();

        return $view->with('filter_content', $filter_content);

    }
}