<?php

namespace App\Http\PageContent\Frontend\Setting;


use App\Eloquent\NewsCategory;

class AccountPageContent
{

    private $user;

    public function __construct() {
        $this->user = auth()->user();
    }

    public function mainPageContent () {

        $content_page['title']                 = 'Настройки профиля ' . $this->user->name;
        $content_page['crumbs']                = ['Главная' => route('front.home'), $content_page['title'] => ''];
        $content_page['categories_collection'] = $this->makeCategory();
        $content_page['menu_active']           = '';

        return $content_page;

    }

    public function createPostPageContent() {
        $content_page['title']                 = 'Добавление записи';
        $content_page['crumbs']                = [
            'Главная' => route('front.home'),
            'Настройки профиля ' . $this->user->name => route('front.setting.account'),
            $content_page['title'] => ''
        ];
        $content_page['categories_collection'] = $this->makeCategory();
        $content_page['menu_active']           = '';

        return $content_page;
    }

    public function editPostPageContent() {
        $content_page['title']                 = 'Редактирование записи';
        $content_page['crumbs']                = [
            'Главная' => route('front.home'),
            'Настройки профиля ' . $this->user->name => route('front.setting.account'),
            $content_page['title'] => ''
        ];
        $content_page['categories_collection'] = $this->makeCategory();
        $content_page['menu_active']           = '';

        return $content_page;
    }

    public function makeCategory() {
        /*Make categories collection with child categories*/
        $categories = NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });
        return $categories;
    }
}
