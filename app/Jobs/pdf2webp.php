<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ImagickException;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\PdfToImage\Pdf;

class pdf2webp implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $book;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 60;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->book->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $book   = $this->book;
        $source = storage_path('temp-pdf');
        $dist   = public_path('assets/preview');
        if ($book->preview) {
            $preview = str_replace(['redactor/', 'temp-pdf/'], '', $book->preview);
            $nf      = 'preview/' . str_replace('.pdf', '', $preview);
            $folder  = $dist . "/" . str_replace('.pdf', '', $preview);

            $preview = $source . "/" . $preview;
            if (file_exists($preview)) {
                echo "+";
                if (!is_dir($folder)) {
                    mkdir($folder);
                }

                $pdf = new Pdf($preview);
                try {
                    $pdf->saveAllPagesAsImages($folder);
                } catch (ImagickException $e) {
                    echo "E!";
                }

                $images = array_diff(scandir($folder), array('.', '..'));
                echo "Y";
                foreach ($images as $image) {
                    echo "^";
                    $new_path = str_replace('jpg', 'webp', $folder . "/" . $image);
                    Image::make($folder . "/" . $image)
                        ->insert(public_path("assets/images/watermark.png"), 'center')
                        ->resize(664, 1000)
                        ->encode('webp', 70)
                        ->save($new_path, 70);

                    if (file_exists($new_path)) {
                        @unlink($folder . "/" . $image);
                    }
                }
                $book->timestamps = false;
                $book->preview    = $nf . "|" . count($images);
                $book->save();
                @unlink($preview);
            } elseif (preg_match("/preview/", $book->preview)) {
                echo "OK";
            } else {
                echo "\n\nPDF Issue: {$book->id}\n\n";
            }
        }
    }
}
