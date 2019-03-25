<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('country_code',2);
            $table->unsignedInteger('time_zone_id');
            $table->string('avatar')->default('no-img.png');
            $table->text('description')->nullable();
            $table->string('rol_code',2);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('country_code')->references('code')->on('countries');
            $table->foreign('time_zone_id')->references('id')->on('time_zones');
            $table->foreign('rol_code')->references('key')->on('codes_meta');

        });
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
