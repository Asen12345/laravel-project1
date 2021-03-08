<?php

namespace App\Http\Controllers\AdminPanel\Widgets;

use App\Eloquent\Anons;
use App\Eloquent\Banner;
use App\Eloquent\BannerPlace;
use App\Eloquent\Blog;
use App\Eloquent\Widget;
use App\Http\PageContent\AdminPanel\Banner\BannerPageContent;
use App\Http\PageContent\AdminPanel\Widgets\WidgetPageContent;
use App\Repositories\Back\BannerRepository;
use App\Repositories\Back\WidgetRepository;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;
use Validator;

class WidgetController extends Controller
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

        $content = (new WidgetPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $banners = (new WidgetRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order'))
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.widgets.index', [
            'content'     => $content,
            'banners'     => $banners,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    public function edit($id) {
        $content = (new WidgetPageContent())->editAndCreateContent();
        $widget   = (new WidgetRepository())->getById($id);

        if (isset($widget->blogs_id)) {
            $blogs = Blog::whereIn('id', json_decode($widget->blogs_id))->get();
        } else {
            $blogs = Blog::whereNotNull('id')->get();
        }

        return view('admin_panel.widgets.edit', [
            'content'             => $content,
            'widget'              => $widget,
            'blogs'               => $blogs,
            'blogAnnounceIdValue' => $blogAnnounceIdValue ?? null,
        ]);
    }

    public function update(Request $request, $id){

        $published = !empty($request->published) ? true : false;
        $all_blogs = !empty($request->all_blogs) ? true : false;

        Widget::find($id)->update([
            'name'       => $request->name,
            'published'  => $published,
            'all_blogs'  => $all_blogs,
            'blogs_id'   => ($request->blogs_id)? json_encode($request->blogs_id) : null,
            'text'       => $request->text,
        ]);


        return redirect()->route('admin.widgets.index')
            ->with('success', 'Запись успешно создана');

    }

    public function ajaxUpload(Request $request)
    {
        $data = Blog::where('subject', 'LIKE', '%' . $request->value . '%')
            ->select('subject', 'id')->take(5)->get();
        $data = array_merge((array(['title' => 'Нет', 'id' => ''])), $data->toArray());
        return response()->json($data);
    }
}
