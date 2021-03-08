<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class CityPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {
        $content['title']  = 'Города';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Страны'  => route('admin.resources.countries'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'title'   => [
                'title' => 'Имя',
                'sort_url' => $this->generateUrlSort($request , 'title')
            ],
            'country_count' => [
                'title' => 'Регион',
                'sort_url' => $this->generateUrlSort($request , 'country_count')
            ],
            'region_count' => [
                'title' => 'Регион',
                'sort_url' => $this->generateUrlSort($request , 'region_count')
            ],
        ];
        //$content['page'] = 'admins';

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.resources.city.sort') . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
