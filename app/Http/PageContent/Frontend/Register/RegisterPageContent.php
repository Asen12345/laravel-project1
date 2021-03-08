<?php

namespace App\Http\PageContent\Frontend\Register;


use App\Eloquent\DataTemplate;
use App\Eloquent\NewsCategory;

class RegisterPageContent
{
    public function __construct() {
        //
    }

    public function registerPageContent () {

        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });
        $data_template = DataTemplate::where('page', 'register_page')->first();
        $content_page['title']                 = 'Регистрация пользователя';
        $content_page['content_form']           = $data_template;
        $content_page['crumbs']   = ['Главная' => route('front.home'), $content_page['title'] => ''];
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = '';
        return $content_page;

    }
}
