<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\CompanyType;
use App\Http\PageContent\AdminPanel\Resources\CompanyTypePageContent;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CompanyTypeController extends Controller
{
    public function index(Request $request) {
        if (empty($request->sort_by)) {
            $sortBy = 'name';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'asc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $type = CompanyType::orderBy($sortBy, $sortOrder)
            ->paginate('50', array('id', 'name'));


        $content = (new CompanyTypePageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));
        return view('admin_panel.resources.company_type.index', [
            'content' => $content,
            'data'    => $type,
        ]);
    }

    public function create()
    {
        $content = (new CompanyTypePageContent())->editAndCreateUserPageContent();

        return view('admin_panel.resources.company_type.create', [
            'content'        => $content,
        ]);
    }

    public function store(Request $request) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();
        CompanyType::create($request->all());
        return redirect()
            ->route('admin.resources.company.type')
            ->with('success', 'Запись успешно добавлена.');
    }


    public function edit($id) {
        $content = (new CompanyTypePageContent())->editAndCreateUserPageContent();
        $type = CompanyType::find($id);
        return view('admin_panel.resources.company_type.edit', [
            'content'  => $content,
            'type'     => $type,
        ]);
    }

    public function update(Request $request , $id) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();

        DB::beginTransaction();
        try{
            CompanyType::find($id)->update($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }
        return redirect()
            ->route('admin.resources.company.type')
            ->with('success', 'Запись успешно обновлена.');
    }

    public function destroy($id) {
        DB::beginTransaction();
        try{
            CompanyType::find($id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }

        return redirect()
            ->back()
            ->with('success', 'Запись успешно удалена.');
    }
}
