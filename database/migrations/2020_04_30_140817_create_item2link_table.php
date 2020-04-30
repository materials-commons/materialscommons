<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem2linkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item2link', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('link_id');
            $table->foreign('link_id')
                  ->references('id')
                  ->on('links')
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
        Schema::dropIfExists('community2link');
    }
}
