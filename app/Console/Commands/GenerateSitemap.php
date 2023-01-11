<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Page;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

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
        $main    = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $pages   = Page::where('status', 1)->get()->pluck('slug')->toArray();
        $pages[] = '/';
        $pages[] = '/books';
        // page Sitemaps
        $data = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";
        foreach ($pages as $key => $value) {
            $data .= "    <url>
        <loc>" . url($value) . "</loc>
        <lastmod>" . date('Y-m-d') . "</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>\n";
        }
        $data .= '</urlset>';
        file_put_contents(public_path('sitemap/sitemap-page.xml'), $data);
        $main .= "  <sitemap>\n     <loc>" . url('sitemap/sitemap-page.xml') . "</loc>\n    </sitemap>\n";

        // Book
        $skip = 0;
        for ($i = 0; $i < ceil(Book::where('status', 1)->count('id') / 100); $i++) {
            $skip = $i * 100;
            $data = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";
            foreach (Book::where('status', 1)->take(100)->skip($skip)->get() as $key => $value) {
                $data .= "    <url>
        <loc>" . url("/book/" . $value->slug) . "</loc>
        <lastmod>" . $value->updated_at->format('Y-m-d') . "</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>\n";
            }
            $data .= '</urlset>';
            file_put_contents(public_path('sitemap/sitemap-' . ($i + 1) . '.xml'), $data);
            $main .= "  <sitemap>\n     <loc>" . url('sitemap/sitemap-' . ($i + 1) . '.xml') . "</loc>\n    </sitemap>\n";
        }

        $main .= '</sitemapindex>';
        file_put_contents(public_path('sitemap/sitemap.xml'), $main);
        @file_get_contents("http://www.google.com/webmasters/tools/ping?sitemap=https://boiferry.com/sitemap/sitemap.xml");
        @file_get_contents("https://bing.com/webmaster/ping.aspx?sitemap=https://boiferry.com/sitemap/sitemap.xml");
    }
}
