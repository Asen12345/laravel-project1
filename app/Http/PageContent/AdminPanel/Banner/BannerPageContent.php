<?php

namespace App\Http\PageContent\AdminPanel\Banner;

use Illuminate\Support\Facades\Request;

class BannerPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Баннеры';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'name'              => [
                'title' => 'Название',
                'sort_url' => $this->generateUrlSort($request, 'name')
            ],
            'banner_place_id'   => [
                'title' => 'Расположение',
                'sort_url' => $this->generateUrlSort($request, 'banner_place_id')
            ],
            'published'         => [
                'title' => 'Опубликовано',
                'sort_url' => $this->generateUrlSort($request, 'published')
            ],
            'views_count' => [
                'title' => 'Просмотров',
                'sort_url' => $this->generateUrlSort($request, 'views_count')
            ],
            'click'             => [
                'title' => 'Кликов',
                'sort_url' => $this->generateUrlSort($request, 'click')
            ],

        ];

        return $content;

    }

    public function editAndCreateContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление Баннера';
            $post_route = 'admin.text.page.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Баннера';
            $post_route = 'admin.text.page.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Банеры' => route('admin.banner.index'),
            $title         => ''
        ];
        $content['post_route'] = $post_route;

        return $content;

    }

    public function settingPageContent () {

        $title = 'Настройка отображения банеров';

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Банеры' => route('admin.banner.index'),
            $title         => ''
        ];

        return $content;
    }

    public function generateUrlSort ($request, $column) {

        $base_sort_url = route('admin.banner.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }
        $base_sort_url .= '&sort_by='.$column;
        return $base_sort_url;

    }

}
