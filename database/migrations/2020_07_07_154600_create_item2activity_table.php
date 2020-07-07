<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem2activityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item2activity', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')
                  ->references('id')
                  ->on('activities')
                  ->onDelete('cascade');

            $table->nullableMorphs('item');

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
        Schema::dropIfExists('item2activity');
    }
}
