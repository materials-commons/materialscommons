<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataset2workflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dataset2workflow', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('dataset_id');
            $table->foreign('dataset_id')
                  ->references('id')
                  ->on('datasets')
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
        Schema::dropIfExists('dataset2workflow');
    }
}
