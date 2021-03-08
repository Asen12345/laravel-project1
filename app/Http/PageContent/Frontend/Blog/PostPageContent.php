<?php

namespace App\Http\PageContent\Frontend\Blog;


use App\Eloquent\MetaTags;

class PostPageContent
{
    public function __construct() {
        //
    }

    public function postPageContent ($blog, $post) {
        $content_page['title']       = $post;
        $content_page['crumbs']      = [
            'Главная'              => route('front.home'),
            'Блоги'                => route('front.page.blogs.all'),
            $blog->subject         => route('front.page.blog', ['permission' => $blog->user->permission, 'blog_id' => $blog->id]),
            $content_page['title'] => ''
        ];
        $content_page['menu_active'] = 'blogs';
        $content_page['category']    = 'blogs';
        return $content_page;

    }

    public function postsPageContent () {
        $content_page['title']       = 'Все записи';
        $content_page['crumbs']      = [
            'Главная'              => route('front.home'),
            'Блоги'                => route('front.page.blogs.all'),
            $content_page['title'] => ''
        ];
        $content_page['meta'] = MetaTags::where('meta_id', 'meta_blog_posts')->first();

        $content_page['menu_active'] = 'blogs';
        $content_page['category']    = 'blogs';
        return $content_page;

    }

    public function postTagsPageContent ($name) {
        $content_page['title']       = $name;
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
