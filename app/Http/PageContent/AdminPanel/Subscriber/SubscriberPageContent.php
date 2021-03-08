<?php

namespace App\Http\PageContent\AdminPanel\Subscriber;

use Illuminate\Support\Facades\Request;

class SubscriberPageContent
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

        $content['title']  = 'Подписчики блогов';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Блоги' => route('admin.blogs.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'subject'       => ['title' => 'Заголовок блога', 'sort_url' => $this->generateUrlSort($request, 'subject')],
            'active'      => ['title' => 'Активность', 'sort_url' => $this->generateUrlSort($request,'active')],
            'email'       => ['title' => 'Email', 'sort_url' => $this->generateUrlSort($request, 'email')],

        ];
        $content['page'] = 'subscriber';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.subscriber.sort') . '?';

        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
