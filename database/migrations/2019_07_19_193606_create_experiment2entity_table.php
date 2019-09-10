<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiment2entityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment2entity', function (Blueprint $table) {
            $table->unsignedBigInteger('experiment_id');
            $table->foreign('experiment_id')
                  ->references('id')
                  ->on('experiments')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('entity_id');
            $table->foreign('entity_id')
                  ->references('id')
                  ->on('entities')
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
        Schema::dropIfExists('experiment2entity');
    }
}
