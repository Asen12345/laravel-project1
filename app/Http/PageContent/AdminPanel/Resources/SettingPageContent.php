<?php

namespace App\Http\PageContent\AdminPanel\Resources;


use Illuminate\Support\Facades\Request;

class SettingPageContent
{
    public function __construct()
    {
        //
    }

    public function templateMailPageContent () {

        $content['title']  = 'Шаблоны писем';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];

        return $content;

    }

    public function editPageContent($id) {

        if ($id === 'user_register') {
            $content['tags_use'] = ['Имя' => '{{ $name }}', 'Email' => '{{ $email }}', 'Роль' => '{{ $permission }}', 'Пароль' => '{{ $password }}'];
        } elseif ($id === 'user_approval') {
            $content['tags_use'] = ['Имя' => '{{ $name }}', 'Email' => '{{ $email }}', 'Роль' => '{{ $permission }}', 'Пароль' => '{{ $password }}'];
        } elseif ($id === 'forgot_password_link') {
            $content['tags_use'] = ['Имя' => '{{ $name }}', 'Email' => '{{ $email }}', 'Роль' => '{{ $permission }}', 'Ссылка' => '{!! $link !!}'];
        } elseif ($id === 'forgot_password_new_password') {
            $content['tags_use'] = ['Имя' => '{{ $name }}', 'Email' => '{{ $email }}', 'Роль' => '{{ $permission }}', 'Пароль' => '{{ $password }}', 'Ссылка' => '{!! $link !!}'];
        } elseif ($id === 'change_password_from_admin') {
            $content['tags_use'] = ['Имя' => '{{ $name }}', 'Email' => '{{ $email }}', 'Роль' => '{{ $permission }}', 'Пароль' => '{{ $password }}', 'Ссылка' => '{!! $link !!}'];
        } elseif ($id === 'new_blog_admin') {
            $content['tags_use'] = ['Заголовок блога' => '{{ $title }}', 'Имя пользователя' => '{{ $user_name }}', 'Ссылка' => '{!! $link !!}'];
        } elseif ($id === 'blog_activate_admin') {
            $content['tags_use'] = ['Заголовок блога' => '{{ $title }}', 'Имя пользователя' => '{{ $user_name }}', 'Ссылка' => '{!! $link !!}'];
        } elseif ($id === 'new_news_admin') {
            $content['tags_use'] = ['Заголовок новости' => '{{ $title }}', 'Имя пользователя' => '{{ $user_name }}', 'Ссылка' => '{!! $link !!}'];
        } elseif ($id === 'news_activate_admin') {
            $content['tags_use'] = ['Заголовок новости' => '{{ $title }}', 'Имя пользователя' => '{{ $user_name }}', 'Ссылка' => '{!! $link !!}'];
        } elseif ($id === 'new_post') {
            $content['tags_use'] = ['Заголовок поста' => '{{ $title }}', 'Заголовок блога' => '{{ $blog }}', 'Анонс поста' => '{{ $anons }}', 'Читать далее' => '{!! $link !!}', 'Ссылка для отписки' => '{!! $unsubscribe !!}'];
        } elseif ($id === 'new_message') {
            $content['tags_use'] = ['Тема сообщения' => '{{ $subject_message }}', 'Текст сообщения' => '{{ $message_body }}', 'Имя отправившего' => '{{ $user_from }}', 'Имя кому отправлено' => '{{ $user_to }}', 'Ссылка на сообщение' => '{!! $link !!}'];
        } elseif ($id === 'new_message_from_admin') {
            $content['tags_use'] = ['Тема сообщения' => '{{ $subject }}', 'Текст сообщения' => '{!! $message_body !!}', 'Имя отправившего' => '{{ $user_from }}', 'Имя кому отправлено' => '{{ $user_to }}', 'Ссылка на сообщение' => '{!! $link !!}'];
        } elseif ($id === 'new_comment_in_blog') {
            $content['tags_use'] = ['Название блога' => '{{ $blog_name }}', 'Название поста' => '{{ $post_name }}', 'Имя отправившего' => '{{ $user_from }}', 'Имя кому отправлено' => '{{ $user_to }}', 'Ссылка на комментарий' => '{!! $link !!}'];
        } elseif ($id === 'topic_subscriber') {
            $content['tags_use'] = ['Название темы' => '{{ $title }}', 'Имя кому отправлено' => '{{ $to_name }}', 'Ссылка на комментарий' => '{!! $link !!}'];
        } elseif ($id === 'new_friend') {
            $content['tags_use'] = ['Имя кто отправил' => '{{ $user_from }}', 'Имя кому отправлено' => '{{ $user_to }}', 'Ссылка на просмотр' => '{!! $link !!}'];
        } elseif ($id === 'feedback') {
            $content['tags_use'] = ['Имя кто отправил' => '{{ $user_from }}', 'Email пользователя' => '{{ $email }}', 'Название продукта' => '{{ $product }}', 'Текст' => '{{ $text }}', 'Ссылка на просмотр' => '{!! $link !!}'];
        } elseif ($id === 'new_research') {
            $content['tags_use'] = ['Автор' => '{{ $author }}', 'Заголовок исследования' => '{{ $title }}', 'Ссылка на просмотр' => '{!! $link !!}', 'Ссылка отписаться' => '{!! $unsubscribe !!}'];
        } elseif ($id === 'changing_order_status') {
            $content['tags_use'] = ['Статус' => '{{ $status }}', 'Номер заказа' => '{{ $number }}', 'Имя пользователя' => '{{ $user_to }}'];
        } elseif ($id === 'order') {
            $content['tags_use'] = ['Статус' => '{{ $status }}', 'Номер заказа' => '{{ $number }}', 'Имя пользователя' => '{{ $user_to }}'];

        } elseif ($id === 'purchase') {
            $content['tags_use'] = ['Номер заказа' => '{{ $number }}', 'Ссылка' => '{!! $link !!}', 'Имя пользователя' => '{{ $user_to }}'];
        }

        $content['title']  = 'Редактирование шаблона';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];

        return $content;

    }

    public function templateDataPageContent() {
        $content['title']  = 'Данные в шаблонах';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        return $content;
    }

    public function metaTagsPageContent() {
        $content['title']  = 'Метатеги';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        return $content;
    }
    public function metaTagsEditPageContent() {
        $content['title']  = 'Метатеги';
        $content['crumbs'] = [
            'Главная'         => route('admin.dashboard.index'),
            'Метатеги'        => route('admin.resources.metatags'),
            'Редактирование'  => '',
        ];
        return $content;
    }

}