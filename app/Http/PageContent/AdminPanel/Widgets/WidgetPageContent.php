<?php

namespace App\Http\PageContent\AdminPanel\Widgets;

use Illuminate\Support\Facades\Request;

class WidgetPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Виджеты';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'name'              => [
                'title' => 'Название',
                'sort_url' => $this->generateUrlSort($request, 'name')
            ],
            'published'         => [
                'title' => 'Опубликовано',
                'sort_url' => $this->generateUrlSort($request, 'published')
            ],

        ];

        return $content;

    }

    public function editAndCreateContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление Виджета';
            $post_route = 'admin.widget.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Виджета';
            $post_route = 'admin.widget.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Виджеты' => route('admin.widgets.index'),
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

        $base_sort_url = route('admin.widgets.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }
        $base_sort_url .= '&sort_by='.$column;
        return $base_sort_url;

    }

}
