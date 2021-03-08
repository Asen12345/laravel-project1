<?php

namespace App\Http\Controllers\AdminPanel\News;

use App\Eloquent\Mailing;
use App\Eloquent\MailingUser;
use App\Eloquent\NewsCategory;
use App\Eloquent\User;
use App\Http\PageContent\AdminPanel\News\CategoryPageContent;
use App\Repositories\Back\News\CategoryRepository;
use App\Repositories\Back\News\NewsRepository;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Storage;

class CategoryController extends Controller
{

    protected $role;
    protected $redactorPermittedCat;

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

    public function index (Request $request) {

        if (empty($request->sort_by)) {
            $sortBy = 'sort';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'asc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $content = (new CategoryPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        if ($this->role == 'redactor') {
            if (Gate::allows('permission', 'all_news')){
                $categories = (new CategoryRepository())
                    ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                    ->orderBy($sortBy, $sortOrder)
                    ->where('parent_id', 0)
                    ->paginate('50', array('id', 'name', 'sort', 'created_at', 'parent_id'));
            } else {
                $categories = (new CategoryRepository())
                    ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                    ->whereIn('id', $this->redactorPermittedCat)
                    ->orderBy($sortBy, $sortOrder)
                    ->paginate('50', array('id', 'name', 'sort', 'created_at', 'parent_id'));
            }

        } else {
            $categories = (new CategoryRepository())
                ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                ->orderBy($sortBy, $sortOrder)
                ->where('parent_id', 0)
                ->paginate('50', array('id', 'name', 'sort', 'created_at', 'parent_id'));
        }


        $filter_data = $request->except('_token');

        return view('admin_panel.news.category.index', [
            'content'     => $content,
            'categories'  => $categories,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $content = (new CategoryPageContent())->editAndCreateUserPageContent();
        $categories = NewsCategory::where('parent_id', 0)->orderBy('sort', 'asc')->get();
        return view('admin_panel.news.category.create', [
            'content'     => $content,
            'categories'  => $categories
        ]);
    }

    public function store(Request $request) {

        $rules = ([
            'name'             => ['required', 'string', 'max:255'],
            'url_ru'           => ['required', 'string', 'max:255'],
            'url_en'           => ['required', 'string', 'max:255'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'sort'             => ['required', 'integer', 'max:255'],
            'parent_id'        => ['integer', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();

        (new CategoryRepository())->create($request->all());

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория успешно добавлена.');
    }


    public function edit($id) {
        $content = (new CategoryPageContent())->editAndCreateUserPageContent();
        $categories = NewsCategory::where('parent_id', 0)
            ->where('id', '!=', $id)
            ->orderBy('sort', 'asc')
            ->get();
        $category = (new CategoryRepository())->getById($id);

        return view('admin_panel.news.category.edit', [
            'content'    => $content,
            'categories' => $categories,
            'category'   => $category,
        ]);
    }

    public function update(Request $request , $id) {

        $rules = ([
            'name'             => ['required', 'string', 'max:255'],
            'url_ru'           => ['required', 'string', 'max:255'],
            'url_en'           => ['required', 'string', 'max:255'],
            'sort'             => ['required', 'integer', 'max:255'],
            'parent_id'        => ['integer', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();

        (new CategoryRepository())->updateById($id, $request->all());

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория успешно обновлена.');
    }

    public function destroy(Request $request, $id) {
        if (empty($request->new_category)) {
            return redirect()
                ->back()
                ->withErrors(['Не выбрана новая категория для новостей.']);
        }
        $category = (new CategoryRepository())->getById($id);
        DB::beginTransaction();
        try {
            foreach ($category->news as $news) {
                $news->update([
                    'news_category_id' => $request->new_category,
                ]);
            }
            $category->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()
                ->back()
                ->withErrors([$id_index]);
        }

        return redirect()
            ->back()
            ->with('success', 'Категория успешно удалена.');
    }
}
