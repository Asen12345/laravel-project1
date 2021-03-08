<?php

namespace App\Http\Controllers\AdminPanel\News;

use App\Eloquent\NewsSceneGroup;
use App\Http\PageContent\AdminPanel\News\ScenePageContent;
use App\Repositories\Back\News\SceneRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SceneController extends Controller
{
    public function __construct() {

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
            $sortOrder = 'asc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $content = (new ScenePageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $scenes = (new SceneRepository());

        if (count($parameters) > 0) {
            $scenes = $scenes->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
                ->orderBy($sortBy, $sortOrder)->paginate(50);
            $scenes->appends($parameters);
        } else {
            $scenes = $scenes->orderBy($sortBy, $sortOrder)->paginate(50);
        }

        $scene_groups = NewsSceneGroup::orderBy('sort', 'asc')->get();

        return view('admin_panel.news.scene.index', [
            'content'      => $content,
            'scenes'       => $scenes,
            'scene_groups' => $scene_groups,
            'sort_by'      => $sortBy,
            'sort_order'   => $sortOrder
        ]);
    }

    public function create()
    {
        $content = (new ScenePageContent())->editAndCreateUserPageContent();
        $scene_groups = NewsSceneGroup::orderBy('sort', 'asc')->get();
        return view('admin_panel.news.scene.create', [
            'content'        => $content,
            'scene_groups'   => $scene_groups,
        ]);
    }

    public function edit($id){

        $content      = (new ScenePageContent())->editAndCreateUserPageContent();
        $scene_groups = NewsSceneGroup::orderBy('sort', 'asc')->get();
        $scene        = (new SceneRepository())->getById($id);

        return view('admin_panel.news.scene.edit', [
            'content'      => $content,
            'scene'        => $scene,
            'scene_groups' => $scene_groups,
        ]);
    }


    public function update(Request $request, $id) {

        $this->validateRequest($request);

        (new SceneRepository())->updateById($id, $request->all());

        return redirect()
            ->route('admin.scene.index')
            ->with('success', 'Сюжет успешно обновлен.');
    }

    public function store (Request $request) {

        $this->validateRequest($request);

        (new SceneRepository())->create($request->except(['_token', 'x1', 'x2', 'y1', 'y2' , 'img_width', 'img_height', 'image_hidden_file']));

        return redirect()
            ->route('admin.scene.index')
            ->with('success', 'Сюжет успешно добавлен.');

    }

    public function destroy ($id) {
        (new SceneRepository())->deleteById($id);
        return redirect()
            ->back()
            ->with('success', 'Сюжет успешно удален.');
    }

    protected function validateRequest (Request $request) {
        $rules = ([
            'name'                  => ['required', 'string', 'max:255'],
            'news_scene_group_id'   => ['nullable', 'integer', 'max:10'],
            'image'                 => ['nullable', 'string', 'max:255'],
            'meta_title'            => ['nullable', 'string', 'max:255'],
            'meta_keywords'         => ['nullable', 'max:255'],
            'meta_description'      => ['nullable', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();
    }
}
