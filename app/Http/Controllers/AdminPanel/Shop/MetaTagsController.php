<?php

namespace App\Http\Controllers\AdminPanel\Shop;

use App\Eloquent\MetaTags;
use App\Http\PageContent\AdminPanel\Resources\SettingPageContent;
use App\Repositories\Back\MetaTagsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetaTagsController extends Controller
{
    public function index () {

        $content = (new SettingPageContent())->metaTagsPageContent();

        $templates = MetaTags::where('meta_id', 'meta_researches_main')
            ->orWhere('meta_id', 'meta_researches_author')
            ->orderBy('id', 'asc')->paginate(50);

        return view('admin_panel.shop.meta_tags.meta_tags', ['content' => $content, 'templates' => $templates]);

    }

    public function edit($id) {

        $content = (new SettingPageContent())->metaTagsEditPageContent();

        $template = (new MetaTagsRepository())->getById($id);

        return view('admin_panel.shop.meta_tags.meta_tags_edit', [
            'content'  => $content,
            'template' => $template
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'              => 'bail|required|max:100',
            'meta_title'        => 'required|min:3|max:1000',
            'meta_keywords'     => 'required|min:3|max:1000',
            'meta_description'  => 'required|min:3|max:1000',
        ]);

        (new MetaTagsRepository())->updateById($id, $data);

        return redirect()
            ->route('admin.shop.researches.settings.metatags')
            ->with('success', 'Успешно сохранено.');
    }

}
