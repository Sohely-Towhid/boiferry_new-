<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->decimal('total')->unsigned()->default(0);
            $table->decimal('coupon_discount')->unsigned()->default(0);
            $table->tinyInteger('cod')->unsigned()->default(0);
            $table->tinyInteger('print')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(1);
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
        Schema::dropIfExists('vendor_invoices');
    }
}
