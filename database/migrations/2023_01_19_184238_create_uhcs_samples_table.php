<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUHCSSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uhcs_samples', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->float('anneal_time')->nullable();
            $table->string('anneal_time_unit')->nullable();
            $table->float('anneal_temperature')->nullable();
            $table->string('anneal_temperature_unit')->nullable();
            $table->string('cool_method')->nullable();
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
        Schema::dropIfExists('u_h_c_s_samples');
    }
}
