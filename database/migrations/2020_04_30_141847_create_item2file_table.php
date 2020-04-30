<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem2fileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item2file', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')
                  ->references('id')
                  ->on('files')
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
        Schema::dropIfExists('item2file');
    }
}
