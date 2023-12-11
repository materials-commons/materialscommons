<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community2ds_waiting_approval', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('community_id');
            $table->foreign('community_id')
                  ->references('id')
                  ->on('communities')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('dataset_id');
            $table->foreign('dataset_id')
                  ->references('id')
                  ->on('datasets')
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
        Schema::dropIfExists('community2ds_waiting_approval');
    }
};
