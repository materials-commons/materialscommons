<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->text('description');
            $table->json('metadata');
            $table->morphs('metadatable');

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users');

            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('dataset_id')->nullable();
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
        Schema::dropIfExists('metadata');
    }
};
