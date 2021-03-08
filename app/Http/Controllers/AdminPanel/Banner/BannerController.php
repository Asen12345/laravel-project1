<?php

namespace App\Http\Controllers\AdminPanel\Banner;

use App\Eloquent\Anons;
use App\Eloquent\Banner;
use App\Eloquent\BannerPlace;
use App\Eloquent\Blog;
use App\Http\PageContent\AdminPanel\Banner\BannerPageContent;
use App\Repositories\Back\BannerRepository;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;
use Validator;

class BannerController extends Controller
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

        $content = (new BannerPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));
        $banners = (new BannerRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->with(['bannerPlace'])
            ->orderBy($sortBy, $sortOrder)
            ->paginate('50');

        $filter_data = $request->except('_token');

        return view('admin_panel.banner.index', [
            'content'     => $content,
            'banners'     => $banners,
            'filter_data' => $filter_data,
            'sort_by'     => $sortBy,
            'sort_order'  => $sortOrder
        ]);
    }

    public function create() {
        $content = (new BannerPageContent())->editAndCreateContent();
        $bannerPlace = BannerPlace::all();
        return view('admin_panel.banner.create', [
            'content'      => $content,
            'bannerPlace'  => $bannerPlace,

        ]);
    }

    public function store(Request $request) {
        if (in_array($request->banner_place_id, [4, 12, 17])){
            $blogAnnounceId = $request->input_group;
        } else {
            $blogAnnounceId = null;
        }
        $fileImage = $request->file('image');
        $filename = time() . '_' . $fileImage->getClientOriginalName();
        Storage::disk('public')->makeDirectory('banners');
        $urlImage = Storage::disk('public')->putFileAs('banners', $fileImage, $filename);

        $request['published'] = $this->checkboxToBoolean($request->published);

        DB::beginTransaction();
        try {
            $banner = Banner::create([
                'name'             => $request->name,
                'published'        => $request->published,
                'banner_place_id'  => $request->banner_place_id,
                'blog_announce_id' => $blogAnnounceId,
                'date_from'        => $request->date_from,
                'date_to'          => $request->date_to,
                'image'            => $urlImage,
                'link'             => $request->link ?? null,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Storage::disk('public')->delete($urlImage);
        }

        if (!empty($banner->id)) {
            return redirect()->route('admin.banner.index')
                ->with('success', 'Запись успешно создана');
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'При отправке произошла ошибка']);
        }
    }

    public function edit($id) {
        $content = (new BannerPageContent())->editAndCreateContent();
        $banner   = (new BannerRepository())->getById($id);
        $bannerPlace = BannerPlace::all();
        if ($banner->banner_place_id == 12 || $banner->banner_place_id == 4) {
            $blogAnnounceIdValue = Blog::where('id', $banner->blog_announce_id)
                ->first()->subject ?? null;
        } elseif ($banner->banner_place_id == 17) {
            $blogAnnounceIdValue = Anons::where('id', $banner->blog_announce_id)
                ->first()->title ?? null;
        }
        return view('admin_panel.banner.edit', [
            'content'             => $content,
            'bannerPlace'         => $bannerPlace,
            'banner'              => $banner,
            'blogAnnounceIdValue' => $blogAnnounceIdValue ?? null,
        ]);
    }

    public function update(Request $request, $id){
        $banner = (new BannerRepository())->getById($id);
        $request['published'] = $this->checkboxToBoolean($request->published);
        if (in_array($request->banner_place_id, [4, 12, 17])){
            $blogAnnounceId = $request->input_group;
        } else {
            $blogAnnounceId = null;
        }
        $fileImage = $request->file('image');
        if (!empty($request->file('image'))) {
            $oldImage = 'banners/' . basename($banner->image);
            $filenameImage = time() . '_' . $fileImage->getClientOriginalName();
            Storage::disk('public')->makeDirectory('banners');
            $urlImage = Storage::disk('public')->putFileAs('banners', $fileImage, $filenameImage);
            if (!empty($urlImage)){
                Storage::disk('public')->delete($oldImage);
            }
        }

        DB::beginTransaction();
        try {
            Banner::find($id)->update([
                'name'             => $request->name,
                'published'        => $request->published,
                'banner_place_id'  => $request->banner_place_id,
                'blog_announce_id' => $blogAnnounceId,
                'date_from'        => $request->date_from,
                'date_to'          => $request->date_to,
                'link'             => $request->link,
            ]);
            if (!empty($urlImage)) {
                Banner::where('id', $id)->update(['image'  => $urlImage]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            if (!empty($urlImage)){
                Storage::disk('public')->delete($urlImage);
            }
            if (!empty($urlImagePlug)) {
                Storage::disk('public')->delete($urlImagePlug);
            }

            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.banner.index')
            ->with('success', 'Запись успешно создана');

    }

    /**
     * @param $id int
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id){
        $banner = Banner::find($id);
        if (!empty($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        $res = $banner->delete();
        if ($res == true) {
            return redirect()->back()
                ->with('success', 'Запись успешно удалена');
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'При удалении произошла ошибка']);
        }
    }

    public function clearClearStatistic($id)
    {
        $banner = Banner::find($id);
        $banner->viewBanner()->delete();
        $banner->click = 0;
        $banner->save();
        return redirect()->back()
            ->with('success', 'Статистика успешно очищена');
    }

    public function ajaxUpload(Request $request)
    {
        $subject = $request->input('subject');

        if ($request->id == 12 || $request->id == 4) {
            $query = Blog::whereNotNull('id');

            if($subject !== '') {
                $query->where('subject', 'like', '%'.$subject.'%');
            }

            $blogs = $query->select('id', 'subject')->get();
            return response()->json($blogs);
        }

        if ($request->id == 17) {
            $query = Anons::whereNotNull('id');

            if ($subject !== '') {
                $query->where('title', 'like', '%'.$subject.'%');
            }

            $blogs = $query->select('id', 'title')->get();

            $blogs->map(function($page) {
                $page['subject'] = $page['title'];
                return $page;
            });
            return response()->json($blogs);
        }
    }

    public function ajaxSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'integer|required',
            'value'     => 'integer|required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('success' => 'false', 'error' => $validator->errors()->first()));
        }

        BannerPlace::find($request->id)
            ->update(['view_count' => $request->value]);

        return response()->json(array('success' => 'ok', 'mess' => 'Запись успешно обновлена'));
    }

    public function settings()
    {
        $content = (new BannerPageContent())->settingPageContent();

        $places = BannerPlace::all();

        return view('admin_panel.banner.setting', [
            'content'     => $content,
            'places'      => $places,
        ]);
    }
}
