<?php

use App\Enums\ActionType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Action2entity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action2entity', function (Blueprint $table) {
            $table->unsignedBigInteger('action_id');
            $table->foreign('action_id')
                ->references('id')
                ->on('actions')
                ->onDelete('cascade');

            $table->unsignedBigInteger('entity_id');
            $table->foreign('entity_id')
                ->references('id')
                ->on('entities')
                ->onDelete('cascade');

            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onDelete('cascade');

            $table->tinyInteger('action')->unsigned()->default(ActionType::Creates);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process2sample');
    }
}
