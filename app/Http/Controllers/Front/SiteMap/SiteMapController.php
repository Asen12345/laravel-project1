<?php

namespace App\Http\Controllers\Front\SiteMap;

use App\Eloquent\NewsCategory;
use App\Eloquent\Researches;
use App\Eloquent\ShopCategory;
use App\Eloquent\TextPage;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\SiteMap\SiteMapPageContent;

class SiteMapController extends Controller
{
    public function index()
    {
        $textPages    = TextPage::where('published', true)->get();
        $newsCategory = NewsCategory::withCount(['news' => function($query){
            $query->where('published', true);
        }])->get();
        $shopCategoryResearches  = ShopCategory::with(['researches' => function ($query) {
            $query->orderBy('published_at', 'DESC');
        }])
        ->get();


        return view('front.page.site_map.site_map_page', [
            'textPages'      => $textPages,
            'newsCategory'   => $newsCategory,
            'shopCategoryResearches' => $shopCategoryResearches,
            'content_page'   => (new SiteMapPageContent())->indexPageContent()
        ]);
    }
}
