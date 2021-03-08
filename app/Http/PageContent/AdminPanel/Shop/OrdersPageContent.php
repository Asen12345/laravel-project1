<?php

namespace App\Http\PageContent\AdminPanel\Shop;

use Illuminate\Support\Facades\Request;

class OrdersPageContent
{
    public function __construct() {
        //
    }

    /**
     * @param $request
     * @return mixed
     */
    public function homePageContent ($request) {

        $content['title']  = 'Заказы';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'id'                         => ['title' => 'Номер заказа', 'sort_url' => $this->generateUrlSort($request , 'id')],
            'user_id'                    => ['title' => 'Фамилия/Имя', 'sort_url' => $this->generateUrlSort($request , 'user_id')],
            'total_count'                => ['title' => 'Сумма', 'sort_url' => $this->generateUrlSort($request , 'total_count')],
            'created_at'                 => ['title' => 'Создано' , 'sort_url' => $this->generateUrlSort($request , 'created_at')],
            'updated_at'                 => ['title' => 'Обновлено' , 'sort_url' => $this->generateUrlSort($request , 'updated_at')],
            'status'                     => ['title' => 'Статус' , 'sort_url' => $this->generateUrlSort($request , 'status')],
        ];
        return $content;

    }

    public function byersPageContent ($request) {

        $content['title']  = 'Покупатели';
        $content['crumbs'] = ['Главная' => route('admin.dashboard.index'), $content['title'] => ''];
        $content['fields'] = [
            'name'                    => ['title' => 'Фамилия/Имя', 'sort_url' => $this->generateUrlSortBuyers($request , 'name')],
            'cart_count'              => ['title' => 'Кол-во заказов', 'sort_url' => $this->generateUrlSortBuyers($request , 'cart_count')],
        ];
        return $content;

    }

    public function editPageContent () {

        $title = 'Просмотр заказа';
        $post_route = 'admin.news.update';

        $content['title']  = $title;
        $content['crumbs'] = [
            'Главная'      => route('admin.dashboard.index'),
            'Заказы' => route('admin.shop.researches.orders'),
            $title         => ''
            ];
        $content['post_route'] = $post_route;

        return $content;

    }

    public function generateUrlSort ($request , $column) {

        $base_sort_url = route('admin.shop.researches.orders.sort') . '?';
        foreach ($request as $key => $valFilter) {
            if ($key == 'date'){
                foreach ($valFilter as $k => $item) {
                    $base_sort_url .= '&date['. $k .']=' . $item;
                }
            } else {
                $base_sort_url .= '&'.$key.'=' . $valFilter;
            }

        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

    public function generateUrlSortBuyers ($request , $column) {

        $base_sort_url = route('admin.shop.researches.buyers.sort') . '?';
        foreach ($request as $key => $valFilter) {
            if ($key == 'date'){
                foreach ($valFilter as $k => $item) {
                    $base_sort_url .= '&date['. $k .']=' . $item;
                }
            } else {
                $base_sort_url .= '&'.$key.'=' . $valFilter;
            }

        }

        $base_sort_url .= '&sort_by='.$column;

        return $base_sort_url;

    }

}
