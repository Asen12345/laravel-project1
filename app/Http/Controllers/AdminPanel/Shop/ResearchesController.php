<?php

namespace App\Http\Controllers\AdminPanel\Shop;

use App\Eloquent\Researches;
use App\Eloquent\ResearchesAuthor;
use App\Eloquent\ShopCategory;
use App\Http\PageContent\AdminPanel\Shop\ResearchesPageContent;
use App\Repositories\Back\Shop\ResearchesRepository;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Storage;
use Validator;

class ResearchesController extends Controller
{
    public function index (Request $request)
    {
        $parameters = $request->all();

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

        $content = (new ResearchesPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $researches = (new ResearchesRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->with('author')
            ->orderBy($sortBy, $sortOrder)
            ->paginate('30');

        if (count($parameters) > 0) {
            $researches->appends($parameters);
        }

        return view('admin_panel.shop.researches.index', [
            'content'      => $content,
            'researches'   => $researches,
            'sort_by'      => $sortBy,
            'sort_order'   => $sortOrder
        ]);
    }

    public function create()
    {
        $content = (new ResearchesPageContent())->editAndCreateAuthorPageContent();
        $categories = ShopCategory::with(['child' => function ($query) {
            $query->orderBy('sort', 'ASC');
        }])->orderBy('sort', 'ASC')->get();

        $authors = ResearchesAuthor::all();

        return view('admin_panel.shop.researches.create', [
            'content'        => $content,
            'categories'     => $categories,
            'authors'        => $authors,
        ]);
    }

    public function store (Request $request) {

        $this->validateRequest($request);

        $request['main'] = $this->checkboxToBoolean($request->main);

        DB::beginTransaction();
        try {
            if (!empty($request->file('file_researches'))){
                $file_content = $request->file('file_researches');
                $filename = time() . '.' . $file_content->getClientOriginalExtension();
                $dir_name = '/shop/file/' . Carbon::now()->format('Y-m-d');
                Storage::disk('shop')->makeDirectory($dir_name);
                $url_file = Storage::disk('shop')->putFileAs($dir_name, $file_content, $filename);
                //$url_file = Storage::url('file_upload' . $dir_name. '/' . $filename);
                $request['file'] = '/file_upload/' . $url_file;
            }
            if (!empty($request->file('demo_researches'))){
                $fileDemo      = $request->file('demo_researches');
                $filenameDemo  = time() . '.' . $fileDemo->getClientOriginalExtension();
                $dirNameDemo   = '/shop/demo/' . Carbon::now()->format('Y-m-d');
                Storage::disk('shop')->makeDirectory($dirNameDemo);
                $urlDemo = Storage::disk('shop')->putFileAs($dirNameDemo, $fileDemo, $filenameDemo);
                //$urlDemo = Storage::url( 'file_upload' . $dirNameDemo. '/' . $filenameDemo);
                $request['demo_file'] = '/file_upload/' . $urlDemo;
            }

            $research = (new ResearchesRepository())->create($request->except(['_token', 'x1', 'x2', 'y1', 'y2' , 'img_width', 'img_height', 'image_hidden_file', 'shop_category_id']));

            $research->category()
                ->sync($request->shop_category_id);;

            DB::commit();
        } catch (Exception $e) {
            if (!empty($url_demo)) {
                Storage::disk('private')->delete($url_demo);
            }
            if (!empty($url_file)) {
                Storage::disk('private')->delete($url_file);
            }
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withInput()
                ->withErrors([$id_index]);
        }

        if($request->save_and_stay) {
            return redirect()->back()
            ->with('success', 'Исследование успешно добавлено.');
        }

        return redirect()
            ->route('admin.shop.researches')
            ->with('success', 'Исследование успешно добавлено.');

    }

    public function update (Request $request, $id) {

        $this->validateRequest($request);

        $request['main'] = $this->checkboxToBoolean($request->main);

        DB::beginTransaction();
        try {
            if (!empty($request->file('file_researches'))){
                $file_content = $request->file('file_researches');
                $filename = time() . '.' . $file_content->getClientOriginalExtension();
                $dir_name = '/shop/file/' . Carbon::now()->format('Y-m-d');
                Storage::disk('shop')->makeDirectory($dir_name);
                $url_file = Storage::disk('shop')->putFileAs($dir_name, $file_content, $filename);
                //$url_file = Storage::url('file_upload' . $dir_name. '/' . $filename);
                $request['file'] = '/file_upload/' . $url_file;
            }
            if (!empty($request->file('demo_researches'))){
                $fileDemo      = $request->file('demo_researches');
                $filenameDemo  = time() . '.' . $fileDemo->getClientOriginalExtension();
                $dirNameDemo   = '/shop/demo/' . Carbon::now()->format('Y-m-d');
                Storage::disk('shop')->makeDirectory($dirNameDemo);
                $urlDemo = Storage::disk('shop')->putFileAs($dirNameDemo, $fileDemo, $filenameDemo);
                //$urlDemo = Storage::url( 'file_upload' . $dirNameDemo. '/' . $filenameDemo);
                $request['demo_file'] = '/file_upload/' . $urlDemo;
            }

            (new ResearchesRepository())->updateById($id, $request->except(['_token', 'x1', 'x2', 'y1', 'y2' , 'img_width', 'img_height', 'image_hidden_file', 'download', 'shop_category_id']));

            Researches::find($id)
                ->category()
                ->sync($request->shop_category_id);

            DB::commit();
        } catch (Exception $e) {
            if (!empty($url_demo)) {
                Storage::disk('private')->delete($url_demo);
            }
            if (!empty($url_file)) {
                Storage::disk('private')->delete($url_file);
            }
            DB::rollBack();
            $id_index = $e->getMessage();
            return redirect()->back()
                ->withInput()
                ->withErrors([$id_index]);
        }

        if($request->save_and_stay) {
            return redirect()->back()
            ->with('success', 'Исследование успешно обновлено.');
        }

        return redirect()
            ->route('admin.shop.researches')
            ->with('success', 'Исследование успешно обновлено.');

    }


    public function edit($id)
    {
        $content = (new ResearchesPageContent())->editAndCreateAuthorPageContent();

        $researches = Researches::find($id);

        $categories = ShopCategory::with(['child' => function ($query) {
            $query->orderBy('sort', 'ASC');
        }])->orderBy('sort', 'ASC')->get();

        $authors = ResearchesAuthor::all();

        return view('admin_panel.shop.researches.edit', [
            'content'        => $content,
            'categories'     => $categories,
            'authors'        => $authors,
            'researches'     => $researches
        ]);
    }


    public function destroy($id)
    {
        $researches = Researches::find($id);

        if (isset($researches->url_demo)) {
            Storage::disk('private')->delete($researches->url_demo);
        }
        if (isset($researches->url_file)) {
            Storage::disk('private')->delete($researches->url_file);
        }
        $researches->delete();

        return redirect()
            ->route('admin.shop.researches')
            ->with('success', 'Исследование успешно удалено.');
    }


    public function preUploadImage(Request $request) {
        //The value is in kilobytes. I.e. max:10240 = max 10 MB.
        $max_size_file = 5;
        //Format for upload file.
        $format = ['jpg', 'jpeg', 'JPG', 'JPEG'];

        $file_content = $request->file('image');
        $file_size = $file_content->getSize();
        $extension = $file_content->getClientOriginalExtension();

        if ($file_size > ($max_size_file * 1048576)) {
            return response()->json([
                'is'    => 'failed',
                'error' => 'Слишком большой файл. Размер файла не должен превышать ' .  $max_size_file . ' MB.'
            ]);
        }

        if (!in_array($extension, $format)) {
            return response()->json([
                'is'    => 'failed',
                'error' => 'Неверный формат файла. Поддерживается только jpeg.'
            ]);
        }

        $filename = time() . '_' . $file_content->getClientOriginalName();

        Storage::disk('public')->makeDirectory('upload_tmp');

        $folder_file = Storage::disk('public')->putFileAs(
            'upload_tmp', $file_content, $filename
        );

        return response()->json([
            'is'   => 'success',
            'path' => Storage::url($folder_file)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $directory = 'researches/image/'. Carbon::now()->format('Y-m-d');

        $image_path = public_path() . $request->input('img_path');
        $file_name = basename($image_path);

        $im = Image::make($image_path);

        $img_width = $request->input('img_width');
        $img_height = $request->input('img_height');
        $x1 = $request->input('x1');
        $x2 = $request->input('x2');
        $y1 = $request->input('y1');
        $y2 = $request->input('y2');
        $scaleX = ($im->width() / $img_width);
        $scaleY = ($im->height() / $img_height);
        $crop_width = ($x2 - $x1) * $scaleX;
        $crop_height = ($y2 - $y1) * $scaleY;

        $im->crop((int)$crop_width, (int)$crop_height, (int)($x1 * $scaleX), (int)($y1 * $scaleY));

        Storage::disk('public')->makeDirectory($directory);
        $im->save(public_path() . '/storage/'.$directory.'/' . $file_name, 60);

        $path_file = Storage::url($directory.'/' . $file_name);

        if (!empty($path_file)){
            Storage::disk('public')->delete('upload_tmp/' . $file_name);
        }

        return response()->json([
            'is'   => 'success',
            'path' => $path_file
        ]);

    }

    protected function validateRequest (Request $request) {
        $rules = ([
            'title'                 => ['required', 'string', 'max:255'],
            'image'                 => ['nullable', 'string', 'max:255'],
            'meta_title'            => ['nullable', 'string', 'max:255'],
            'meta_keywords'         => ['nullable', 'max:255'],
            'meta_description'      => ['nullable', 'max:255'],
        ]);

        Validator::make($request->all(), $rules)->validate();
    }



}
