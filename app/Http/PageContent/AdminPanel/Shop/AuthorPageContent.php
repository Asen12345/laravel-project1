<?php

namespace App\Http\PageContent\AdminPanel\Shop;

use Illuminate\Support\Facades\Request;

class AuthorPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Авторы исследований';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'title'            => ['title' => 'Заголовок', 'sort_url' => $this->generateUrlSort($request , 'title')],
            'researches_count' => ['title' => 'Кол-во исследований' , 'sort_url' => $this->generateUrlSort($request , 'researches_count')],
        ];
        return $content;

    }

    public function editAndCreateAuthorPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление автора';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование автора';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'            => route('admin.dashboard.index'),
            'Авторы исследований' => route('admin.shop.researches.authors'),
            $title               => ''
            ];
        $content['name_method'] = $name_method;

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.shop.researches.authors.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
