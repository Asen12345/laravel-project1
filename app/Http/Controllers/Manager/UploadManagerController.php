<?php

namespace App\Http\Controllers\Manager;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadManagerController extends Controller
{

    public function preUploadImage(Request $request) {
        //The value is in kilobytes. I.e. max:10240 = max 10 MB.
        $max_size_file = 5;
        //Format for upload file.
        $format = ['jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG'];

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
                'error' => 'Неверный формат файла.'
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
        $previous_route = $this->getPreviousRoute();
        if ((!empty($previous_route) == 'admin.users.edit')) {
            $directory = 'avatar/'. Carbon::now()->format('Y-m-d');
        } else {
            $directory = 'scene';
        }

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

    /**
     * Upload image to 'images' disk, return location url
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadCustomImage(Request $request)
    {
        $disk = 'images';
        $filename = $request->file('image')->store('', $disk);
        $path = Storage::disk($disk)->url($filename);

        return response()->json(['location' => $path], 200);
    }
}
