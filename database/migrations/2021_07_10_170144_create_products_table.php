<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('brand_id')->unsigned()->default(0);
            $table->string('name');
            $table->text('images');
            $table->text('short_description');
            $table->longText('description');
            $table->text('seo')->nullable();
            $table->string('sku', 50);
            $table->string('size', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->decimal('rate', 13, 2)->unsigned();
            $table->decimal('sale', 13, 2)->unsigned();
            $table->integer('stock')->unsigned();
            $table->integer('sold')->unsigned()->default(0);
            $table->integer('point')->unsigned()->default(0);
            $table->string('shelf', 20)->nullable();
            $table->tinyInteger('type')->unsigned()->default(0);
            $table->string('rating_review', 100);
            $table->tinyInteger('in_home')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
