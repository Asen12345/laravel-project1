<?php

namespace App\Http\PageContent\AdminPanel\Newsletter;

use Illuminate\Support\Facades\Request;

class NewsletterPageContent
{
    public function __construct() {
        //
    }

    /**
     * @return mixed
     */
    public function settingPageContent ()
    {
        $content['title']  = 'Настройка рассылки';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        $content['weekday'] = [
          'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'
        ];

        return $content;

    }

    public function templateShowPageContent ()
    {
        $content['title']  = 'Просмотр шаблона';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];

        return $content;

    }

    public function showNewsletter ()
    {
        $content['title']  = 'Просмотр рассылки';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];

        return $content;
    }

    public function adsOffersPageContent()
    {
        $content['title']  = 'Объявления и предложения';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        return $content;
    }

    public function blogPostContent($request)
    {
        $content['title']  = 'Новости блогов';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'name'          => ['title' => 'Заголовок блога', 'sort_url' => $this->generateUrlSort($request, 'name')],
            'title'         => ['title' => 'Заголовок записи', 'sort_url' => $this->generateUrlSort($request, 'title')],
            'created_at'    => ['title' => 'Дата размещения', 'sort_url' => $this->generateUrlSort($request, 'created_at')],
            'to_newsletter' => ['title' => 'В рассылку', 'sort_url' => $this->generateUrlSort($request, 'to_newsletter')],

        ];
        return $content;
    }

    public function createSubscriberPageContent()
    {
        $content['title']  = 'Подписать пользователя';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];

        return $content;
    }

    public function indexSubscriberPageContent () {

        $content['title']  = 'Подписанные пользователи';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['route'] = 'subscribers';

        return $content;

    }

    public function indexUnsubscribedPageContent () {

        $content['title']  = 'Отписанные пользователи';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['route'] = 'unsubscribed';

        return $content;

    }

    public function generateUrlSort ($request, $column)
    {
        $base_sort_url = route('admin.newsletter.news.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }
        $base_sort_url .= '&sort_by='.$column;
        return $base_sort_url;

    }

}
