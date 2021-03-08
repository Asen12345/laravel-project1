<?php

namespace App\Http\PageContent\Frontend\Home;


use App\Eloquent\MetaTags;
use App\Eloquent\NewsCategory;

class HomePageContent
{
    public function __construct() {
        //
    }

    public function indexPageContent ($category = null, $sub_category = null) {

        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        /*Meta Tags*/
        $content_page['meta'] = MetaTags::where('meta_id', 'meta_main')->first();

        $content_page['title']                 = 'Новости';
        $content_page['crumbs']   = [
            'Главная' => '',
        ];

        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'news';
        return $content_page;

    }

    private function makeCrumbs ($category = null, $sub_category = null, $crumbs, $title) {
        if (!empty($category)) {
            $crumbs = $crumbs + array('Новости' => route('front.page.news.all'));
            $crumbs = $crumbs + array($category->name => route('front.page.news.category', ['url_section' => $category->url_en]));
        }
        if (!empty($category) && !empty($sub_category)) {
            $crumbs = $crumbs + array($sub_category->name => route('front.page.news.category', ['url_section' => $category->url_en, 'url_sub_section' => $sub_category->url_en]));
        } else {
            $crumbs = $crumbs + array($title => '');
        }
        return $crumbs;
    }
}
