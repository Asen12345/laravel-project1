<?php

namespace App\Http\PageContent\AdminPanel\Topic;

use Illuminate\Support\Facades\Request;

class AnswerPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function homePageContent ($request, $id) {

        $content['title']  = $id === null ? 'Ответы на темы' : 'Ответы темы';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Темы дня' => route('admin.topic.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'user'       => ['title' => 'Автор ответа', 'sort_url' => $this->generateUrlSort($request, $id, 'user')],
            'created_at'  => ['title' => 'Дата создания', 'sort_url' => $this->generateUrlSort($request, $id, 'created_at')],
            'published'   => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request, $id, 'published')],
            'title'        => ['title' => 'Заголовок темы', 'sort_url' => $this->generateUrlSort($request, $id, 'title')],

        ];
        $content['page'] = 'answer';

        return $content;

    }

    public function editAndCreateContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление ответа';
            $post_route = 'admin.answer.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование ответа';
            $post_route = 'admin.answer.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Темы дня'     => route('admin.topic.index'),
			'Ответы'       => route('admin.answer.index'),
            $title         => ''
        ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'answer';

        return $content;

    }

    public function generateUrlSort ($request , $id, $column) {
         $base_sort_url = route('admin.answer.sort') . '?';

        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
