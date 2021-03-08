<?php

namespace App\Http\PageContent\Frontend\People\Expert;


use App\Eloquent\NewsCategory;

class ExpertPageContent
{
    public function __construct() {
        //
    }

    public function expertsPageContent ($request) {

        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        $content_page['title']                 = 'Список экспертов';
        $content_page['crumbs']                = ['Главная' => route('front.home'), $content_page['title'] => ''];
        $content_page['fields']                = $this->field($request);
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'expert';
        return $content_page;

    }

    public function expertPageContent ($expert = null) {
        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });
        $content_page['title']                 = $expert->name;
        $content_page['crumbs']                = ['Главная' => route('front.home'), 'Список экспертов' => route('front.page.people.experts.index'), $content_page['title'] => ''];
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'expert';

        return $content_page;

    }

    private function field($request){
        $content_page = [
            'name'             => ['title' => 'фамилия имя', 'sort_url' => $this->generateUrlSort($request, 'name')],
            'news_count'       => ['title' => 'новости', 'sort_url' => $this->generateUrlSort($request, 'news_count')],
            'comments_count'   => ['title' => 'комментарии', 'sort_url' => $this->generateUrlSort($request, 'comments_count')],
            'posts_count'      => ['title' => 'блоги', 'sort_url' => $this->generateUrlSort($request, 'posts_count')],
            'last_login_at'    => ['title' => 'последнее посещение', 'sort_url' => $this->generateUrlSort($request, 'last_login_at')],

        ];
        return $content_page;
    }
    public function generateUrlSort ($request, $column) {

        $base_sort_url = route('front.page.people.experts.index') . '?';

        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }
}
