<?php

namespace App\Http\PageContent\AdminPanel\News;

use Illuminate\Support\Facades\Request;

class ScenePageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Сюжеты';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'                       => ['title' => 'Название сюжета', 'sort_url' => $this->generateUrlSort($request , 'name')],
            'news_scene_group_id'        => ['title' => 'Сюжетная группа', 'sort_url' => $this->generateUrlSort($request , 'news_scene_group_id')],
            'created_at'                 => ['title' => 'Создано' , 'sort_url' => $this->generateUrlSort($request , 'created_at')],
        ];
        $content['page'] = 'scene';
        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление сюжета';
            $post_route = 'admin.scene.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование сюжета';
            $post_route = 'admin.scene.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Сюжеты' => route('admin.scene.index'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'scene';
        //$content['url_statistic_filter'] = 'admin.users.edit.sort';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.scene.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
