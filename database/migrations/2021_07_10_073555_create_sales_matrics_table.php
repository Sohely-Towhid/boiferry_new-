<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesMatricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_matrics', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id')->unsigned()->default(0);
            $table->integer('book_id')->unsigned()->default(0);
            $table->integer('vendor_id')->unsigned()->default(0);
            $table->integer('product_id')->unsigned()->default(0);
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_matrics');
    }
}
