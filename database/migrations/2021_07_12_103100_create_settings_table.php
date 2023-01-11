<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('value')->nullable();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();
        });

        Setting::create(['name' => 'book_home_slider', 'value' => 3, 'status' => 1]);
        Setting::create(['name' => 'book_home_shipping', 'value' => 50, 'status' => 1]);
        Setting::create(['name' => 'book_home_shipping_out', 'value' => 100, 'status' => 1]);
        Setting::create(['name' => 'book_home_free_shipping', 'value' => 2000, 'status' => 1]);
        Setting::create(['name' => 'book_home_gift_wrap', 'value' => 30, 'status' => 1]);
        Setting::create(['name' => 'book_home_menu', 'value' => [], 'status' => 1]);
        Setting::create(['name' => 'book_home_footer', 'value' => [], 'status' => 1]);

        Setting::create(['name' => 'book_home_top_ad', 'value' => ['link' => '', 'image' => '', 'status' => 0], 'status' => 1]);
        Setting::create(['name' => 'book_home_shipping_cod', 'value' => 50, 'status' => 1]);
        Setting::create(['name' => 'book_home_shipping_out_cod', 'value' => 100, 'status' => 1]);

        Setting::create(['name' => 'book_home_extra_discount', 'value' => ['bkash' => 10, 'nagad' => 10, 'ssl' => 10], 'status' => 1]);

        Setting::create(['name' => 'book_home_block_1', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_2', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_3', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_4', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_5', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_6', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_7', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_8', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_9', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_10', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_11', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_12', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_13', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_14', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_15', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_16', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_17', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_18', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_19', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);
        Setting::create(['name' => 'book_home_block_20', 'status' => 0, 'value' => ['take_items' => 10, 'show_items' => 6, 'theme' => '', 'bg' => '', 'title' => '', 'category' => []]]);

        Setting::create(['name' => 'subscription_1', 'status' => 1, 'value' => 200]);
        Setting::create(['name' => 'subscription_3', 'status' => 1, 'value' => 200 * 3]);
        Setting::create(['name' => 'subscription_6', 'status' => 1, 'value' => 200 * 6]);
        Setting::create(['name' => 'subscription_12', 'status' => 1, 'value' => 200 * 12]);

        Setting::create(['name' => 'feature_1', 'status' => 1, 'value' => ['image' => '', 'link' => '', 'bg' => '']]);
        Setting::create(['name' => 'feature_2', 'status' => 1, 'value' => ['image' => '', 'link' => '', 'bg' => '']]);
        Setting::create(['name' => 'feature_3', 'status' => 1, 'value' => ['image' => '', 'link' => '', 'bg' => '']]);

        Setting::create(['name' => 'book_home_fixed_1', 'status' => 0, 'value' => ['name' => '', 'position' => '', 'data_type' => '', 'time_period' => '', 'bg' => '']]);
        Setting::create(['name' => 'book_home_fixed_2', 'status' => 0, 'value' => ['name' => '', 'position' => '', 'data_type' => '', 'time_period' => '', 'bg' => '']]);
        Setting::create(['name' => 'book_home_fixed_3', 'status' => 0, 'value' => ['name' => '', 'position' => '', 'data_type' => '', 'time_period' => '', 'bg' => '']]);
        Setting::create(['name' => 'book_home_fixed_4', 'status' => 0, 'value' => ['name' => '', 'position' => '', 'data_type' => '', 'time_period' => '', 'bg' => '']]);
        Setting::create(['name' => 'book_home_fixed_5', 'status' => 0, 'value' => ['name' => '', 'position' => '', 'data_type' => '', 'time_period' => '', 'bg' => '']]);
        Setting::create(['name' => 'book_home_fixed_6', 'status' => 0, 'value' => ['name' => '', 'position' => '', 'data_type' => '', 'time_period' => '', 'bg' => '']]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
