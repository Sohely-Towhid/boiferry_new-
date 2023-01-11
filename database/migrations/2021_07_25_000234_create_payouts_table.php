<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->double('amount', 13, 2)->unsigned();
            $table->double('fee', 13, 2)->unsigned();
            $table->double('pg_fee', 13, 2)->unsigned();
            $table->double('refund', 13, 2)->unsigned()->default(0);
            $table->string('method', 20)->nullable();
            $table->string('details', 20)->nullable();
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
        Schema::dropIfExists('payouts');
    }
}
