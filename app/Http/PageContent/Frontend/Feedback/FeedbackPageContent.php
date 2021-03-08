<?php

namespace App\Http\PageContent\Frontend\Feedback;


use App\Eloquent\MetaTags;

class FeedbackPageContent
{
    public function __construct() {
        //
    }

    public function feedbackPageContent () {

        $content_page['title']          = 'Обратная связь';
        $content_page['menu_active']    = '';
        $content_page['meta']           = MetaTags::where('meta_id', 'meta_feedback')->first();
        return $content_page;

    }
}
