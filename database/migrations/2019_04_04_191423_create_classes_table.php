<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('lesson_id')->nullable();
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('student_id');
            $table->string('type_code', 3)->default('GR');
            $table->string('status_code', 3)->default('PEN');
            $table->dateTime('date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_code')->references('key')->on('codes_meta');
            $table->foreign('type_code')->references('key')->on('codes_meta');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
