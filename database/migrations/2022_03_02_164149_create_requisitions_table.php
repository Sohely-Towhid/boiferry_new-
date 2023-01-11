<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned()->default(0);
            $table->integer('quantity')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::table('books', function (Blueprint $table) {
            $table->integer('actual_stock')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');

        Schema::table('books', function ($table) {
            $table->dropColumn('actual_stock');
        });
    }
}
