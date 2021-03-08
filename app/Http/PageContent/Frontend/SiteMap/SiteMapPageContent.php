<?php

namespace App\Http\PageContent\Frontend\SiteMap;


class SiteMapPageContent
{
    public function __construct() {
        //
    }

    public function indexPageContent () {

        $content_page['title']                 = 'Карта сайта';
        $content_page['crumbs']   = ['Главная' => route('front.home'), $content_page['title'] => ''];
        $content_page['menu_active']           = '';
        return $content_page;

    }
}
