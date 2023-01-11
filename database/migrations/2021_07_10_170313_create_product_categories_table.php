<?php

use App\Models\ProductCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent')->sunsigned()->default(0);
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_bn');
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        ProductCategory::create(['slug' => 'mans-fashion', 'name' => 'Men\'s Fashion', 'name_bn' => 'Men\'s Fashion']);
        ProductCategory::create(['slug' => 'womens-fashion', 'name' => 'Women\'s Fashion', 'name_bn' => 'Women\'s Fashion']);
        ProductCategory::create(['slug' => 'gift-item', 'name' => 'Gift Item', 'name_bn' => 'Gift Item']);
        ProductCategory::create(['slug' => 'electronic-devices', 'name' => 'Electronic Devices', 'name_bn' => 'Electronic Devices']);
        ProductCategory::create(['slug' => 'electronic-accessories', 'name' => 'Electronic Accessories', 'name_bn' => 'Electronic Accessories']);
        ProductCategory::create(['slug' => 'tv-home-appliances', 'name' => 'TV & Home Appliances', 'name_bn' => 'TV & Home Appliances']);
        ProductCategory::create(['slug' => 'health-beauty', 'name' => 'Health & Beauty', 'name_bn' => 'Health & Beauty']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
