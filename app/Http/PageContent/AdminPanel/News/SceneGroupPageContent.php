<?php

namespace App\Http\PageContent\AdminPanel\News;

use Illuminate\Support\Facades\Request;

class SceneGroupPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Сюжетные группы';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'                       => ['title' => 'Название сюжета', 'sort_url' => $this->generateUrlSort($request , 'name')],
            'sort'                       => ['title' => 'Сортировка', 'sort_url' => $this->generateUrlSort($request , 'sort')],
            'created_at'                 => ['title' => 'Создано' , 'sort_url' => $this->generateUrlSort($request , 'created_at')],
        ];
        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Новая Сюжетная группа';
            $post_route = 'admin.scene-group.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Сюжетной группы';
            $post_route = 'admin.scene-group.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Сюжетные группы' => route('admin.scene-group.index'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'category';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.scene-group.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
