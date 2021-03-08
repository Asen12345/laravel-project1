<?php

namespace App\Http\PageContent\Frontend\Shop;


use App\Eloquent\MetaTags;
use App\Eloquent\ShopCategory;

class ResearchesPageContent
{
    public function __construct() {
        //
    }
    public function researchAuthorsPage($category = null, $sub_category = null, $research = null) {
        $categories = ShopCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        /*Meta Tags*/
        if ($category !== null && $sub_category !== null) {
            $content_page['meta'] = $sub_category;
        } elseif ($category !== null) {
            $content_page['meta'] = $category;
        } else {
            $content_page['meta'] = MetaTags::where('meta_id', 'meta_researches_author')->first();
        }

        $content_page['title']                 = 'Авторы исследований';

        $content_page['crumbs']   = [
            'Главная' => route('front.home'),
            'Магазин исследований' => route('front.page.shop'),
            $content_page['title'] => ''
        ];

        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'shop';
        return $content_page;
    }

    /**
     * @param null $category
     * @param null $sub_category
     * @param $author
     * @return mixed
     */
    public function researchAuthorPage($category = null, $sub_category = null, $author = null) {

        $categories = ShopCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });
        /*Meta Tags*/
        if ($category !== null && $sub_category !== null) {
            $content_page['meta'] = $sub_category;
        } elseif ($category !== null) {
            $content_page['meta'] = $category;
        } else {
            $content_page['meta']['meta_title'] = $author->meta_title ?? $author->title;
            $content_page['meta']['meta_keywords'] = $author->meta_keywords ?? MetaTags::where('meta_id', 'meta_researches_author')->first()->meta_keywords ?? '';
            $content_page['meta']['meta_description'] = $author->meta_description ?? MetaTags::where('meta_id', 'meta_researches_author')->first()->meta_description ?? '';
        }

        $content_page['title']                 = $author->title;

        $content_page['crumbs']   = [
            'Главная' => route('front.home'),
            'Магазин исследований' => route('front.page.shop'),
            'Авторы исследований' => route('front.page.shop.researches.authors'),
            $content_page['title'] => ''
        ];

        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'shop';
        return $content_page;
    }

	/* страница исследований */
    public function researchesPageContent ($category = null, $sub_category = null, $research = null) {

        /*Make categories collection with child categories*/
        $categories = ShopCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        /*Meta Tags*/
        if ($category !== null && $sub_category !== null) {
            $content_page['meta'] = $sub_category;
        } elseif ($category !== null) {
            $content_page['meta'] = $category;
        } else {
            $content_page['meta'] = MetaTags::where('meta_id', 'meta_researches_main')->first();
        }

        $content_page['title']                 = 'Магазин исследований';

        $crumbs_home = ['Главная' => route('front.home')];
        $content_page['crumbs']   = $this->makeCrumbs($category, $sub_category, $crumbs_home, $content_page['title'] , $research);
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'shop';
        return $content_page;

    }

	/* страница исследования */
    public function researchPageContent ($category = null, $sub_category = null, $research = null) {

        /*Make categories collection with child categories*/
        $categories = ShopCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

		/*Meta Tags*/
        $content_page['meta'] = [
            'meta_title'       => $research->meta_title,
            'meta_keywords'    => $research->meta_keywords,
            'meta_description' => $research->meta_description ?? strip_tags($research->annotation),
			'og_image'         => $research->image,
        ];

        $content_page['title']                 = 'Магазин исследований';

        $crumbs_home = ['Главная' => route('front.home')];
        $content_page['crumbs']   = $this->makeCrumbs($category, $sub_category, $crumbs_home, $content_page['title'] , $research);
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'shop';
        return $content_page;

    }

    public function cartPageContent ($category = null, $sub_category = null, $research = null) {

        /*Make categories collection with child categories*/
        $categories = ShopCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        /*Meta Tags*/
        $content_page['meta'] = [
            'name'              => 'Корзина покупок',
            'meta_title'        => 'Корзина покупок',
            'meta_keywords'     => 'Корзина покупок',
            'meta_description'  => 'Корзина покупок',
        ];

        $content_page['title']                 = 'Корзина';

        $content_page['crumbs']   = [
            'Главная' => route('front.home'),
            'Магазин исследований' => route('front.page.shop'),
            $content_page['title'] => ''
        ];
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'shop';
        return $content_page;

    }

    public function checkoutPageContent ($category = null, $sub_category = null, $research = null) {

        /*Make categories collection with child categories*/
        $categories = ShopCategory::orderBy('sort', 'asc')->where('parent_id', 0)->get();
        $categories->map(function($d){
            $d['sub_category'] = $d->child;
            return $d;
        });

        /*Meta Tags*/
        $content_page['meta'] = [
            'name'              => 'Корзина покупок',
            'meta_title'        => 'Корзина покупок',
            'meta_keywords'     => 'Корзина покупок',
            'meta_description'  => 'Корзина покупок',
        ];

        $content_page['title']                 = 'Корзина';

        $content_page['crumbs']   = [
            'Главная' => route('front.home'),
            'Магазин исследований' => route('front.page.shop'),
            $content_page['title'] => ''
        ];
        $content_page['categories_collection'] = $categories;
        $content_page['menu_active']           = 'shop';
        return $content_page;

    }

    private function makeCrumbs ($category, $sub_category, $crumbs, $title, $research) {

        if (!empty($category) && empty($sub_category)) {
            $crumbs = $crumbs + array('Магазин исследований' => route('front.page.shop'));
            if (!empty($research)) {
                $crumbs = $crumbs + array($category->name => route('front.page.shop.researches.category', ['url_section' => $category->url_en]));
                $crumbs = $crumbs + array($research->title => '');
            } else {
                $crumbs = $crumbs + array($category->name => '');
            }
        }

        if (!empty($category) && !empty($sub_category)) {
            $crumbs = $crumbs + array('Магазин исследований' => route('front.page.shop'));
            $crumbs = $crumbs + array($category->name => route('front.page.shop.researches.category', ['url_section' => $category->url_en]));
            if (!empty($research)) {
                $crumbs = $crumbs + array($sub_category->name => route('front.page.shop.researches.category', ['url_section' => $category->url_en, 'url_sub_section' => $sub_category->url_en]));
                $crumbs = $crumbs + array($research->title => '');
            } else {
                $crumbs = $crumbs + array($sub_category->name => '');
            }
        } else {
            $crumbs = $crumbs + array($title => '');
        }
        return $crumbs;
    }

}
