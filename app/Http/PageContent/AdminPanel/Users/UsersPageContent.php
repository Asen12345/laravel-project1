<?php

namespace App\Http\PageContent\AdminPanel\Users;

use Illuminate\Support\Facades\Request;

class UsersPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Эксперты/Компании';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'                       => ['title' => 'ФИО/Название компании', 'sort_url' => $this->generateUrlSort($request , 'name')],
            'email'                      => ['title' => 'Логин (e-mail)', 'sort_url' => $this->generateUrlSort($request , 'email')],
            'active'                     => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request , 'active')],
            'block'                      => ['title' => 'Блокировка', 'sort_url' => $this->generateUrlSort($request , 'block')],
            'notifications_subscribed'   => ['title' => 'Новостная рассылка', 'sort_url' => $this->generateUrlSort($request , 'notifications_subscribed')],
            'invitations'                => ['title' => 'Уведомления и приглашения', 'sort_url' => $this->generateUrlSort($request , 'invitations')],
            'permission'                 => ['title' => 'Роль' , 'sort_url' => $this->generateUrlSort($request , 'permission')],
        ];
        $content['page'] = 'users';

        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление пользователя';
            $post_route = 'admin.users.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование пользователя';
            $post_route = 'admin.users.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Эксперты/Компании' => route('admin.users.index'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'users';
        $content['url_statistic_filter'] = 'admin.users.edit.sort';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.users.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
