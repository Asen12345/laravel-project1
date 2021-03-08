<?php

namespace App\Http\ViewComposers;

use App\Eloquent\CompanyType;
use App\Eloquent\GeoCountry;
use App\Eloquent\NewsCategory;
use App\Eloquent\NewsSceneGroup;
use Illuminate\View\View;

class NewsSceneSidebarComposer
{
    public function compose(View $view) {


        $newsCategory = NewsCategory::where('parent_id', false)->get();
        $sceneGroup = NewsSceneGroup::with('newsScene')->orderBy('sort')->get();

        return $view->with([
            'companyTypes' => $newsCategory,
            'sceneGroup'   => $sceneGroup,
        ]);

    }
}