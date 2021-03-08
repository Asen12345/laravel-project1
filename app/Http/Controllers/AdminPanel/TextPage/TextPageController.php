<?php

namespace App\Http\Controllers\AdminPanel\TextPage;

use App\Eloquent\TextPage;
use App\Http\PageContent\AdminPanel\Anons\AnonsPageContent;
use App\Http\PageContent\AdminPanel\TextPage\TextPagePageContent;
use App\Repositories\Back\AnonsRepository;
use App\Repositories\Back\TextPageRepository;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class TextPageController extends Controller
{
    public function __construct() {

    }
    public function index(Request $request) {

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


        $content = (new TextPagePageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $pages = (new TextPageRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.text_page.index', [
            'content'     => $content,
            'pages'       => $pages,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    public function create() {
        $content = (new TextPagePageContent())->editAndCreateContent();
        return view('admin_panel.text_page.create', [
            'content'     => $content,

        ]);
    }

    public function store(Request $request) {
        $this->validateForm($request);
        $request['published'] = $this->checkboxToBoolean($request->published);
        $textPage = (new TextPageRepository())->create($request->all());
        if (!empty($textPage->id)) {
            return redirect()->route('admin.text.page.index')
                ->with('success', 'Запись успешно создана');
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'При отправке произошла ошибка.']);
        }
    }

    public function edit($id) {
        $content = (new TextPagePageContent())->editAndCreateContent();
        $textPage   = (new TextPageRepository())->getById($id);
        return view('admin_panel.text_page.edit', [
            'content'    => $content,
            'textPage'   => $textPage
        ]);
    }

    public function update(Request $request, $id){
        $this->validateForm($request);
        $request['published'] = $this->checkboxToBoolean($request->published);
        $anons = (new TextPageRepository())->updateById($id, $request->all());
        if (!empty($anons->id)) {
            return redirect()->route('admin.text.page.index')
                ->with('success', 'Запись успешно создана');
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'При отправке произошла ошибка.']);
        }
    }

    /**
     * @param $id int
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id){
        $textPage = (new TextPageRepository())->deleteById($id);
        if ($textPage == true) {
            return redirect()->back()
                ->with('success', 'Запись успешно удалена');
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'При удалении произошла ошибка.']);
        }
    }

    public function validateForm ($request) {

        $rules = ([
            'title'            => ['required', 'string', 'max:255'],
            'text'             => ['nullable', 'string'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);
        $messages = [
            'required' => 'Поле :attribute обязателен к заполнению.',
        ];
        $names = [
            'title'            => "'Заголовок'",
            'text'             => "'Описание мероприятия'",
            'meta_title'       => "'Meta Title'",
            'meta_keywords'    => "'Meta Keywords'",
            'meta_description' => "'Meta Description'",
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $names)->validate();

        return $validator;

    }

}
