<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Experiment2sample extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment2sample', function (Blueprint $table) {
            $table->unsignedBigInteger('experiment_id');
            $table->foreign('experiment_id')
                ->references('id')
                ->on('experiment')
                ->onDelete('cascade');

            $table->unsignedBigInteger('sample_id');
            $table->foreign('sample_id')
                ->references('id')
                ->on('samples')
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
        Schema::dropIfExists('experiment2sample');
    }
}
