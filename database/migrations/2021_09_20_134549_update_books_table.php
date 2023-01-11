<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('vpc', 13, 2)->nullable()->default(0);
        });
        Schema::table('libraries', function (Blueprint $table) {
            $table->decimal('apc', 13, 2)->nullable()->default(0);
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
            $table->dropColumn('vpc');
        });
        Schema::table('libraries', function ($table) {
            $table->dropColumn('apc');
        });
    }
}
