<?php

namespace App\Http\ViewComposers;

use App\Eloquent\ShopCategory;
use App\Eloquent\Widget;
use Illuminate\View\View;

class ShopMenuComposer
{
    public function compose(View $view) {

        $categories = ShopCategory::where('show', true)
            ->with('child')
            ->where('parent_id', 0)
            ->orderByRaw('-sort desc')
            ->get();

        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        $widget = Widget::where('id', 1)->first();
        if (request()->route()->getName() == 'front.page.blog' && isset($widget->blogs_id)) {
            if (in_array(request()->blog_id, json_decode($widget->blogs_id))) {
                $type = 'widget';
            } else {
                $type = 'category';
            };
        } elseif ($widget->published == false) {
            $type = 'category';
        } elseif ($widget->all_blogs == true && (preg_match('/front.page.blog/', request()->route()->getName()) || preg_match('/front.page.post/', request()->route()->getName()))) {
            $type = 'widget';
        } elseif (request()->route()->getName() != 'front.page.blog') {
            $type = 'category';
        } else {
            $type = 'category';
        }


        return $view->with([
            'categories' => $categories,
            'widget'     => $widget->text,
            'type'       => $type
        ]);

    }
}