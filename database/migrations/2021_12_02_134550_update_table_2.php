<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->text('book_id')->nullable()->change();
            $table->text('product_id')->nullable()->change();
            $table->text('vendor_id')->nullable()->change();
            $table->text('category_id')->nullable()->change();
            $table->text('author_id')->nullable();
            $table->text('publisher_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function ($table) {
            $table->integer('book_id')->unsigned()->change();
            $table->integer('product_id')->unsigned()->change();
            $table->integer('vendor_id')->unsigned()->change();
            $table->integer('category_id')->unsigned()->change();
            $table->dropColumn('author_id');
            $table->dropColumn('publisher_id');
        });
    }
}
