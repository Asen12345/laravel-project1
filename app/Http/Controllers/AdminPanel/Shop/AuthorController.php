<?php

namespace App\Http\Controllers\AdminPanel\Shop;

use App\Eloquent\NewsSceneGroup;
use App\Http\PageContent\AdminPanel\News\ScenePageContent;
use App\Http\PageContent\AdminPanel\Shop\AuthorPageContent;
use App\Repositories\Back\News\SceneRepository;
use App\Repositories\Back\Shop\AuthorRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Storage;
use Validator;

class AuthorController extends Controller
{
    public function index (Request $request)
    {
        $parameters = $request->all();

        if (empty($request->sort_by)) {
            $sortBy = '-sort';
        } else {
            $sortBy = $request->sort_by;
        }
        if (empty($request->sort_order)) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = $request->sort_order;
        }

        $content = (new AuthorPageContent())->homePageContent($request->except('_token', 'sort_by', 'sort_order'));

        $authors = (new AuthorRepository())
            ->multiSort($request->except('_token', 'sort_by', 'sort_order', 'page'))
            ->withCount('researches')
            ->orderByRaw($sortBy. ' ' .$sortOrder)
            ->paginate(50);

        if (count($parameters) > 0) {
            $authors->appends($parameters);
        }

        return view('admin_panel.shop.author.index', [
            'content'      => $content,
            'authors'      => $authors,
            'sort_by'      => $sortBy,
            'sort_order'   => $sortOrder
        ]);
    }

    public function create()
    {
        $content = (new AuthorPageContent())->editAndCreateAuthorPageContent();
        return view('admin_panel.shop.author.create', [
            'content'        => $content,
        ]);
    }


    public function store (Request $request) {

        $this->validateRequest($request);

        (new AuthorRepository())->create($request->except(['_token', 'x1', 'x2', 'y1', 'y2' , 'img_width', 'img_height', 'image_hidden_file']));

        return redirect()
            ->route('admin.shop.researches.authors')
            ->with('success', 'Автор успешно добавлен.');

    }

    public function edit($id){

        $content      = (new AuthorPageContent())->editAndCreateAuthorPageContent();
        $author        = (new AuthorRepository())->getById($id);

        return view('admin_panel.shop.author.edit', [
            'content'      => $content,
            'author'       => $author,
        ]);
    }


    public function update(Request $request, $id) {

        $this->validateRequest($request);

        (new AuthorRepository())->updateById($id, $request->all());

        return redirect()
            ->route('admin.shop.researches.authors')
            ->with('success', 'Автор успешно обновлен.');
    }

    public function destroy ($id) {
        (new AuthorRepository())->deleteById($id);
        return redirect()
            ->back()
            ->with('success', 'Автор успешно удален.');
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
        $directory = 'researches/author/'. Carbon::now()->format('Y-m-d');

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
        /*$im->resize(130, null, function ($constraint) {
            $constraint->aspectRatio();
        });*/
        $im->save(public_path() . '/storage/'.$directory.'/' . $file_name);

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
