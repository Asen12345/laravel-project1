<?php

namespace App\Http\PageContent\AdminPanel\Blog;

use Illuminate\Support\Facades\Request;

class BlogPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Блоги';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'subject'                    => ['title' => 'Название блога', 'sort_url' => $this->generateUrlSort($request , 'subject')],
            'permission'                 => ['title' => 'Тип', 'sort_url' => $this->generateUrlSort($request , 'permission')],
            'active'                     => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request , 'active')],
            'posts'                      => ['title' => 'Кол-во записей', 'sort_url' => $this->generateUrlSort($request , 'posts')],
            'rating'                     => ['title' => 'Рейтинг', 'sort_url' => $this->generateUrlSort($request , 'rating')],

        ];
        $content['page'] = 'blogs';

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

        $base_sort_url = route('admin.blogs.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
