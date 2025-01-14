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
        Schema::create('datahq_instances', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('current_explorer')->nullable();

            $table->json('overview_explorer_state')->nullable();
            $table->json('samples_explorer_state')->nullable();
            $table->json('processes_explorer_state')->nullable();
            $table->json('computations_explorer_state')->nullable();

            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('project_id')
                  ->nullable()
                  ->constrained('projects')
                  ->onDelete('cascade');

            $table->foreignId('experiment_id')
                  ->nullable()
                  ->constrained('experiments')
                  ->onDelete('cascade');

            $table->foreignId('dataset_id')
                  ->nullable()
                  ->constrained('datasets')
                  ->onDelete('cascade');

            $table->datetime('current_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datahq_instances');
    }
};
