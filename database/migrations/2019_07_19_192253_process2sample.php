<?php

use App\Enums\ActionType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Process2sample extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process2sample', function (Blueprint $table) {
            $table->unsignedBigInteger('process_id');
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onDelete('cascade');

            $table->unsignedBigInteger('sample_id');
            $table->foreign('sample_id')
                ->references('id')
                ->on('samples')
                ->onDelete('cascade');

            $table->unsignedBigInteger('attribute_set_id');
            $table->foreign('attribute_set_id')
                ->references('id')
                ->on('attribute_sets')
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
