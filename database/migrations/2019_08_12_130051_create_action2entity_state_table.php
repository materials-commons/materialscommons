<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAction2entityStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action2entity_state', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')
                  ->references('id')
                  ->on('actions');

            $table->unsignedBigInteger('entity_state_id');
            $table->foreign('entity_state_id')
                  ->references('id')
                  ->on('entity_states');

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
        Schema::dropIfExists('action2entity_state');
    }
}
