<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiment2activityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment2activity', function (Blueprint $table) {
            $table->unsignedBigInteger('experiment_id');
            $table->foreign('experiment_id')
                  ->references('id')
                  ->on('experiments')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')
                  ->references('id')
                  ->on('activities')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiment2process');
    }
}
