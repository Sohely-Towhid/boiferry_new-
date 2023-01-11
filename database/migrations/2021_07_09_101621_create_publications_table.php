<?php

use App\Models\Publication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_bn');
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        $xml   = simplexml_load_string(file_get_contents(storage_path('data/publisher_urls.xml')));
        $json  = json_decode(json_encode($xml));
        $items = [];
        foreach ($json->url as $key => $value) {
            $name         = preg_replace('/(.*publisher\/[0-9]+\/)(.*)/', '$2', $value->loc);
            $slug         = str_replace(["--", ',', '+'], ['-', '', '-'], $name);
            $name         = ucwords(str_replace(["-"], [' '], $slug));
            $items[$slug] = ['slug' => $slug, 'name' => $name, 'name_bn' => $name, 'created_at' => date('Y-m-d H:i:s')];
        }

        Publication::insert($items);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
