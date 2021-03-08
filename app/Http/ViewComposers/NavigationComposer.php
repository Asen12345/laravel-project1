<?php

namespace App\Http\ViewComposers;

use App\Eloquent\Anons;
use App\Eloquent\Blog;
use App\Eloquent\News;
use App\Eloquent\NewsCategory;
use App\Eloquent\Researches;
use App\Eloquent\ShopCategory;
use App\Eloquent\Topic;
use App\Eloquent\User;
use Carbon\Carbon;
use Illuminate\View\View;
use Request;

class NavigationComposer
{
    public function compose(View $view) {

        $header_menu = array();
        $header_menu['news'] = [
            'name'        => 'Новости',
            'route'       => 'page.news.all',
            'total'       => News::where('published', true)->count(),
            'sections'    => NewsCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get(),
        ];
        $header_menu['blogs'] = [
            'active'   => false,
            'name'     => 'Блоги',
            'route'    => 'page.blogs.all',
            'total'    => Blog::where('active', true)->count(),
            'sections' => [
                ['name' => 'Личные блоги', 'url_en' => route('front.page.blogs.all', ['sort' => 'expert'])],
                ['name' => 'Корпоративные блоги', 'url_en' => route('front.page.blogs.all', ['sort' => 'company'])],
				['name' => 'Все записи', 'url_en' => route('front.page.posts.all')],
            ]
        ];
        $header_menu['shop'] = [
            'active'   => false,
            'name'     => 'Магазин исследований',
            'route'    => '', //Указываем в представлении
            'total'    => Researches::whereNotNull('id')->count(),
            'sections' => [
                ['name' => 'Все исследования', 'url_en' => route('front.page.shop')],
                ['name' => 'Авторы исследований', 'url_en' => route('front.page.shop.researches.authors')],
            ],
        ];
        $header_menu['expert'] = [
            'name'        => 'Эксперты',
            'route'       => 'page.people.experts.index',
            'total'       => User::where('permission', 'expert')->where('active', true)->count(),
            'sections'    => [],
        ];
        $header_menu['company'] = [
            'name'     => 'Компании',
            'route'    => 'page.people.companies.index',
            'total'    => User::where('permission', 'company')->where('active', true)->count(),
            'sections' => [],
        ];
        $header_menu['anons'] = [
            'active'   => false,
            'name'     => 'Мероприятия',
            'route'    => 'page.anons',
            'total'    => Anons::where('will_end', '>=', Carbon::now())->count(),
            'sections' => []
        ];
        $header_menu['topic'] = [
            'active'   => false,
            'name'     => 'Тема дня',
            'route'    => 'page.topic',
            'total'    => Topic::where('published',true)->count(),
            'sections' => []
        ];

        return $view->with('header_menu', $header_menu);

    }
}