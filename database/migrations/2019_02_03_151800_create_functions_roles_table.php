<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionsRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('functions_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rol_code');
            $table->string('function_code');

            $table->foreign('rol_code')->references('code')->on('roles');
            $table->foreign('function_code')->references('code')->on('functions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('functions_roles');
    }
}
