<?php

namespace App\Http\PageContent\Frontend\News;


use App\Eloquent\MetaTags;
use App\Eloquent\NewsCategory;

class NewsPageContent
{
    public function __construct() {
        //
    }

    public function newsPageContent ($category = null, $sub_category = null, $scene = null, $news = null) {

        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')
            ->where('parent_id', 0)
            ->with('child')
            ->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        /*Meta Tags*/
        if ($category !== null && $sub_category !== null) {
            $content_page['meta'] = $sub_category;
        } elseif ($category !== null && !isset($news)) {
            $content_page['meta'] = $category;
        } elseif ($scene !== null){
            $content_page['meta'] = $scene;
        } elseif (!isset($news)) {
            $content_page['meta'] = MetaTags::where('meta_id', 'meta_news')->first();
        } else {
            $content_page['meta'] = ['meta_title' => $news->title, 'meta_description' => $news->announce];
        }

        $content_page['title']                 = 'Новости';

        $crumbs_home = ['Главная' => route('front.home')];
        $content_page['crumbs']   = $this->makeCrumbs($category, $sub_category, $crumbs_home, $content_page['title'] , $news);
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'news';
        return $content_page;

    }

	/* Добавление новости */
    public function addNewsPage()
    {
        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        /*Meta Tags*/

        $content_page['meta']     = [
            'meta_title' => 'Добавление анонимной новости',
            'meta_description' => 'Добавление анонимной новости'
        ];

		/* крошки */
        $content_page['title']    = 'Новости';

        $content_page['crumbs']   = [
            'Главная'               => route('front.home'),
            $content_page['title']  => route('front.page.news.all'),
            'Добавить новость'      => ''
        ];
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'news';
        return $content_page;
    }
	/* end Добавление новости */

    public function searchResult()
    {
        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        //$content_page['meta'] = MetaTags::where('meta_id', 'meta_news')->first();

        $content_page['title']                 = 'Результат поиска';

        $content_page['crumbs']   =  [
            'Главная' => route('front.home'),
            'Результат поиска' => ''
        ];
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'news';
        return $content_page;

    }


    private function makeCrumbs ($category, $sub_category, $crumbs, $title, $news) {
        if (!empty($category) && empty($sub_category)) {
            $crumbs = $crumbs + array('Новости' => route('front.page.news.all'));
            if (!empty($news)) {
                $crumbs = $crumbs + array($category->name => route('front.page.news.category', ['url_section' => $category->url_en]));
                $crumbs = $crumbs + array($news->name => '');
            } else {
                $crumbs = $crumbs + array($category->name => '');
            }
        }
        if (!empty($category) && !empty($sub_category)) {
            $crumbs = $crumbs + array('Новости' => route('front.page.news.all'));
            $crumbs = $crumbs + array($category->name => route('front.page.news.category', ['url_section' => $category->url_en]));
            if (!empty($news)) {
                $crumbs = $crumbs + array($sub_category->name => route('front.page.news.category', ['url_section' => $category->url_en, 'url_sub_section' => $sub_category->url_en]));
                $crumbs = $crumbs + array($news->name => '');
            } else {
                $crumbs = $crumbs + array($sub_category->name => '');
            }
        } else {
            $crumbs = $crumbs + array($title => '');
        }
        return $crumbs;
    }
}
