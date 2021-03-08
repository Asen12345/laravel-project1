<?php

namespace App\Http\Controllers\Front;

use App\Eloquent\Anons;
use App\Eloquent\Blog;
use App\Eloquent\BlogPostSubscriber;
use App\Eloquent\News;
use App\Eloquent\NotificationSubscriber;
use App\Eloquent\ResearchAuthorSubscriber;
use App\Eloquent\Topic;
use App\Eloquent\UnsubscribedUser;
use App\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\Home\HomePageContent;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index() {

        $dayTopic = Topic::where('published', true)->where('main_topic', true)->first();
        if (empty($dayTopic)) {
            $dayTopicAnswers = null;
        } else {
            $dayTopicAnswers = $dayTopic->answers()
                ->with(['user' => function($query) {
                    $query->with('socialProfile');
                }])->get()->shuffle()->take(10);
        }

		//слайдер новостей на главной
        $newsScroll = News::where('published', 1)
            ->orderBy('vip', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->select('id', 'announce', 'name', 'title', 'url_en', 'vip', 'created_at', 'news_category_id')
            ->get()
            ->take(20);

        $blogs['experts'] = $this->blogsSortArray('expert', 3);
        
        $blogs['companies'] = $this->blogsSortArray('company', 3);
        $announces = Anons::where('will_end', '>', Carbon::now())
            ->where('main', true)
			->orderBy('date', 'ASC')
			->get();
        return view('front.page.home.index', [
            'blogs'            => $blogs,
            'announces'        => $announces,
            'dayTopic'         => $dayTopic,
            'newsScroll'       => $newsScroll,
            'dayTopicAnswers'  => $dayTopicAnswers,
            'content_page'     => (new HomePageContent())->indexPageContent()
        ]);
    }

    private function blogsSortArray ($type, $count) {

        $blogs['actual'] = Blog::where('blogs.active', true)
            ->whereHas('user', function ($query) use ($type) {
                $query->where('permission', $type)
                ->where('block', false);
            })
            ->withCount('posts')
            ->with(['user' => function($query){
                $query->with('socialProfile');
            }])
            ->with(['votes', 'posts'])
            ->withCount(['votes as votes_count' => function ($query) {
                $query->select(DB::raw('SUM(vote) as votes_count'));
            }])
            ->withCount(['posts as last_post_create' => function($query){
                $query->where('published', true)
                    ->latest()
                    ->take(1)
                    ->select('created_at');
            }])
            ->withCount(['comments as count_comments' => function($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('block', false);
                });
            }])
            ->orderBy('last_post_create', 'desc')
            ->limit($count)
            ->get();

        foreach ($blogs['actual'] as $blog) {
            $blog['count_view'] = $this->countPostInBlog($blog);
            $blog['last_post']  = $blog->lastPost()->where('published', true)->first();
        }

        $blogs['newest'] = Blog::where('blogs.active', true)
            ->whereHas('user', function ($query) use ($type) {
                $query->where('permission', $type)
                    ->where('block', false);
            })
            ->whereHas('lastPost', function ($query) use ($type) {
                $query->where('published', true);
            })
            ->withCount('posts')
            ->with(['votes', 'posts'])
            ->withCount(['votes as votes_count' => function ($query) {
                $query->select(DB::raw('SUM(vote) as votes_count'));
            }])
            ->withCount(['comments as count_comments' => function($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('block', false);
                });
            }])
            ->orderBy('id', 'desc')
            ->limit($count)
            ->get();

        foreach ($blogs['newest'] as $blog) {
            $blog['count_view'] = $this->countPostInBlog($blog);
            $blog['last_post']  = $blog->lastPost()->where('published', true)->first();
        }

        $blogs['popular'] = Blog::where('blogs.active', true)
            ->whereHas('user', function ($query) use ($type) {
                $query->where('permission', $type)
                    ->where('block', false);
            })
			->whereHas('lastPost', function ($query) use ($type) {
                $query->where('published', true);
            })
            ->withCount('posts')
            ->with(['votes', 'viewPosts'])
            ->withCount(['votes as votes_count' => function ($query) {
                $query->select(DB::raw('SUM(vote) as votes_count'));
            }])
            ->withCount(['comments as count_comments' => function($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('block', false);
                });
            }])
            ->withCount(['posts as views_count' => function($query) {
                $query->select(DB::raw('SUM(views_count) as views_count'));
            }])
            ->orderBy('views_count', 'desc')
            ->limit($count)
            ->get();

        foreach ($blogs['popular'] as $blog) {
            $blog['count_view'] = $this->countPostInBlog($blog);
            $blog['last_post']  = $blog->lastPost()->where('published', true)->first();
        }

        $blogs['discussed'] = Blog::where('blogs.active', true)
            ->whereHas('user', function ($query) use ($type) {
                $query->where('permission', $type)
                    ->where('block', false);
            })
            ->whereHas('lastPost', function ($query) use ($type) {
                $query->where('published', true);
            })
            ->withCount('posts')
            ->with(['votes', 'comments'])
            ->withCount(['votes as votes_count' => function ($query) {
                $query->select(DB::raw('SUM(vote) as votes_count'));
            }])
            ->whereHas('comments',  function($query) {
                $query->whereHas('user', function ($query){
                    $query->where('block', false);
                });
            })
            ->withCount(['comments as count_comments' => function($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('block', false);
                });
            }])
            ->orderBy('count_comments', 'desc')
            ->limit($count)
            ->get();
        foreach ($blogs['discussed'] as $blog) {
            $blog['count_view'] = $this->countPostInBlog($blog);
            $blog['last_post']  = $blog->lastPost()->where('published', true)->first();
        }

        return $blogs;
    }

    public function countPostInBlog($blog) {
        $posts = $blog->posts()->with('user')->get();
        $count = 0;
        foreach ($posts as $key => $post) {
            $count = $count + $post->views_count;
        }
        return $count;
    }



    /*Unsubscribe from email link function */
    public function unsubscribe(Request $request)
    {
        $randomKey1 = '2141569819753';
        $randomKey2 = 'slhaw25gwiYIURndf7202';

        $request->validate([
            'email'   => 'email|required|max:100',
            'hash'    => 'required|max:300'
        ]);

        $hash = base64_encode($randomKey1 . $request->input('email') . $randomKey2);

        if ($hash == $request->input('hash')) {

            $subscribe = NotificationSubscriber::where('email', $request->input('email'))->first();
            $unsubscribe = UnsubscribedUser::where('email', $request->input('email'))->first();

            if ($unsubscribe !== null) {
                return redirect()
                    ->route('front.home')
                    ->withErrors('Email '. $request->input('email').' уже был отписан от рассылки. Спасибо.');
            }
            DB::beginTransaction();
            try {
                /*Если пользователь зарегистрирован*/
                $user = User::where('email', $request->input('email'))->first();

                if (!empty($user)) {
                    User::find($user->id)->update([
                        'notifications_subscribed' => false
                    ]);
                    /*Добавление записи пользователя в базу отписавшихся выпольняется Observer при обновлении 'notifications_subscribed' => false*/
                } else {
                    NotificationSubscriber::where('email', $request->input('email'))->delete();
                    UnsubscribedUser::create(['email' => $request->input('email')]);
                }


                DB::commit();

            } catch (Exception $e) {

                DB::rollBack();

                $id_index = $e->getMessage();
                return redirect()
                    ->route('front.home')
                    ->withErrors(['error' => 'Ошибка!']);

            }
            return redirect()
                ->route('front.home')
                ->with('success', 'Email '. $request->input('email').' успешно отписан от рассылки. Спасибо.');
        } else {
            return abort('404');
        }

    }


    /*Unsubscribe from email link function */
    public function unsubscribeBlog(Request $request)
    {
        $randomKey1 = '2141569819753';
        $randomKey2 = 'slhaw25gwiYIURndf7202';

        $request->validate([
            'email'   => 'email|required|max:100',
            'hash'    => 'required|max:300'
        ]);

        $hash = base64_encode($randomKey1 . $request->input('email') . $randomKey2);

        if ($hash == $request->input('hash')) {

            $subscribe = BlogPostSubscriber::where('email', $request->input('email'))
                ->where('blog_id', $request->input('blog_id'))->first();

            if ($subscribe == null) {
                return redirect()
                    ->route('front.home')
                    ->withErrors('Email '. $request->input('email').' не подписан на данный блог.');
            } else {

                DB::beginTransaction();
                try {
                    $subscribe->delete();
                    DB::commit();

                } catch (Exception $e) {

                    DB::rollBack();

                    $id_index = $e->getMessage();
                    return redirect()
                        ->route('front.home')
                        ->withErrors(['error' => 'Ошибка!']);

                }
                return redirect()
                    ->route('front.home')
                    ->with('success', 'Email '. $request->input('email').' успешно отписан от рассылки. Спасибо.');

            }

        } else {
            return abort('404');
        }

    }


    /*Unsubscribe from email link function */
    public function unsubscribeResearches(Request $request)
    {
        $randomKey1 = '2141569819753';
        $randomKey2 = 'slhaw25gwiYIURndf7202';

        $request->validate([
            'email'   => 'email|required|max:100',
            'hash'    => 'required|max:300'
        ]);

        $hash = base64_encode($randomKey1 . $request->input('email') . $randomKey2);
        if ($hash == $request->input('hash')) {

            $subscribe = ResearchAuthorSubscriber::where('email', $request->input('email'))
                ->where('author_id', $request->input('author_id'))->first();

            if (empty($subscribe)) {
                return redirect()
                    ->route('front.home')
                    ->withErrors('Email '. $request->input('email').' не подписан на данного автора.');
            } else {

                $subscribe->delete();

                return redirect()
                    ->route('front.home')
                    ->with('success', 'Email '. $request->input('email').' успешно отписан от рассылки. Спасибо.');

            }

        } else {
            return abort('404');
        }

    }





}
