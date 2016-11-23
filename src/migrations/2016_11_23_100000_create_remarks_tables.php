<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemarksTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remarks_remarks', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->morphs('user');
            $table->tinyInteger('type');
            $table->timestamp('created_at')->nullable();

            $table->unique(['model_id', 'model_type', 'user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remarks_remarks');
    }
}
