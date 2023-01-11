<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('processed_by')->unsigned()->default(0);
            $table->integer('lock_by')->unsigned()->default(0);
            $table->integer('billing_id')->unsigned()->default(0);
            $table->integer('shipping_id')->unsigned()->default(0);
            $table->string('session')->nullable();
            $table->string('note')->nullable();
            $table->decimal('shipping', 13, 2)->unsigned()->default(0);
            $table->decimal('total', 13, 2)->unsigned()->default(0);
            $table->decimal('discount', 13, 2)->unsigned()->default(0);
            $table->decimal('coupon_discount', 13, 2)->unsigned()->default(0);
            $table->decimal('partial_payment', 13, 2)->unsigned()->default(0);
            $table->string('coupon', 100)->nullable();
            $table->string('payment', 50)->nullable();
            $table->tinyInteger('print')->unsigned()->default(0);
            $table->date('shipment_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('last_mail')->nullable();
            $table->text('system_note')->nullable();
            $table->string('tracking')->nullable();
            $table->string('referral', 50)->nullable();
            $table->decimal('gift_wrap', 13, 2)->unsigned()->default(0);
            $table->tinyInteger('stock_update')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('packed')->unsigned()->default(0);
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
        Schema::dropIfExists('invoices');
    }
}
