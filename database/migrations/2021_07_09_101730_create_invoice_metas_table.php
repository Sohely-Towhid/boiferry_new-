<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->integer('book_id')->unsigned()->default(0);
            $table->integer('product_id')->unsigned()->default(0);
            $table->integer('quantity')->unsigned()->default(0);
            $table->decimal('rate', 13, 2)->unsigned()->default(0);
            $table->decimal('discount', 13, 2)->unsigned()->default(0);
            $table->text('other_data')->nullable();
            $table->text('product')->nullable();
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
        Schema::dropIfExists('invoice_metas');
    }
}
