<?php

namespace App\Console\Commands;

use App\Curl;
use Illuminate\Console\Command;

class FakeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake:data';

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
        for ($j = 0; $j < 100; $j++) {

            $http = new Curl();
            $pg   = ['cod', 'sslcommerz'];
            for ($i = 0; $i < rand(1, 5); $i++) {
                $response = $http->get('http://books.books.test/ajax-cart?' . http_build_query(['type' => 'add2cart', 'product_id' => 8, 'quantity' => rand(1, 3)]));
            }
            $response = $http->get('http://books.books.test/checkout');
            preg_match('/_token" value="([a-z0-9]+)">/i', $response, $token);
            $post['_token']  = @$token[1];
            $post['name']    = "Name_" . $j;
            $post['email']   = 'name_' . $j . '@name.com';
            $post['mobile']  = '0'+(1950010052 + 1 + $j);
            $post['tos']     = 'yes';
            $post['payment'] = $pg[array_rand($pg)];
            $post['bill']    = [
                'street'   => rand(10, 100) . ' Chan Mia Road',
                'country'  => 'Bangladesh',
                'district' => 'Dhaka',
                'city'     => 'Dhaka',
                'postcode' => '1236',
            ];
            $response = $http->post('http://books.books.test/checkout', '', $post);
        }
        return 0;
    }
}
