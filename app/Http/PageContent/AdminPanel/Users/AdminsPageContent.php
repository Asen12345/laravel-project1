<?php

namespace App\Http\PageContent\AdminPanel\Users;

use Illuminate\Support\Facades\Request;

class AdminsPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Админы/Редакторы';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'email'                    => ['title' => 'Логин (e-mail)', 'sort_url' => $this->generateUrlSort($request , 'email')],
            'name'                     => ['title' => 'Имя' , 'sort_url' => $this->generateUrlSort($request , 'name')],
            'active'                   => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request , 'active')],
            'role'                     => ['title' => 'Роль' , 'sort_url' => $this->generateUrlSort($request , 'role')],
        ];
        $content['page'] = 'admins';

        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление пользователя';
            $post_route = 'admin.admins.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование пользователя';
            $post_route = 'admin.admins.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Админы/Редакторы' => route('admin.admins.index'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'admins';
        $content['url_statistic_filter'] = 'admin.admins.edit.sort';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.admins.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
