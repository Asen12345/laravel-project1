<?php

namespace App\Http\PageContent\AdminPanel\Resources;

use Illuminate\Support\Facades\Request;

class RegionsPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request, $id_country) {
        $content['title']  = 'Регионы';
        $content['crumbs'] = [
            'Главная' => route('admin.dashboard.index'),
            'Страны'  => route('admin.resources.countries'),
            $content['title'] => ''
        ];
        $content['fields'] = [
            'title'   => ['title' => 'Имя' , 'sort_url' => $this->generateUrlSort($request , 'title', $id_country)],
        ];
        //$content['page'] = 'admins';

        return $content;

    }


    public function generateUrlSort ($request , $column, $id_country) {

        $base_sort_url = route('admin.resources.regions.sort', ['id' => $id_country]) . '?';
        foreach ($request as $key => $valFilter) {
            $base_sort_url .= '&'.$key.'=' . $valFilter;
        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
