<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBooksTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->tinyInteger('pre_order')->unsigned()->default(0);
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->tinyInteger('pre_order')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function ($table) {
            $table->dropColumn('pre_order');
        });
        Schema::table('invoices', function ($table) {
            $table->dropColumn('pre_order');
        });
    }
}
