<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiment2workflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment2workflow', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('experiment_id');
            $table->foreign('experiment_id')
                  ->references('id')
                  ->on('experiments')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('workflow_id');
            $table->foreign('workflow_id')
                  ->references('id')
                  ->on('workflows')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('experiment2workflow');
    }
}
