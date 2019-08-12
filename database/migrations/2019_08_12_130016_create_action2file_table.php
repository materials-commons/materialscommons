<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAction2fileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action2file', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')
                  ->references('id')
                  ->on('actions');

            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')
                  ->references('id')
                  ->on('files');

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
        Schema::dropIfExists('action2file');
    }
}
