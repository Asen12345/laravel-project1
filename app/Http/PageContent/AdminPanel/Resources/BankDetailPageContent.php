<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class BankDetailPageContent
{
    public function __construct() {
        //
    }

    public function homePageContent () {

        $content['title']  = 'Реквизиты Русипотеки';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];

        return $content;

    }

}
