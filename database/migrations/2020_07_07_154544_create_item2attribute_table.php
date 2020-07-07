<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem2attributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item2attribute', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')
                  ->references('id')
                  ->on('attributes')
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
        Schema::dropIfExists('item2attribute');
    }
}
