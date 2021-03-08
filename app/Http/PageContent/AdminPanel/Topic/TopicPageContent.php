<?php

namespace App\Http\PageContent\AdminPanel\Topic;

use Illuminate\Support\Facades\Request;

class TopicPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Все темы';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'title'      => ['title' => 'Заголовок темы', 'sort_url' => $this->generateUrlSort($request, 'title')],
            'published_at' => ['title' => 'Дата создания', 'sort_url' => $this->generateUrlSort($request, 'published_at')],
            'published' => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request, 'published')],
            'answers_count' => ['title' => 'Кол-во ответов', 'sort_url' => $this->generateUrlSort($request, 'answers_count')],
            'main_topic' => ['title' => 'На главной', 'sort_url' => $this->generateUrlSort($request, 'main_topic')],

        ];
        $content['page'] = 'topic';

        return $content;

    }

    public function editAndCreateContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление темы';
            $post_route = 'admin.topic.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование темы';
            $post_route = 'admin.topic.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Темы дня' 	   => route('admin.topic.index'),
            $title         => ''
        ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'topic';

        return $content;

    }

    public function generateUrlSort ($request, $column) {

        $base_sort_url = route('admin.topic.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }
        $base_sort_url .= '&sort_by='.$column;
        return $base_sort_url;

    }

}
