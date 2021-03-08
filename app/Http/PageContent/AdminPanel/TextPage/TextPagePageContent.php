<?php

namespace App\Http\PageContent\AdminPanel\TextPage;

use Illuminate\Support\Facades\Request;

class TextPagePageContent
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

        $content['title']  = 'Текстовые страницы';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'title'   => ['title' => 'Заголовок записи', 'sort_url' => $this->generateUrlSort($request, 'title')],

        ];

        return $content;

    }

    public function editAndCreateContent () {

        $name_method = Request::route()->getActionMethod();

        if ($name_method === 'create') {
            $title = 'Добавление Текстовой страницы';
            $post_route = 'admin.text.page.store';
        } elseif ($name_method === 'edit') {
            $title = 'Редактирование Текстовой страницы';
            $post_route = 'admin.text.page.update';
        };

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Текстовые страницы' => route('admin.text.page.index'),
            $title         => ''
        ];
        $content['post_route'] = $post_route;
        $content['name_method'] = $name_method;
        $content['page'] = 'anons';

        return $content;

    }

    public function generateUrlSort ($request, $column) {

        $base_sort_url = route('admin.text.page.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }
        $base_sort_url .= '&sort_by='.$column;
        return $base_sort_url;

    }

}
