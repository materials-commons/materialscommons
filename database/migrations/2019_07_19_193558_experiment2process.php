<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Experiment2process extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiment2process', function (Blueprint $table) {
            $table->unsignedBigInteger('experiment_id');
            $table->foreign('experiment_id')
                ->references('id')
                ->on('experiment')
                ->onDelete('cascade');

            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
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
