<?php

namespace App\Http\Controllers\Front\Rss;

use App\Eloquent\Anons;
use App\Eloquent\Blog;
use App\Eloquent\BlogPost;
use App\Eloquent\News;
use App\Eloquent\NewsCategory;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RssController extends Controller
{
    public function blogRss($blog_id)
    {
        $blog = Blog::where('id', $blog_id)
            ->where('active', true)
            ->with('user')
            ->with(['posts' => function($query) {
                $query->where('published', true)
				->orderBy('created_at', 'DESC')
				->take(5);
            }])->first();
        if (empty($blog)) {
            abort('404');
        }
        return response()->view('rss.blog',[
            'blog' => $blog,
        ])->header('Content-Type', 'text/xml');
    }

    /*expert or company*/
    public function blogTypeRss($type)
    {
        $blog = Blog::where('active', true)
            ->with('user')
            ->whereHas('user', function($query) use ($type) {
                $query->where('permission', $type);
            })
            ->with(['posts' => function($query) {
                $query->where('published', true)
				->orderBy('created_at', 'DESC')
				->take(5);
            }])->first();
        if (empty($blog)) {
            abort('404');
        }
        return response()->view('rss.blog',[
            'blog' => $blog,
        ])->header('Content-Type', 'text/xml');
    }

    public function blogsRss()
    {
        $posts = BlogPost::where('published', true)
			->orderBy('created_at', 'DESC')
            ->with('blog')
            ->with('user')
			->take(10)
            ->get();
        if (empty($posts)) {
            abort('404');
        }
        return response()->view('rss.blogs',[
            'posts' => $posts,
        ])->header('Content-Type', 'text/xml');
    }

    public function newsRss()
    {
        $news = News::with('user')
            ->where('published', true)
			->take(30)
			->orderBy('created_at', 'DESC')
			->get();
        if (empty($news)) {
            abort('404');
        }
        return response()->view('rss.news',[
            'news' => $news,
        ])->header('Content-Type', 'text/xml');
    }

    public function anonsRss()
    {
        $announces = Anons::where('will_end', '>=', Carbon::now())
			->orderBy('created_at', 'DESC')
            ->get();

        if (empty($announces)) {
            abort('404');
        }
        return response()->view('rss.anons',[
            'announces' => $announces,
        ])->header('Content-Type', 'text/xml');
    }

    public function sectionNewsRss(Request $request)
    {
        if (!empty($request->url_sub_section)) {
            $category = NewsCategory::where('url_en', $request->url_sub_section)->first();
        } else {
            $category = NewsCategory::where('url_en', $request->url_section)->first();
            /*array ID child category*/
            $array_child_cat_id =  $category->child->pluck('id')->toArray();
        }

        if (!empty($this->category)) {
            if (empty($request->url_sub_section)) {
                $news = News::where(function ($query) use ($category, $array_child_cat_id) {
                    $query->whereIn('news_category_id', $array_child_cat_id)
                        ->orWhere('news_category_id', $category->id);
                })
                    ->where('published', 1)
					->take(30)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            } else {
                $news = $this->category->news()->where('published', true)
					->take(30)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }

        } else {
            $news = News::where('published', true)
                ->take(30)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        if (empty($news)) {
            abort('404');
        }
        return response()->view('rss.news',[
            'news' => $news,
        ])->header('Content-Type', 'text/xml');
    }

    public function yandexRss()
    {
        $news = News::with('user')
            ->where('published', true)
			->take(10)
			->orderBy('created_at', 'DESC')
            ->with('category')
            ->where('yandex', true)
            ->get();

        if (empty($news)) {
            abort('404');
        }
        return response()->view('rss.yandex',[
            'news'     => $news,
        ])->header('Content-Type', 'text/xml');
    }

}
