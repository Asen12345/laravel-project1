<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class CountriesPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Страны';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'title'                    => ['title' => 'Имя' , 'sort_url' => $this->generateUrlSort($request , 'title')],
            'position'                 => ['title' => 'Позиция', 'sort_url' => $this->generateUrlSort($request , 'position')],
            'hidden'                   => ['title' => 'Скрыто' , 'sort_url' => $this->generateUrlSort($request , 'hidden')],
        ];
        //$content['page'] = 'admins';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.resources.countries.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
