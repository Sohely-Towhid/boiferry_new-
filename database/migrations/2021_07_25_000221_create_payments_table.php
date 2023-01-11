<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->double('amount', 13, 2)->unsigned();
            $table->double('fee', 13, 2)->unsigned();
            $table->double('pg_fee', 13, 2)->unsigned();
            $table->string('method', 20)->nullable();
            $table->tinyInteger('type')->unsigned()->default(0);
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
        Schema::dropIfExists('payments');
    }
}
