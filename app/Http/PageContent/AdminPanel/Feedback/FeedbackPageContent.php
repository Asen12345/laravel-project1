<?php

namespace App\Http\PageContent\AdminPanel\Feedback;

use Illuminate\Support\Facades\Request;

class FeedbackPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Темы сообщений';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'subject' => ['title' => 'Тема', 'sort_url' => $this->generateUrlSort($request , 'subject')],
            'email'   => ['title' => 'Email', 'sort_url' => $this->generateUrlSort($request , 'email')],
        ];
        //$content['page'] = 'admins';

        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление Темы сообщений';
            $post_route = 'admin.feedback.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Темы сообщений';
            $post_route = 'admin.feedback.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'        => route('admin.dashboard.index'),
            'Темы сообщений' => route('admin.feedback.index'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.feedback.index') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
