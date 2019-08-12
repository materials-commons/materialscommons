<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityState2fileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_state2file', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('entity_state_id');
            $table->foreign('entity_state_id')
                  ->references('id')
                  ->on('entity_states');

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
        Schema::dropIfExists('entity_state2file');
    }
}
