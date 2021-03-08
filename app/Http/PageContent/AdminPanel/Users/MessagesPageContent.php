<?php

namespace App\Http\PageContent\AdminPanel\Users;

use Illuminate\Support\Facades\Request;

class MessagesPageContent
{
    public function __construct() {
        //
    }

    public function homePageContent () {

        $content['title']  = 'Массовая рассылка';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['page'] = 'message';

        return $content;

    }

}
