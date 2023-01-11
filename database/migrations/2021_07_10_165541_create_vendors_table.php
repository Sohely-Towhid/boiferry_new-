<?php

use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('address')->nullable();
            $table->string('logos')->nullable();
            $table->text('banners')->nullable();
            $table->text('category')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('details')->nullable();
            $table->text('files')->nullable();
            $table->integer('followers')->unsigned();
            $table->decimal('rating')->unsigned();
            $table->decimal('fee', 13, 2)->unsigned()->default(5);
            $table->tinyInteger('book')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();
        });

        Vendor::create([
            'user_id'   => 2,
            'name'      => 'Winners Bazar',
            'slug'      => 'winners-bazar',
            'address'   => '3rd Floor, Abedin Bhaban, Soni Akra, Dhaka',
            'logos'     => [],
            'banners'   => [],
            'files'     => [],
            'category'  => ['*'],
            'book'      => 1,
            'followers' => 0,
            'rating'    => 0,
            'status'    => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
