<?php

namespace App\Http\Controllers\Front\People;

use App\Eloquent\User;
use App\Http\PageContent\Frontend\People\Company\CompanyPageContent;
use App\Http\PageContent\Frontend\People\Expert\ExpertPageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use phpDocumentor\Reflection\Types\AbstractList;


class MainUserController extends Controller
{
    /**
     * @var integer
     */
    protected $count_view;
    protected $user;
    protected $blog_post_count;
    protected $news_count;
    protected $comments_count;
    protected $friends_count;
    protected $news;
    protected $blog_posts;
    protected $comments;
    protected $topic_answers_count;
    protected $topicAnswers;

    public function __construct(Request $request) {
        $this->user = User::where('active', true)
            ->where('id', $request->id)
            ->where('block', false)
            ->with(['socialProfile', 'privacy', 'company'])
            ->with(['blog' => function($query) {
                $query->where('active', true)
                    ->whereHas('firstPost', function ($query){
                        $query->where('published', true);
                    });
            }])
            ->first();
        if (empty($this->user)) {
            return abort(404);
        }

        // process views count incrementation
        \ViewsCount::process($this->user);

        $this->count_view          = $this->user->views_count;
        $this->blog_post_count     = $this->user->blogPosts()
            ->where('published', true)
            ->whereHas('blog', function ($query){
                $query->where('active', true);
            })
            ->count();
        $this->news_count          = $this->user->news->where('published', true)->count();
        $this->comments_count      = $this->user->comments->where('published', true)->count();
        $this->topic_answers_count = $this->user->topicAnswers->where('published', true)->count();
        $this->friends_count       = $this->user->friends->where('accepted', true)->count();
        $this->news                = $this->user->news()->where('published', true)->orderBy('created_at', 'DESC')->paginate(5, ['*'], 'news');
        $this->blog_posts          = $this->user->blogPosts()
            ->where('published', true)
            ->whereHas('blog', function ($query){
                $query->where('active', true);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(5, ['*'], 'blog_posts');
        $this->comments            = $this->user->comments()->where('published', true)->orderBy('created_at', 'DESC')->paginate(5, ['*'], 'comments');
        $this->topicAnswers        = $this->user->topicAnswers()->where('published', true)->orderBy('created_at', 'DESC')->paginate(5, ['*'], 'topicAnswers');
    }

    public function userPage(Request $request, $id) {

        if($this->user->exists){
            if ($this->user->permission === 'expert') {
                return view('front.page.people.expert.expert', [
                    'user'                 => $this->user,
                    'news'                 => $this->news,
                    'blog_posts'           => $this->blog_posts,
                    'comments'             => $this->comments,
                    'count_view'           => $this->count_view,
                    'friends_count'        => $this->friends_count,
                    'content_page'         => (new ExpertPageContent())->expertPageContent($this->user),
                    'blog_post_count'      => $this->blog_post_count,
                    'news_count'           => $this->news_count,
                    'comments_count'       => $this->comments_count,
                    'topic_answers_count'  => $this->topic_answers_count,
                    'topicAnswers'         => $this->topicAnswers,
                ]);
            }
            if ($this->user->permission === 'company') {
                return view('front.page.people.company.company', [
                    'user'            => $this->user,
                    'news'            => $this->news,
                    'blog_posts'      => $this->blog_posts,
                    'comments'        => $this->comments,
                    'count_view'      => $this->count_view,
                    'friends_count'   => $this->friends_count,
                    'content_page'    => (new CompanyPageContent())->companyPageContent($this->user),
                    'blog_post_count' => $this->blog_post_count,
                    'news_count'      => $this->news_count,
                    'comments_count'  => $this->comments_count,
                ]);
            }
            return abort('404');
        } else {
            return abort('404');
        }

    }
}
