<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem2workflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item2workflow', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('workflow_id');
            $table->foreign('workflow_id')
                  ->references('id')
                  ->on('workflows')
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
        Schema::dropIfExists('item2workflow');
    }
}
