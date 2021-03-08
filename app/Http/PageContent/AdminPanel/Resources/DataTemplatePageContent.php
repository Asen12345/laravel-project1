<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class DataTemplatePageContent
{
    public function __construct() {
        //
    }

    public function homePageContent () {

        $content['title']  = 'Данные в шаблоне';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['page'] = 'data.template';

        return $content;

    }

}
