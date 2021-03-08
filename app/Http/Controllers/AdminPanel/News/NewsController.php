<?php

namespace App\Http\Controllers\AdminPanel\News;

use App\Eloquent\News;
use App\Eloquent\NewsCategory;
use App\Eloquent\NewsIdNewsSceneId;
use App\Eloquent\NewsScene;
use App\Eloquent\NewsSceneGroup;
use App\Eloquent\User;
use App\Http\PageContent\AdminPanel\News\NewsPageContent;
use App\Repositories\Back\News\NewsRepository;
use App\Repositories\Back\News\SceneRepository;
use Arr;
use Auth;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * @var User|null
     */
    protected $role;
    protected $redactorPermittedCat;
    protected $query;

    public function __construct() {
        /*Category permitted redactor*/
        $this->middleware(function ($request, $next) {
            $this->role = Auth::user()->role;
            if ($this->role == 'redactor') {
                /*Return array [cat1, cat2, ...]*/
                $this->redactorPermittedCat = auth()->user()->categoryPermission()->pluck('category_id')->toArray();
                if (Gate::allows('any-category') || Gate::allows('permission', 'all_news')){
                    return $next($request);
                } else {
                    abort('403');
                }
            }
            return $next($request);
        });

    }

    public function index (Request $request)
    {

        $parameters = $request->all();

        if (empty($request->sort_by)) {
            $sortBy = 'id';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $content = (new NewsPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $news = (new NewsRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'));


        if ($this->role == 'redactor') {
            if (Gate::allows('permission', 'all_news')){
                if (count($parameters) > 0) {
                    $news = $news->orderBy($sortBy, $sortOrder)->paginate(50);
                    $news->appends($parameters);
                } else {
                    $news = $news->orderBy($sortBy, $sortOrder)->paginate(50);
                }
            } else {
                if (count($parameters) > 0) {
                    $news = $news->whereIn('news_category_id', $this->redactorPermittedCat)
                        ->orderBy($sortBy, $sortOrder)
                        ->paginate(50);
                    $news->appends($parameters);
                } else {
                    $news = $news->whereIn('news_category_id', $this->redactorPermittedCat)
                        ->orderBy($sortBy, $sortOrder)
                        ->paginate(50);
                }
            }

        } else {

            if (count($parameters) > 0) {
                $news = $news->orderBy($sortBy, $sortOrder)
                    ->paginate(50);
                $news->appends($parameters);
            } else {
                $news = $news->orderBy($sortBy, $sortOrder)
                    ->paginate(50);
            }
        }


        $categories = NewsCategory::all();

        return view('admin_panel.news.news.index', [
            'content'      => $content,
            'news'         => $news,
            'categories'   => $categories,
            'sort_by'      => $sortBy,
            'sort_order'   => $sortOrder
        ]);
    }

    public function create () {
        $content = (new NewsPageContent())->editAndCreateUserPageContent();
        $scenes  = NewsScene::all();
        $categories   = NewsCategory::orderBy('sort', 'asc')->get();
        return view('admin_panel.news.news.create', [
            'content'        => $content,
            'categories'     => $categories,
            'scenes'         => $scenes,
        ]);
    }

    public function store (Request $request) {

        $this->validateNews($request);

        $news = (new NewsRepository())->createNews($request->all());
        if (!empty($news['error'])) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => $news['error']]);
        } else {

            if($request->save_and_stay) {
                return redirect()->back()
                ->with('success', 'Новость успешно добавлена.');
            }

            return redirect()
                ->route('admin.news.index')
                ->with('success', 'Новость успешно добавлена.');
        }
    }

    public function edit($id) {

        $content     = (new NewsPageContent())->editAndCreateUserPageContent();
        $scenes      = NewsScene::all();
        $categories  = NewsCategory::orderBy('sort', 'asc')->get();
        $news        = (new NewsRepository())->getById($id);
        if(!empty($news->user)) {
            $author = $news->user;
        } elseif ($news->author_text_val) {
            $author = $news->author_text_val;
        } else {
            $author = '';
        }


        /*if ($this->role == 'redactor'){
            if (!in_array($news->category->id, $this->redactorPermittedCat) || Gate::allows('any-category')) {
                abort('403');
            }
        }*/

        return view('admin_panel.news.news.edit', [
            'content'    => $content,
            'news'       => $news,
            'scenes'     => $scenes,
            'categories' => $categories,
            'author'     => $author
        ]);
    }

    public function update (Request $request, $id) {
        $this->validateNews($request);
        $news = (new NewsRepository())->updateNews($request->all(), $id);

        if (!empty($news['error'])) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors(['error' => $news['error']]);
        } else {
            
            if($request->save_and_stay) {
                return redirect()->back()
                ->with('success', 'Новость успешно обновлена.');
            }

            return redirect()
                ->route('admin.news.index')
                ->with('success', 'Новость успешно обновлена.');
        }
    }

    public function destroy($id) {
        News::where('id', $id)->delete();
        NewsIdNewsSceneId::where('news_id', $id)->delete();
        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Новость успешно удалена.');
    }


    public function autocompleteAuthor (Request $request) {
        $input = $request->input('value');
        $data = User::where(function ($query) use ($input) {
            if($input == '') {
                $query->where('name', 'LIKE', '%' .''. '%');
            } else (
                $query->where('name', 'LIKE', '%' . $input . '%')
                ->orWhere('email', 'LIKE', '%' . $input . '%')
            );

        })
            //->where('active', true)
            ->take(15)->get();

        return response()->json($data);
    }

    protected function validateNews(Request $request) {
        $rules = ([
            'name'                  => ['required', 'string', 'max:255'],
            'published'             => ['in:on', 'max:2'],
            'title'                 => ['nullable', 'string', 'max:255'],
            'url_ru'                => ['required', 'string', 'max:255'],
            'url_en'                => ['required', 'string', 'max:255'],
            'announce'              => ['nullable', 'string'],
            'text'                  => ['required', 'string'],
            'news_category_id'      => ['required', 'integer', 'max:25'],
            'news_scene_id'         => ['array', 'exists:news_scenes,id'],
            'news_scene_id.*'       => ['integer', 'exists:news_scenes,id'],
            'source_name'           => ['string', 'max:255', 'nullable'],
            'source_url'            => ['string', 'max:255', 'nullable'],
            'author_user_name'      => ['string', 'nullable'],
            'author_user_id'        => ['integer', 'nullable'],
            'vip'                   => ['in:on', 'max:2'],
            'yandex'                => ['in:on', 'max:2'],
        ]);

        Validator::make($request->all(), $rules)->validate();

    }


}
