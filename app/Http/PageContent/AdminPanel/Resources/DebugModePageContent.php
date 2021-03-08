<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class DebugModePageContent
{
    public function __construct() {
        //
    }

    public function homePageContent () {

        $content['title']  = 'Режим отладки';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['page'] = 'debug.mode';

        return $content;

    }

}
