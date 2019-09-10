<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataset2activityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dataset2activity', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('dataset_id');
            $table->foreign('dataset_id')
                  ->references('id')
                  ->on('datasets')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')
                  ->references('id')
                  ->on('activities')
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
        Schema::dropIfExists('dataset2activity');
    }
}
