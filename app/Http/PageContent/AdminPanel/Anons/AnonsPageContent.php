<?php

namespace App\Http\PageContent\AdminPanel\Anons;

use Illuminate\Support\Facades\Request;

class AnonsPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Мероприятия';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'title'   => ['title' => 'Заголовок записи', 'sort_url' => $this->generateUrlSort($request, 'title')],
            'main'    => ['title' => 'На главной', 'sort_url' => $this->generateUrlSort($request, 'main')],

        ];
        $content['page'] = 'anons';

        return $content;

    }

    public function editAndCreateContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление мероприятия';
            $post_route = 'admin.anons.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование мероприятия';
            $post_route = 'admin.anons.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Мероприятия' => route('admin.anons.index'),
            $title         => ''
        ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'anons';

        return $content;

    }

    public function generateUrlSort ($request, $column) {

        $base_sort_url = route('admin.anons.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }
        $base_sort_url .= '&sort_by='.$column;
        return $base_sort_url;

    }

}
