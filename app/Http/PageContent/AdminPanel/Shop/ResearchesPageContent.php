<?php

namespace App\Http\PageContent\AdminPanel\Shop;

use Illuminate\Support\Facades\Request;

class ResearchesPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Исследования';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'title'                 => ['title' => 'Заголовок', 'sort_url' => $this->generateUrlSort($request , 'title')],
            'researches_author_id'  => ['title' => 'Автор', 'sort_url' => $this->generateUrlSort($request , 'researches_author_id')],
            'published_at'          => ['title' => 'Дата выхода', 'sort_url' => $this->generateUrlSort($request , 'published_at')],
            'price'                 => ['title' => 'Цена' , 'sort_url' => $this->generateUrlSort($request , 'price')],
            'download'              => ['title' => 'Кол-во скачиваний' , 'sort_url' => $this->generateUrlSort($request , 'download')],
            'views_count'           => ['title' => 'Кол-во просмотров' , 'sort_url' => $this->generateUrlSort($request , 'views_count')],
        ];
        return $content;

    }

    public function editAndCreateAuthorPageContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление Исследования';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Исследования';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Исследования' => route('admin.shop.researches'),
            $title         => ''
            ];
        $content['name_method'] = $name_method;

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.shop.researches.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
