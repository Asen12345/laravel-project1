<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class CompanyTypePageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Сферы деятельности компаний';
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
            $title = 'Добавление Сферы деятельности';
            $post_route = 'resources.company.type';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Сферы деятельности';
            $post_route = 'resources.company.type';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Сферы деятельности' => route('admin.resources.company.type'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'admins';
        //$content['url_statistic_filter'] = 'admin.admins.edit.sort';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.resources.company.type.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
