<?php

use App\Models\Author;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_bn');
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        $items = [];

        Author::create(['slug' => 'humayun-ahmed', 'name' => 'Humayun Ahmed', 'name_bn' => 'হুমায়ূন আহমেদ']);

        /*$xml  = simplexml_load_string(file_get_contents(storage_path('data/author_urls_1.xml')));
    $json = json_decode(json_encode($xml));
    foreach ($json->url as $key => $value) {
    $name         = trim(preg_replace('/(.*author\/[0-9]+\/)(.*)/', '$2', $value->loc));
    $slug         = trim(str_replace(["--", ',', '+', ' '], ['-', '', '-', ''], $name));
    $name         = ucwords(str_replace(["-"], [' '], $slug));
    $items[$slug] = ['slug' => $slug, 'name' => $name, 'name_bn' => $name, 'created_at' => date('Y-m-d H:i:s')];
    }
    $xml  = simplexml_load_string(file_get_contents(storage_path('data/author_urls_2.xml')));
    $json = json_decode(json_encode($xml));
    foreach ($json->url as $key => $value) {
    $name         = trim(preg_replace('/(.*author\/[0-9]+\/)(.*)/', '$2', $value->loc));
    $slug         = trim(str_replace(["--", ',', '+', ' '], ['-', '', '-', ''], $name));
    $name         = ucwords(str_replace(["-"], [' '], $slug));
    $items[$slug] = ['slug' => $slug, 'name' => $name, 'name_bn' => $name, 'created_at' => date('Y-m-d H:i:s')];
    }

    $items = array_chunk($items, 2000);
    foreach ($items as $key => $item) {
    Author::insert($item);
    }*/

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
    }
}
