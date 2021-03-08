<?php

namespace App\Http\PageContent\Frontend\Topic;


use App\Eloquent\MetaTags;
use App\Eloquent\Topic;
use App\Eloquent\NewsCategory;

class TopicPageContent
{
    public function __construct() {
        //
    }

    public function indexContent () {

        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        $content_page['title']                 = 'Темы дня';
        $content_page['crumbs']                = ['Главная' => route('front.home'), $content_page['title'] => ''];
        $content_page['categories_collection'] = $categories;
        $content_page['meta'] = MetaTags::where('meta_id', 'meta_anons')->first();
        $content_page['menu_active']           = 'topic';
        return $content_page;

    }

    public function pageContent (Topic $anons) {

        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        $content_page['title']                 = $anons->title;
        $content_page['meta']                  = $anons;
        $content_page['categories_collection'] = $categories;
        $content_page['crumbs']                = [
            'Главная'                  => route('front.home'),
            'Темы'                     => route('front.page.topic'),
            $content_page['title']     => ''];
        $content_page['menu_active']           = 'topic';
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
