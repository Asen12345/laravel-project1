<?php

namespace App\Http\PageContent\AdminPanel\Shop;

use Illuminate\Support\Facades\Request;

class CategoryPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Категории исследований';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'                       => ['title' => 'Название категории', 'sort_url' => $this->generateUrlSort($request , 'name')],
            /*'sort'                       => ['title' => 'Сортировка', 'sort_url' => $this->generateUrlSort($request , 'sort')],*/
            'child_count'                      => ['title' => 'Кол-во вложенных' , 'sort_url' => $this->generateUrlSort($request , 'child_count')],
        ];
        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление категории';
            $post_route = 'admin.category.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование категории';
            $post_route = 'admin.category.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Категории исследований' => route('admin.shop.researches.category'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'category';
        //$content['url_statistic_filter'] = 'admin.users.edit.sort';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.shop.researches.category.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
