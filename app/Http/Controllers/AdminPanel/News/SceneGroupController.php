<?php

namespace App\Http\Controllers\AdminPanel\News;

use App\Http\PageContent\AdminPanel\News\SceneGroupPageContent;
use App\Repositories\Back\News\SceneGroupRepository;
use App\Repositories\Back\News\SceneRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SceneGroupController extends Controller
{
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

        $content = (new SceneGroupPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $scene_groups = (new SceneGroupRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50', array('id', 'name', 'sort', 'created_at'));
        $filter_data = $request->except('_token');

        return view('admin_panel.news.scene_group.index', [
            'content'      => $content,
            'scene_groups' => $scene_groups,
            'filter_data'  => $filter_data,
            'sort_by'      => $sortBy,
            'sort_order'   => $sortOrder
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $content = (new SceneGroupPageContent())->editAndCreateUserPageContent();

        return view('admin_panel.news.scene_group.create', [
            'content'        => $content,
        ]);
    }

    public function store(Request $request) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
            'sort'    => ['required', 'integer', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();

        (new SceneGroupRepository())->create($request->all());

        return redirect()
            ->route('admin.scene-group.index')
            ->with('success', 'Сюжетная группа успешно добавлена.');
    }


    public function edit($id) {
        $content = (new SceneGroupPageContent())->editAndCreateUserPageContent();

        $scene_group = (new SceneGroupRepository())->getById($id);

        return view('admin_panel.news.scene_group.edit', [
            'content'      => $content,
            'scene_group'  => $scene_group,
        ]);
    }

    public function update(Request $request , $id) {

        $rules = ([
            'name'     => ['required', 'string', 'max:255'],
            'sort'    => ['required', 'integer', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();

        (new SceneGroupRepository())->updateById($id, $request->all());

        return redirect()
            ->route('admin.scene-group.index')
            ->with('success', 'Сюжетная группа успешно обновлена.');
    }

    public function destroy($id) {

        $group = (new SceneGroupRepository())->getById($id);
        $scenes = $group->newsScene;
        foreach ($scenes as $scene) {
            $scene->update(['news_scene_group_id'  => null]);
        }
        $group->delete();

        return redirect()
            ->back()
            ->with('success', 'Сюжетная группа успешно удалена.');
    }
}
