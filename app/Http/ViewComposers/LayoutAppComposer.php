<?php

namespace App\Http\ViewComposers;

use App\Eloquent\Topic;
use Illuminate\View\View;

class LayoutAppComposer
{
    public function compose(View $view) {

        $dayTopic = Topic::where('published', true)->where('main_topic', true)->first();
        return $view->with('dayTopic', $dayTopic);

    }
}