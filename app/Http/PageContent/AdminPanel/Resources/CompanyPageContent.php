<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class CompanyPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Компании';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'          => ['title' => 'Заголовок', 'sort_url' => $this->generateUrlSort($request , 'name')],
            'type_count'    => ['title' => 'Тип компании', 'sort_url' => $this->generateUrlSort($request , 'type_count')],
            'users_count'   => ['title' => 'Кол-во сотрудников', 'sort_url' => $this->generateUrlSort($request , 'users_count')],
        ];
        //$content['page'] = 'admins';

        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление Компании';
            $post_route = 'resources.company.type';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Компании';
            $post_route = 'resources.company.type';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Компании'     => route('admin.resources.company'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'admins';
        //$content['url_statistic_filter'] = 'admin.admins.edit.sort';

        return $content;

    }

    public function mergePageContent(){

        $title = 'Объединение Компаний';
        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Компании'     => route('admin.resources.company'),
            $title         => ''
        ];
        $content['page'] = 'admins';

        return $content;
    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.resources.company.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
