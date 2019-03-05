<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('functions', function (Blueprint $table) {
            $table->string('code')->unique();
            $table->string('title');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('order')->default(0);
            $table->boolean('menu')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->primary('code');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('functions');
    }
}
