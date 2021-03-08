<?php

namespace App\Http\Controllers\Front\Blog;

use App\Eloquent\Banner;
use App\Eloquent\BannerPlace;
use App\Eloquent\Blog;
use App\Http\PageContent\Frontend\Blog\BlogPageContent;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class BlogController extends Controller
{

    public function allBlogs($sort = null) {
        if ($sort === null) {
            $blogs = Blog::where('blogs.active', true)
                ->withCount('posts')
                ->with(['user', 'votes'])
                ->whereHas('user', function ($query){
                    $query->where('block', false);
                })
                ->withCount(['votes as votes_count' => function ($query) {
                    $query->select(DB::raw('SUM(vote) as votes_count'));
                }])
                ->with(['subscribers' => function ($query){
                    $query->where('active', true);
                }])
                ->withCount(['posts as last_post_create' => function($query){
                    $query->where('published', true)
                        ->latest()
                        ->take(1)
                        ->select('created_at');
                }])
                ->orderBy('last_post_create', 'desc')
                ->paginate(30);
        }
        if ($sort == 'newest') {
            $blogs = Blog::where('blogs.active', true)
                ->whereHas('user', function ($query) {
                    $query->where('block', false);
                })
                ->whereHas('lastPost', function ($query) {
                    $query->where('published', true);
                })
                ->withCount('posts')
                ->with(['user', 'votes', 'posts'])
                ->withCount(['votes as votes_count' => function ($query) {
                    $query->select(DB::raw('SUM(vote) as votes_count'));
                }])
                ->withCount(['comments as count_comments' => function($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('block', false);
                    });
                }])
                ->with(['subscribers' => function ($query){
                    $query->where('active', true);
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(30);
        }

        if ($sort == 'popular') {
            $blogs = Blog::where('blogs.active', true)
                ->whereHas('user', function ($query) {
                    $query->where('block', false);
                })
                ->whereHas('lastPost', function ($query) {
                    $query->where('published', true);
                })
                ->withCount('posts')
                ->with(['user', 'votes', 'posts'])
                ->withCount(['votes as votes_count' => function ($query) {
                    $query->select(DB::raw('SUM(vote) as votes_count'));
                }])
                ->withCount(['comments as count_comments' => function($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('block', false);
                    });
                }])
                ->with(['subscribers' => function ($query){
                    $query->where('active', true);
                }])
                ->withCount(['posts as views_count' => function($query) {
                    $query->select(DB::raw('SUM(views_count) as views_count'));
                }])
                ->orderBy('views_count', 'desc')
                ->paginate(30);

        }
        if ($sort == 'discussed') {
            $blogs = Blog::where('blogs.active', true)
                ->whereHas('user', function ($query) {
                    $query->where('block', false);
                })
                ->whereHas('lastPost', function ($query) {
                    $query->where('published', true);
                })
                ->withCount('posts')
                ->with(['user', 'votes', 'comments'])
                ->withCount(['votes as votes_count' => function ($query) {
                    $query->select(DB::raw('SUM(vote) as votes_count'));
                }])
                ->whereHas('comments',  function($query) {
                    $query->whereHas('user', function ($query){
                        $query->where('block', false);
                    });
                })
                ->with(['subscribers' => function ($query){
                    $query->where('active', true);
                }])
                ->withCount(['comments as count_comments' => function($query) {
                    $query->whereHas('user', function ($query) {
                        $query->where('block', false);
                    });
                }])
                ->orderBy('count_comments', 'desc')
                ->paginate(30);
        }
        if ($sort == 'rate') {
            $blogs = Blog::where('blogs.active', true)
                ->withCount('posts')
                ->with(['user', 'votes', 'posts'])
                ->whereHas('user', function ($query){
                    $query->where('block', false);
                })
                ->withCount(['votes as votes_count' => function ($query) {
                    $query->select(DB::raw('SUM(vote) as votes_count'));
                }])
                ->with(['subscribers' => function ($query){
                    $query->where('active', true);
                }])
                ->orderBy('votes_count', 'desc')
                ->paginate(30);
        }
        if ($sort === 'company' || $sort === 'expert') {
            $blogs = Blog::where('blogs.active', true)
                ->whereHas('user', function ($query) use ($sort) {
                    $query->where('permission', $sort)
                        ->where('block', false);
                })
                ->withCount('posts')
                ->with(['user', 'votes'])
                ->withCount(['posts as last_post' => function($query){
                    $query->where('published', true)->latest()->take(1)->select('created_at');
                }])
                ->withCount(['votes as votes_count' => function ($query) {
                    $query->select(DB::raw('SUM(vote) as votes_count'));
                }])
                ->with(['subscribers' => function ($query){
                    $query->where('active', true);
                }])
                ->orderBy('last_post', 'desc')
                ->paginate(30);
        }
        if (!empty($blogs)) {
            foreach ($blogs as $blog) {
                $blog['user_social']        = $blog->user->socialProfile->image;
                $blog['active_count_blog']  = $blog->getCountsPostActive();
                $blog['last_post']          = $blog->lastPost()->where('published', true)->first();
                $blog['count_view']         = $this->countPostInBlog($blog);
                $blog['count_comments']     = $blog->comments->count();
                $blog['subscribe']          = auth()->check() ? $blog->subscribers->where('active', true)->contains('user_id', auth()->user()->id) : null;
            }
        }

        return view('front.page.blog.blogs', [
            'content_page'  => (new BlogPageContent())->blogsPageContent($sort),
            'blogs'         => $blogs,
        ]);
    }

    public function blog($permission, $blog_id) {

        $blog = Blog::where('blogs.active', true)
            ->where('id', $blog_id)
            ->whereHas('user', function ($query){
                $query->where('block', false);
            })
            ->whereHas('firstPost', function ($query){
                $query->where('published', true);
            })
            ->where('active', true)
            ->withCount('posts')
            ->with(['votes'])
            ->with(['subscribers' => function ($query){
                $query->where('active', true);
            }])
            ->withCount(['votes as votes_count' => function ($query) {
                $query->select(DB::raw('SUM(vote) as votes_count'));
            }])
            ->first();

        if (empty($blog)) {
            return abort(404);
        }

        $bannersBlog = Banner::where('banner_place_id', 12)
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
            ->get()->shuffle()->take(BannerPlace::find(12)->view_count);

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

        $posts = $blog->posts()
            ->where('published', true)
            ->with(['tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        foreach ($posts as $post) {
            $post['count_view']     = $post->views_count;
            $post['count_comments'] = $post->comments()
                ->whereHas('user', function ($query){
                    $query->where('block', false);
                })
                ->where('blog_post_discussions.published', true)->count();
        }


        return view('front.page.blog.blog-page', [
            'content_page'  => (new BlogPageContent())->blogPageContent($blog->subject),
            'blog'          => $blog,
            'posts'         => $posts,
            'bannersBlog'   => $bannersBlog,

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
