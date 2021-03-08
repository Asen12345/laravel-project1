<?php

namespace App\Http\Controllers\AdminPanel\Shop;

use App\Eloquent\NewsCategory;
use App\Eloquent\ShopCategory;
use App\Http\PageContent\AdminPanel\Shop\CategoryPageContent;
use App\Repositories\Back\News\CategoryRepository;
use App\Repositories\Back\Shop\ShopCategoryRepository;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Validator;

class CategoryController extends Controller
{
    public function index (Request $request) {

        if (empty($request->sort_by)) {
            $sortBy = '-sort';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $content = (new CategoryPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $categories = (new ShopCategoryRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->with(['child' => function ($query) {
                $query->orderBy('sort', 'ASC');
            }])
            ->withCount('child')
            ->orderByRaw($sortBy. ' ' .$sortOrder)
            ->where('parent_id', 0)
            ->paginate('50', array('id', 'name', 'sort', 'created_at', 'parent_id'));

        $filter_data = $request->except('_token');

        return view('admin_panel.shop.category.index', [
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
        $categories = ShopCategory::where('parent_id', 0)->orderBy('sort', 'asc')->get();
        return view('admin_panel.shop.category.create', [
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
            'parent_id'        => ['integer', 'max:255'],
        ]);

        $request['show'] = $this->checkboxToBoolean($request->show);
        Validator::make($request->all(), $rules)->validate();

        (new ShopCategoryRepository())->create($request->all());

        return redirect()
            ->route('admin.shop.researches.category')
            ->with('success', 'Категория успешно добавлена.');
    }

    public function edit($id) {
        $content = (new CategoryPageContent())->editAndCreateUserPageContent();
        $categories = ShopCategory::where('parent_id', 0)
            ->where('id', '!=', $id)
            ->orderBy('sort', 'asc')
            ->get();
        $category = (new ShopCategoryRepository())->getById($id);

        return view('admin_panel.shop.category.edit', [
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
            'parent_id'        => ['integer', 'max:255'],
        ]);
        $request['show'] = $this->checkboxToBoolean($request->show);
        Validator::make($request->all(), $rules)->validate();

        (new ShopCategoryRepository())->updateById($id, $request->all());

        return redirect()
            ->route('admin.shop.researches.category')
            ->with('success', 'Категория успешно обновлена.');
    }

    public function destroy(Request $request, $id) {

        if (empty($request->new_category)) {
            return redirect()
                ->back()
                ->withErrors(['Не выбрана новая категория.']);
        }
        $category = ShopCategory::find($id);

        if (!$category->child->isEmpty()){
            return redirect()
                ->back()
                ->withErrors(['Нельзя удалить родительскую категорию.']);
        }

        DB::beginTransaction();
        try {
            if (!$category->researches->isEmpty()) {
                foreach ($category->researches as $researches) {
                    $researches->update([
                        'shop_category_id' => $request->new_category,
                    ]);
                }
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
