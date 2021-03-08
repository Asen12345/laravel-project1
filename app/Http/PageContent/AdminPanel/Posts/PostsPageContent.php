<?php

namespace App\Http\PageContent\AdminPanel\Posts;

use Illuminate\Support\Facades\Request;

class PostsPageContent
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

        $content['title']  = $id === null ? 'Записи блогов' : 'Записи блога';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Блоги' => route('admin.blogs.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'title'       => ['title' => 'Заголовок записи', 'sort_url' => $this->generateUrlSort($request, $id, 'title')],
            'subject'     => ['title' => 'Заголовок блога', 'sort_url' => $this->generateUrlSort($request, $id, 'subject')],
            'published'   => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request, $id, 'published')],
            'comments'    => ['title' => 'Кол-во комментариев', 'sort_url' => $this->generateUrlSort($request, $id, 'comments')],
            'subscribers_count' => ['title' => 'Кол-во подписчиков', 'sort_url' => $this->generateUrlSort($request, $id, 'subscribers_count')],

        ];
        $content['page'] = $id === null ? 'posts.all' : 'posts';

        return $content;

    }

    public function editPostPageContent () {

        $title = 'Редактирование поста';
        //$post_route = 'admin.users.update';

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Блоги' => route('admin.blogs.index'),
            $content['title'] => ''
        ];
        //$content['post_route'] = $post_route;
        return $content;

    }

    public function createPostPageContent () {

        $title = 'Создание поста';
        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Блоги' => route('admin.blogs.index'),
            $content['title'] => ''
        ];
        return $content;

    }

    public function generateUrlSort ($request , $id, $column) {
        if (!is_null($id)) {
            $base_sort_url = route('admin.posts.sort', ['id' => $id]) . '?';
        } else {
            $base_sort_url = route('admin.posts.all.sort') . '?';
        }


        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
