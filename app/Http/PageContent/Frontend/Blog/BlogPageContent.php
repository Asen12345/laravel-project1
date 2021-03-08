<?php

namespace App\Http\PageContent\Frontend\Blog;


use App\Eloquent\MetaTags;

class BlogPageContent
{
    public function __construct() {
        //
    }

    public function blogsPageContent ($sort) {
        if($sort == null) {
            $content_page['title']       = 'Блоги';
            $content_page['crumbs']      = ['Главная' => route('front.home'), $content_page['title'] => ''];
            /*Meta Tags*/
            $content_page['meta'] = MetaTags::where('meta_id', 'meta_blogs')->first();

        } elseif ($sort === 'company' || $sort === 'expert') {
            $content_page['title']       = $sort === 'company' ?' Корпоративные блоги' : 'Личные блоги';
            $content_page['crumbs']      = [
                'Главная' => route('front.home'),
                'Блоги'   => route('front.page.blogs.all'),
                $content_page['title'] => ''
            ];
            /*Meta Tags*/

            if ($sort === 'company') {
                $content_page['meta'] = MetaTags::where('meta_id', 'meta_company_blog')->first();
            } elseif ($sort === 'expert') {
                $content_page['meta'] = MetaTags::where('meta_id', 'meta_expert_blog')->first();
            }

        } else {
            $content_page['title']       = 'Блоги';
            $content_page['crumbs']      = ['Главная' => route('front.home'), $content_page['title'] => ''];

            if ($sort === 'newest') {
                $content_page['meta'] = MetaTags::where('meta_id', 'meta_blog_posts_newest')->first();
            } elseif ($sort === 'popular') {
                $content_page['meta'] = MetaTags::where('meta_id', 'meta_blog_posts_popular')->first();
            } elseif ($sort === 'discussed') {
                $content_page['meta'] = MetaTags::where('meta_id', 'meta_blog_posts_discussed')->first();
            } elseif ($sort === 'rate') {
                $content_page['meta'] = MetaTags::where('meta_id', 'meta_blog_posts_rate')->first();
            }
        }

        $content_page['menu_active'] = 'blogs';
        $content_page['category']    = 'blogs';
        return $content_page;

    }

    public function blogPageContent ($subject) {
        $content_page['title']       = $subject;
        $content_page['crumbs']      = [
            'Главная'              => route('front.home'),
            'Блоги'                => route('front.page.blogs.all'),
            $content_page['title'] => ''
        ];
        $content_page['menu_active'] = 'blogs';
        $content_page['category']    = 'blogs';
        return $content_page;

    }
}
