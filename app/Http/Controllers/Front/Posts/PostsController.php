<?php

namespace App\Http\Controllers\Front\Posts;

use App\Eloquent\Banner;
use App\Eloquent\BannerPlace;
use App\Eloquent\Blog;
use App\Eloquent\BlogPost;
use App\Eloquent\UserNotifyComment;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\Blog\PostPageContent;
use Carbon\Carbon;
use DB;

class PostsController extends Controller
{

    public function __construct()
    {
        //
    }

    public function allPosts() {

        $posts = BlogPost::where('published', true)
            ->with(['user', 'blog'])
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->withCount(['comments as count_comments' => function ($query) {
                $query->where('published', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        foreach ($posts as $post) {
            $post['count_view']     = $post->views_count;
        }

        return view('front.page.blog.posts-page', [
            'content_page'  => (new PostPageContent())->postsPageContent(),
            'posts'         => $posts,
        ]);
    }

    public function tagPosts($name)
    {
        $posts = BlogPost::where('published', true)
            ->with(['user', 'blog'])
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->whereHas('tags', function($query) use ($name) {
                $query->where('name', $name);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(30);

        return view('front.page.blog.posts-page', [
            'content_page'  => (new PostPageContent())->postTagsPageContent($name),
            'posts'         => $posts,
        ]);
    }

    public function post($permission, $blog_id, $post_id) {

        $post = BlogPost::where('blog_id', $blog_id)
            ->where('published', true)
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->whereHas('blog', function ($query){
                $query->where('active', true);
            })
            ->where('id', $post_id)
            ->orderBy('published_at', 'desc')
            ->first();

        if (empty($post)) {
            return abort(404);
        }

        /*View Counter*/
        \ViewsCount::process($post);


        $bannersBlog = Banner::where('banner_place_id', 4)
            ->where(function ($query) use ($blog_id) {
                $query->where('blog_announce_id', $blog_id)
                    ->orWhereNull('blog_announce_id');
            })
            ->where('published', true)
            ->whereDate('date_from', '<=', Carbon::today()->toDateString())
            ->where(function ($query) {
                $query->whereDate('date_to', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('date_to');
            })
            ->get()->shuffle()->take(BannerPlace::find(4)->view_count);


        $post['rate']               = $post->getRating();
        $post['count_view']         = $post->views_count;
        $post['count_comments']     = BlogPost::where('blog_id', $blog_id)
            ->where('published', true)
            ->where('id', $post_id)
            ->first()
            ->comments()
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->count();
        $comments   = $post->comments()
            ->with('user', 'socialProfileUser')
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->where('published', true)
            ->paginate(30);

        $blog = Blog::where('blogs.active', true)
            ->where('id', $blog_id)
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->withCount('posts')
            ->with(['votes'])
            ->withCount(['votes as votes_count' => function ($query) {
                $query->select(DB::raw('SUM(vote) as votes_count'));
            }])
            ->first();

        $blog['user_social']        = $blog->user->socialProfile->image;
        $blog['active_count_blog']  = $blog->getCountsPostActive();
        $blog['last_post']          = $blog->posts()->where('published', true)->orderBy('created_at', 'desc')->first();
        $blog['count_view']         = $this->countPostInBlog($blog);

        $blog['count_comments']     = $blog->comments()
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->where('blog_post_discussions.published', true)
            ->count();

        $blog['subscribe']          = auth()->check() ? $blog->subscribers->where('active', true)->contains('user_id', auth()->user()->id) : null;

        if (auth()->check()) {
            $user_setting = UserNotifyComment::where('user_id', auth()->user()->id)
                ->where('blog_post_id', $post_id)->first();
        } else {
            $user_setting = null;
        }

        return view('front.page.blog.post-page', [
            'content_page' => (new PostPageContent())->postPageContent($blog, $post->title),
            'blog'         => $blog,
            'post'         => $post,
            'comments'     => $comments,
            'user_setting' => $user_setting,
            'bannersBlog'  => $bannersBlog,
        ]);
    }

    public function countPostInBlog($blog) {
        $posts = $blog->posts()->with('user')->get();
        $count = 0;
        foreach ($posts as $key => $post) {
            $count = $count + $post->views_count;
        }
        return $count;
    }
}
