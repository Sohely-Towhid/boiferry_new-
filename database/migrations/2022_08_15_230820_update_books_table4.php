<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBooksTable4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->decimal('ebook_rate', 13, 2)->unsigned()->dafault(0);
            $table->decimal('ebook_sale', 13, 2)->unsigned()->dafault(0);
            $table->tinyInteger('subscription')->unsigned()->dafault(0);
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
            $table->dropColumn('ebook_rate');
            $table->dropColumn('ebook_sale');
            $table->dropColumn('subscription');
        });
    }
}
