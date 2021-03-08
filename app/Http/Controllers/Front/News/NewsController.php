<?php

namespace App\Http\Controllers\Front\News;

use App\Eloquent\News;
use App\Eloquent\NewsCategory;
use App\Eloquent\NewsScene;
use App\Http\Controllers\Controller;
use App\Http\PageContent\Frontend\News\NewsPageContent;
use App\Repositories\Frontend\News\NewsRepository;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{

    protected $category;
    protected $model_cat_sub;
    protected $model_cat;
    protected $array_child_cat_id;

    public function __construct(Request $request) {
        if (!empty($request->url_section) || !empty($request->url_sub_section)) {
            $request->validate([
                'url_section'     => 'string|max:255',
                'url_sub_section' => 'string|max:255',
            ]);
            if (!empty($request->url_sub_section)) {
                $this->category = NewsCategory::where('url_en', $request->url_sub_section)->first();
                $this->model_cat_sub = $this->category;
                $this->model_cat = NewsCategory::where('url_en', $request->url_section)->first();
            } else {
                $this->category = NewsCategory::where('url_en', $request->url_section)->first();
                $this->model_cat = $this->category;
                /*array ID child category*/
                $this->array_child_cat_id =  $this->category->child->pluck('id')->toArray();
            }
        }
    }

    public function filterScene(Request $request) {
        if (!empty($request->scene)) {
            $strId = implode(",",array_keys($request->scene));
            return redirect(route('front.page.news.scene', ['id' => $strId]) . '#view');
        } else {
            return redirect()
                ->back()
                ->withErrors(['Не выбран параметр для поиска']);
        }


    }

    public function index(Request $request) {

        $author_user_id = $request->author_user_id ?? null;
        if($request->route()->getName() == 'front.page.news.scene') {
            $array_id = explode( ',', $request->id);
            foreach ($array_id as $id) {
                if (is_numeric($id)){

                } else {
                    abort('404');
                }
            }

            $news = News::with('scene')
                ->where(function ($q) {
                    $q->where('posted', 'redactor')
                      ->orWhereHas('user', function ($query) {
                            $query->where('block', false)
                                ->orWhereNull('block');
                      });
                })
                ->whereHas('scene', function ($query) use ($array_id) {
                    $query->whereIn('news_scenes.id', $array_id);
                })
                ->where('published', 1)
                ->where('vip', false)
                ->orderBy('created_at', 'DESC')
                ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                ->paginate(30);

            $vipNews = News::with('scene')
                ->where('published', 1)
                ->where(function ($query) {
                    $query->whereHas('user', function ($query){
                        $query->where('block', false)
                            ->orWhereNull('block');
                    })->orWhereNull('author_user_id');
                })
                ->where('vip', true)
                ->orderBy('created_at', 'DESC')
                ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                ->get();

            $scene = NewsScene::where('id', $array_id[0])->first();
            return view('front.page.news.news', [
                'content_page'  => (new NewsPageContent())->newsPageContent($this->model_cat, $this->model_cat_sub, $scene),
                'news'          => $news,
                'vipNews'       => $vipNews,
            ]);

        } else {

            if (!empty($this->category)) {
                if (empty($request->url_sub_section)) {
                    $news = News::where(function ($query) {
                            $query->whereIn('news_category_id', $this->array_child_cat_id)
                                ->orWhere('news_category_id', $this->category->id);
                        })
                        ->where(function ($query) {
                            $query->whereHas('user', function ($query){
                                $query->where('block', false)
                                    ->orWhereNull('block');
                            })->orWhereNull('author_user_id');
                        })
                        ->where(function ($query) use ($author_user_id) {
                            if (isset($author_user_id)) {
                                $query->where('author_user_id', $author_user_id);
                            }
                        })
                        ->where('published', 1)
                        ->where('vip', false)
                        ->orderBy('created_at', 'DESC')
                        ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                        ->paginate(30);

                    $vipNews = News::where('published', 1)
                        ->where(function ($query) {
                            $query->whereHas('user', function ($query){
                                $query->where('block', false)
                                    ->orWhereNull('block');
                            })->orWhereNull('author_user_id');
                        })
                        ->where('vip', true)
                        ->orderBy('created_at', 'DESC')
                        ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                        ->get();
                } else {

                    $news = $this->category->news()->where('published', 1)
                        ->where(function ($query) {
                            $query->whereHas('user', function ($query){
                                $query->where('block', false)
                                    ->orWhereNull('block');
                            })->orWhereNull('author_user_id');
                        })
                        ->where(function ($query) use ($author_user_id) {
                            if (isset($author_user_id)) {
                                $query->where('author_user_id', $author_user_id);
                            }
                        })
                        ->where('vip', false)
                        ->orderBy('created_at', 'DESC')
                        ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                        ->paginate(30);
                    $vipNews = News::where('published', 1)
                        ->where(function ($query) {
                            $query->whereHas('user', function ($query){
                                $query->where('block', false)
                                    ->orWhereNull('block');
                            })->orWhereNull('author_user_id');
                        })
                        ->where('vip', true)
                        ->orderBy('created_at', 'DESC')
                        ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                        ->get();
                }

            } else {

                $news = News::where('published', 1)
                    ->where(function ($query) {
                        $query->whereHas('user', function ($query){
                            $query->where('block', false)
                                ->orWhereNull('block');
                        })->orWhereNull('author_user_id');
                    })
                    ->where(function ($query) use ($author_user_id) {
                        if (isset($author_user_id)) {
                            $query->where('author_user_id', $author_user_id);
                        }
                    })
                    ->where('vip', false)
                    ->orderBy('created_at', 'DESC')
                    ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                    ->paginate(30);

                $vipNews = News::where('published', 1)
                    ->where(function ($query) {
                        $query->whereHas('user', function ($query){
                            $query->where('block', false)
                                ->orWhereNull('block');
                        })->orWhereNull('author_user_id');
                    })
                    ->where('vip', true)
                    ->orderBy('created_at', 'DESC')
                    ->select('id', 'announce', 'url_en', 'vip', 'created_at', 'news_category_id', 'name', 'views_count')
                    ->get();
            }

            if ($news->isEmpty()) {
                return abort(404);
            }

            return view('front.page.news.news', [
                'content_page'  => (new NewsPageContent())->newsPageContent($this->model_cat, $this->model_cat_sub),
                'news'          => $news,
                'vipNews'          => $vipNews,
            ]);
        }

    }

    public function newsPage(Request $request) {
        if (!empty($request->url_sub_section)) {
            $category_url = $request->url_sub_section;
        } else {
            $category_url = $request->url_section;
        }
        $news = News::where('url_en', $request->url_news)
            ->where(function ($query) {
                $query->whereHas('user', function ($query){
                    $query->where('block', false)
                        ->orWhereNull('block');
                })->orWhereNull('author_user_id');
            })
            ->where('published', true)
            ->whereHas('category', function ($query) use ($category_url) {
                    $query->where('url_en', 'like', $category_url);
            })
            ->first();

        if (empty($news)) {
            return abort(404);
        }
        /*View Counter*/
        \ViewsCount::process($news);

        if ($news->author_show) {
            $news->user ? $who_posted = $news->user->name : $who_posted = 'Редактор';
        } else {
            $who_posted = 'Редактор';
        }

        return view('front.page.news.news-page', [
            'content_page'  => (new NewsPageContent())->newsPageContent($this->model_cat, $this->model_cat_sub , null, $news),
            'news'          => $news,
            'count_view'    => $news->views_count,
            'who_posted'    => $who_posted,
        ]);
    }

    public function createNews() {
        return view('front.page.news.news-add', [
            'content_page'  => (new NewsPageContent())->addNewsPage(),
        ]);
    }

    public function storeNews(Request $request) {

        $rules = ([
            'news_category_id'     => ['required', 'numeric'],
            'name'                 => ['required', 'string'],
            'text'                 => ['required', 'string'],
            'source_name'          => ['nullable', 'string'],
            'source_url'           => ['nullable', 'url'],
            'author'               => ['nullable', 'string'],
            'agree'                => ['required', 'string'],
            //'g-recaptcha-response' => ['required', 'captcha']
        ]);
        $messages = [
            'credits.*.percent.numeric' => 'Процент должен быть числом.',
        ];
        $names = [
            'news_category_id' => "'Категория'",
            'name'             => "'Название новости'",
            'text'             => "'Текст новости'",
            'source_name'      => "'Название источника'",
            'source_url'       => "'Ссылка на источник'",
            'author'           => "'Автор новости'",
            'agree'            => "'Согласен с правилами'",
        ];
        Validator::make($request->all(), $rules, $messages, $names)->validate();

        if (auth()->user()) {
            $user_id = auth()->user()->id;
        } else {
            $user_id = null;
        }
        $request_array = $request->except('_token');
        if (!empty($request_array['author_show'])) {
            $request_array['author_show'] = true;
        }

        $data = array_merge($request_array, ['author_user_id'=> $user_id]);

        $news = (new NewsRepository())->create($data);

        if (!empty($news->id)){
            return redirect()->back()->with('success', 'Новость успешно отправлена на модерацию. Спасибо.');
        } else {
            return redirect()->back()->withErrors(['При отправке произошла ошибка.']);
        }

    }
}
