<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->unsigned()->default(0);
            $table->string('name');
            $table->string('mobile', 20)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp', 80)->nullable();
            $table->string('password', 80);
            $table->string('role', 50)->default(serialize(['customer']));
            $table->decimal('balance', 13, 2)->unsigned()->default(0);
            $table->date('dob')->nullable();
            $table->rememberToken();
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->timestamps();
        });

        User::insert(['vendor_id' => 0, 'name' => 'System Admin', 'email' => 'admin@admin.com', 'mobile' => '8801950010050', 'role' => serialize(['admin']), 'password' => bcrypt('123456'), 'status' => 1]);
        User::insert(['vendor_id' => 1, 'name' => 'Book Vendor', 'email' => 'admin1@admin.com', 'mobile' => '8801950010051', 'role' => serialize(['vendor']), 'password' => bcrypt('123456'), 'status' => 1]);

        // alter table users drop index users_mobile_unique ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
