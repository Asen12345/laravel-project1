<?php

namespace App\Http\PageContent\AdminPanel\News;

use Illuminate\Support\Facades\Request;

class NewsPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Новости';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'                       => ['title' => 'Название новости', 'sort_url' => $this->generateUrlSort($request , 'name')],
            'announce'                   => ['title' => 'Анонс', 'sort_url' => $this->generateUrlSort($request , 'announce')],
            'published'                  => ['title' => 'Активная новость', 'sort_url' => $this->generateUrlSort($request , 'published')],
            'news_category_id'           => ['title' => 'Категория', 'sort_url' => $this->generateUrlSort($request , 'news_category_id')],
            'author_user_id'             => ['title' => 'Разместивший новость', 'sort_url' => $this->generateUrlSort($request , 'author_user_id')],
            'created_at'                 => ['title' => 'Создано' , 'sort_url' => $this->generateUrlSort($request , 'created_at')],
        ];
        $content['page'] = 'news';
        return $content;

    }

    public function editAndCreateUserPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление новости';
            $post_route = 'admin.news.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование новости';
            $post_route = 'admin.news.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Новости' => route('admin.news.index'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'news';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.news.sort') . '?';
        foreach ($request as $key => $valFilter) {
            if ($key == 'date'){
                foreach ($valFilter as $k => $item) {
                    $base_sort_url .= '&date['. $k .']=' . $item;
                }
            } else {
                $base_sort_url .= '&'.$key.'=' . $valFilter;
            }

        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
