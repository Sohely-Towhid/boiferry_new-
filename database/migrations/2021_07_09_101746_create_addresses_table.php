<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name', 150);
            $table->string('company', 150)->nullable();
            $table->string('country', 150)->nullable();
            $table->string('street')->nullable();
            $table->string('street2')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('mobile2', 15)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('geo', 50)->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
