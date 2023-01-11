<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->text('seo')->nullable();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();
        });

        Page::create(['slug' => Str::slug('Return Policy'), 'name' => 'Return Policy', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Refund Policy'), 'name' => 'Refund Policy', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Support Policy'), 'name' => 'Support Policy', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Terms of Service'), 'name' => 'Terms of Service', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Seller Policy'), 'name' => 'Seller Policy', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Privacy Policy'), 'name' => 'Privacy Policy', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('About Us'), 'name' => 'About Us', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Contact Us'), 'name' => 'Contact Us', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Become a Seller'), 'name' => 'Become a Seller', 'description' => '', 'status' => 0]);
        Page::create(['slug' => Str::slug('Support'), 'name' => 'Support', 'description' => '', 'status' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
