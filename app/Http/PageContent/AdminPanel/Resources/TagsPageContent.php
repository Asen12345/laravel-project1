<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class TagsPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Теги';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'                 => ['title' => 'Имя', 'sort_url' => $this->generateUrlSort($request , 'name')],
        ];
        //$content['page'] = 'admins';

        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление Тега';
            $post_route = 'resources.tags';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Тега';
            $post_route = 'resources.tags';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Теги' => route('admin.resources.tags'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'admins';
        //$content['url_statistic_filter'] = 'admin.admins.edit.sort';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.resources.tags.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
