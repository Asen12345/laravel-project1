<?php

namespace App\Http\PageContent\Frontend\TextPage;


class TextPageContent
{
    public function __construct() {
        //
    }

    public function indexPageContent ($page) {

        $content_page['title']          = $page->title;
        $content_page['meta']           = $page;
        $content_page['crumbs']         = null;
        $content_page['menu_active']    = '';
        return $content_page;

    }
}
