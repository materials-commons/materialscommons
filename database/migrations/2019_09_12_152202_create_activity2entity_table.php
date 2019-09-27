<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivity2entityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity2entity', function(Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')
                  ->references('id')
                  ->on('activities')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('entity_id');
            $table->foreign('entity_id')
                  ->references('id')
                  ->on('entities')
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
        Schema::dropIfExists('activity2entity');
    }
}
