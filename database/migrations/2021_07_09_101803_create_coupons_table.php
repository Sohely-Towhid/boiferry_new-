<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned()->default(0)->nullable();
            $table->integer('book_id')->unsigned()->default(0)->nullable();
            $table->integer('user_id')->unsigned()->default(0)->nullable();
            $table->integer('vendor_id')->unsigned()->default(0)->nullable();
            $table->integer('category_id')->unsigned()->default(0)->nullable();
            $table->string('coupon_type', 50);
            $table->string('code', 100)->unique();
            $table->decimal('amount', 13, 2)->unsigned()->default(0);
            $table->decimal('min_shopping', 13, 2)->unsigned()->default(0)->nullable();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->integer('min')->unsigned()->default(1)->nullable();
            $table->integer('max_use')->unsigned()->default(0)->nullable();
            $table->integer('used')->unsigned()->default(0);
            $table->date('start')->nullable();
            $table->date('expire')->nullable();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->string('referral', 50)->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
