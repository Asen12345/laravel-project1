<?php

namespace App\Http\Controllers\AdminPanel\Anons;

use App\Eloquent\Anons;
use App\Http\PageContent\AdminPanel\Anons\AnonsPageContent;
use App\Repositories\Back\AnonsRepository;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AnonsController extends Controller
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


        $content = (new AnonsPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $anonses = (new AnonsRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.anons.index', [
            'content'     => $content,
            'anonses'     => $anonses,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    public function create() {
        $content = (new AnonsPageContent())->editAndCreateContent();
        return view('admin_panel.anons.create', [
            'content'     => $content,

        ]);
    }

    public function store(Request $request) {
        $this->validateForm($request);
        $request['main'] = $this->checkboxToBoolean($request->main);
        $anons = (new AnonsRepository())->create($request->all());
        if (!empty($anons->id)) {
            return redirect()->route('admin.anons.index')
                ->with('success', 'Запись успешно создана');
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'При отправке произошла ошибка.']);
        }
    }

    public function edit($id) {
        $content = (new AnonsPageContent())->editAndCreateContent();
        $anons   = (new AnonsRepository())->getById($id);
        return view('admin_panel.anons.edit', [
            'content' => $content,
            'anons'   =>$anons
        ]);
    }

    public function update(Request $request, $id){
        $this->validateForm($request);
        $request['main'] = $this->checkboxToBoolean($request->main);
        $anons = (new AnonsRepository())->updateById($id, $request->all());
        if (!empty($anons->id)) {
            return redirect()->route('admin.anons.index')
                ->with('success', 'Запись успешно обновлена');
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
        $anons = (new AnonsRepository())->deleteById($id);
        if ($anons == true) {
            return redirect()->back()
                ->with('success', 'Запись успешно удалена');
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'При удалении произошла ошибка.']);
        }
    }

    public function checkBox(Request $request) {
        DB::beginTransaction();
        try {
            (new AnonsRepository())->getById($request->id)
                ->update([
                    'main' => $request->active,
                ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return response()->json(array('success' => 'false', 'error' => $id_index));
        }

        return response()->json(array('success' => 'ok', 'mess' => 'Запись успешно обновлена.'));

    }

    public function validateForm ($request) {

        $rules = ([
            'title'            => ['required', 'string', 'max:255'],
            'date'             => ['required', 'string', 'max:255'],
            'will_end'         => ['required', 'string', 'max:255'],
            'place'            => ['nullable', 'string', 'max:255'],
            'organizer'        => ['nullable', 'string', 'max:255'],
            'text'             => ['nullable', 'string'],
            'reg_linc'         => ['nullable', 'string', 'max:255'],
            'price'            => ['nullable', 'string', 'max:50'],
            'main'             => ['nullable', 'string', 'max:2'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);
        $messages = [
            'required' => 'Поле :attribute обязателен к заполнению.',
        ];
        $names = [
            'title'            => "'Заголовок'",
            'date'             => "'Дата мероприятия'",
            'will_end'         => "'Дата удаления'",
            'place'            => "'Место'",
            'organizer'        => "'Организатор'",
            'text'             => "'Описание мероприятия'",
            'reg_linc'         => "'Ссылка на регистрацию'",
            'price'            => "'Стоимость'",
            'main'             => "'На главной'",
            'meta_title'       => "'Meta Title'",
            'meta_keywords'    => "'Meta Keywords'",
            'meta_description' => "'Meta Description'",
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $names)->validate();

        return $validator;

    }

}
