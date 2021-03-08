<?php

namespace App\Http\PageContent\AdminPanel\Comment;

use Illuminate\Support\Facades\Request;

class CommentsPageContent
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

        $content['title']  = $id === null ? 'Комментарии блогов' : 'Комментарии блога';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Блоги' => route('admin.blogs.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'title'       => ['title' => 'Заголовок записи', 'sort_url' => $this->generateUrlSort($request, $id, 'title')],
            'created_at'  => ['title' => 'Дата комментария', 'sort_url' => $this->generateUrlSort($request, $id, 'created_at')],
            'published'   => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request, $id, 'published')],
            'user'        => ['title' => 'Автор комментария', 'sort_url' => $this->generateUrlSort($request, $id, 'user')],
            'anonym'      => ['title' => 'Анонимность', 'sort_url' => $this->generateUrlSort($request, $id, 'anonym')],

        ];
        $content['page'] = $id === null ? 'comments.all' : 'comments';

        return $content;

    }

    public function editPostPageContent () {

        $title = 'Редактирование комментария';
        $post_route = 'admin.comments.update';

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Список комментариев' => route('admin.comments.all.index'),
            $content['title'] => ''
        ];
        $content['post_route'] = $post_route;
        return $content;

    }

    public function generateUrlSort ($request , $id, $column) {
        if (!is_null($id)) {
            $base_sort_url = route('admin.comments.sort', ['id' => $id]) . '?';
        } else {
            $base_sort_url = route('admin.comments.all.sort') . '?';
        }


        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
