<?php

namespace App\Http\ViewComposers;

use App\Eloquent\Anons;
use App\Eloquent\Blog;
use App\Eloquent\News;
use App\Eloquent\NewsCategory;
use App\Eloquent\Researches;
use App\Eloquent\ShopCategory;
use App\Eloquent\Topic;
use App\Eloquent\User;
use Carbon\Carbon;
use Illuminate\View\View;
use Request;

class FooterComposer
{
    public function compose(View $view) {

        $footer = array();
        $footer['category_researches'] = ShopCategory::where('show', true)
            ->with('parent')
            ->orderBy('sort', 'asc')
            ->take(5)
            ->get();
        $footer['category_news'] = NewsCategory::orderBy('sort', 'asc')
            ->where('parent_id', 0)
            ->orderBy('sort', 'asc')
            ->take(5)
            ->get();

        return $view->with('footer', $footer);

    }
}