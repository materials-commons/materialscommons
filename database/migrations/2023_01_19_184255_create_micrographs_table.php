<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMicrographsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('micrographs', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->float('micron_bar')->nullable();
            $table->string('micron_bar_units')->nullable();
            $table->integer('micron_bar_px')->nullable();
            $table->string('magnification')->nullable();
            $table->string('detector')->nullable();
            $table->unsignedBigInteger('uhcs_sample_id')->nullable();
            $table->foreign('uhcs_sample_id')
                  ->references('id')
                  ->on('uhcs_samples')
                  ->onDelete('cascade');
            $table->string('primary_microconstituent')->nullable();
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
        Schema::dropIfExists('micrographs');
    }
}
