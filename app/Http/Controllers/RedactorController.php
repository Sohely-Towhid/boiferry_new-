<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class RedactorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     *
     * Upload redactor Image
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function redactorImage(Request $request)
    {
        $request->validate([
            'file.*' => 'image|required|max:1024',
        ]);

        $re = [];
        // $ups = json_decode(@file_get_contents(public_path('assets/images/redactor/images.json')), true);
        foreach ($request->file('file') as $key => $file) {

            $ext  = $file->extension();
            $path = $file->store('redactor', 'redactor');
            // save as webp
            $webp = preg_replace("/" . $ext . "$/", 'webp', $path);
            Image::make(public_path('assets/images/' . $path))->encode('webp', 100)->save(public_path('assets/images/' . $webp), 100);

            $size[] = ['name' => 'lg', 'height' => 1200, 'width' => 1200, 'quality' => 100];
            $size[] = ['name' => 'md', 'height' => 600, 'width' => 600, 'quality' => 100];
            $size[] = ['name' => 'sm', 'height' => 300, 'width' => 300, 'quality' => 30];

            if ($request->book == 'yes') {
                $size   = [];
                $size[] = ['name' => 'lg', 'height' => 664, 'width' => 1000, 'quality' => 100];
                $size[] = ['name' => 'md', 'height' => 332, 'width' => 500, 'quality' => 100];
                $size[] = ['name' => 'sm', 'height' => 199, 'width' => 300, 'quality' => 50];
                $size[] = ['name' => 'xs', 'height' => 66, 'width' => 100, 'quality' => 30];
            }

            foreach ($size as $__key => $value) {
                $img      = Image::make(public_path('assets/images/' . $path))->encode('webp', $value['quality'])->resize($value['height'], $value['width']);
                $img_path = preg_replace("/" . $ext . "$/", 'webp', public_path('assets/images/' . str_replace("redactor/", "redactor/{$value['name']}_", $path)));
                if ($request->book == 'yes') {
                    $img = $img->insert(public_path("assets/images/book-watermark-{$value['name']}.png"), 'center');
                }
                $img->save($img_path, $value['quality']);
            }
            $np = preg_replace("/" . $ext . "$/", 'webp', $path);
            if ($request->type == 'rta') {
                $re['file-' . $key] = ["url" => showImage($np), 'thumb' => showImage($np, 'sm'), 'id' => md5(rand(10, 99) . date('Y-m- H:i:s'))];
            } else {
                $re['file-' . $key] = ["url" => $np, 'thumb' => showImage($np, 'sm'), 'id' => md5(rand(10, 99) . date('Y-m- H:i:s'))];
            }

            // $ups[]              = ['thumb' => url('assets/images/' . str_replace("redactor/", "redactor/sm_", $np)), 'url' => url('assets/images/' . $np), 'id' => md5(rand(10, 99) . date('Y-m- H:i:s')), 'title' => $file->getClientOriginalName()];
        }
        // file_put_contents(public_path('assets/images/redactor/images.json'), json_encode($ups));
        return $re;
    }

    /**
     * Show Redactor Image List JSON
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function redactorImageList(Request $request)
    {
        return file_get_contents(public_path('assets/images/redactor/images.json'));
    }

}
