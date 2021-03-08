<?php

namespace App\Http\Controllers\AdminPanel\Resources;

use App\Eloquent\Tag;
use App\Http\PageContent\AdminPanel\Resources\TagsPageContent;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TagsController extends Controller
{
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

        $tags = Tag::orderBy($sortBy, $sortOrder)
            ->paginate('50', array('id', 'name'));


        $content = (new TagsPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));
        return view('admin_panel.resources.tags.index', [
            'content' => $content,
            'data'    => $tags,
        ]);
    }

    public function create()
    {
        $content = (new TagsPageContent())->editAndCreateUserPageContent();

        return view('admin_panel.resources.tags.create', [
            'content'        => $content,
        ]);
    }

    public function store(Request $request) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();
        Tag::create($request->all());
        return redirect()
            ->route('admin.resources.tags')
            ->with('success', 'Тег успешно добавлен.');
    }


    public function edit($id) {
        $content = (new TagsPageContent())->editAndCreateUserPageContent();
        $tag = Tag::find($id);
        return view('admin_panel.resources.tags.edit', [
            'content'  => $content,
            'tag'      => $tag,
        ]);
    }

    public function update(Request $request , $id) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();

        $tag = Tag::find($id);
        $tag->update($request->all());

        return redirect()
            ->route('admin.resources.tags')
            ->with('success', 'Тег успешно обновлен.');
    }

    public function destroy($id) {
        DB::beginTransaction();
        try{
            $tag = Tag::find($id);
            $tag->tagsRecords()->delete();
            $tag->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withErrors(['error' => $id_index]);
        }

        return redirect()
            ->back()
            ->with('success', 'Тег успешно удален.');
    }
}
