<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('script_runs', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->string('arguments');

            $table->string("docker_container_id");

            $table->unsignedBigInteger('script_id');
            $table->foreign('script_id')
                  ->references('id')
                  ->on('scripts');

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users');

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->datetime("started_at");
            $table->datetime("finished_at");
            $table->datetime("failed_at");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('script_runs');
    }
};
