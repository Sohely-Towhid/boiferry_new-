<?php

namespace App\Console\Commands;

use App\Models\Author;
use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;

class MakeWebP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'img:webp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $size[] = ['name' => 'lg', 'height' => 1200, 'width' => 1200, 'quality' => 100];
        $size[] = ['name' => 'md', 'height' => 600, 'width' => 600, 'quality' => 100];
        $size[] = ['name' => 'sm', 'height' => 300, 'width' => 300, 'quality' => 50];
        $size[] = ['name' => 'xs', 'height' => 150, 'width' => 150, 'quality' => 30];
        $items  = Author::all();
        foreach ($items as $key => $item) {
            echo "Author: " . $item->id . "\n";
            $image = false;
            if (!preg_match('/$1webp/', $image)) {
                $image = public_path('assets/images/' . $item->photo);
                if ($item->photo && file_exists($image)) {
                    foreach ($size as $__key => $value) {
                        $img      = Image::make($image)->encode('webp', $value['quality'])->resize($value['height'], $value['width']);
                        $img_path = public_path('assets/images/' . str_replace("redactor/", "redactor/{$value['name']}_", $item->photo));
                        $img_path = preg_replace('/(.*\.)([a-z]+)$/', "\$1webp", $img_path);
                        $img->save($img_path, $value['quality']);
                        echo "Img Size: " . $value['name'] . "\n";
                    }
                    $item->photo = preg_replace('/(.*\.)([a-z]+)$/', "\$1webp", $item->photo);
                    $item->save();
                    echo "Author Saved!\n";
                }
            } else {
                echo "Skip\n";
            }
        }
        /*$size[] = ['name' => 'lg', 'height' => 664, 'width' => 1000, 'quality' => 100];
    $size[] = ['name' => 'md', 'height' => 332, 'width' => 500, 'quality' => 100];
    $size[] = ['name' => 'sm', 'height' => 199, 'width' => 300, 'quality' => 50];
    $size[] = ['name' => 'xs', 'height' => 66, 'width' => 100, 'quality' => 30];
    $items  = Book::all();
    foreach ($items as $key => $item) {
    echo "Book: " . $item->id . "\n";
    $imgs = [];
    foreach ($item->images as $jjjj => $image) {
    if (!preg_match('/$1webp/', $image)) {
    foreach ($size as $__key => $value) {
    if (file_exists(public_path('assets/images/' . $image))) {
    $imgs[]   = preg_replace('/(.*\.)([a-z]+)$/', "\$1webp", $image);
    $img      = Image::make(public_path('assets/images/' . $image))->encode('webp', $value['quality'])->resize($value['height'], $value['width']);
    $img_path = public_path('assets/images/' . str_replace("redactor/", "redactor/{$value['name']}_", $image));
    $img_path = preg_replace('/(.*\.)([a-z]+)$/', "\$1webp", $img_path);
    $img      = $img->insert(public_path("assets/images/book-watermark-{$value['name']}.png"), 'center');
    $img->save($img_path, $value['quality']);
    echo "Img Size: " . $value['name'] . "\n";
    }
    }
    }
    }
    if (count($imgs) > 0) {
    $item->images = $imgs;
    $item->save();
    echo "Book: Saved!\n";
    } else {
    echo "Skip\n";
    }
    }*/
    }
}
